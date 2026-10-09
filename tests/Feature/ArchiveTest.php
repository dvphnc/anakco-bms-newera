<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\BlotterCase;
use App\Models\Concerns\Archivable;
use App\Models\Concerns\PermanentDelete;
use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use App\Support\RecycleBin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use LogicException;
use Tests\TestCase;

/**
 * Part 3.1: records are archived (soft deleted), never permanently deleted,
 * and the Admin can restore them from the Recycle Bin.
 */
class ArchiveTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $secretary;
    private Purok $purok;
    private int $street = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin     = User::factory()->create(['role' => 'Admin']);
        $this->secretary = User::factory()->create(['role' => 'Secretary']);
        $this->purok     = Purok::create(['name' => 'Purok 1']);
    }

    private function resident(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            'purok_id' => $this->purok->id, 'address' => ($this->street++).' Luna St.', 'birthdate' => '1980-01-01',
            'residency_status' => 'Active',
        ], $attrs))->fresh();
    }

    private function appointment(): DocumentAppointment
    {
        return DocumentAppointment::create([
            'appointment_number' => DocumentAppointment::generateNumber(), 'resident_name' => 'Andrea Villareal',
            'contact_number' => '09170000000', 'document_type' => 'Barangay Clearance', 'preferred_date' => today(),
        ]);
    }

    public function test_every_model_is_archivable_and_its_table_has_the_archive_columns(): void
    {
        foreach (glob(app_path('Models/*.php')) as $file) {
            $class = 'App\\Models\\'.basename($file, '.php');
            $this->assertContains(Archivable::class, class_uses_recursive($class), "$class does not use Archivable");

            $table = (new $class)->getTable();
            $this->assertTrue(Schema::hasColumns($table, ['deleted_at', 'deleted_by']), "$table is missing deleted_at / deleted_by");
        }
    }

    public function test_every_archivable_type_in_the_recycle_bin_points_to_a_real_model(): void
    {
        foreach (RecycleBin::TYPES as $key => [$label, $class]) {
            $this->assertTrue(class_exists($class), "$key: $class");
        }
        $this->actingAs($this->admin)->get(route('recycle-bin.index'))->assertOk();
    }

    public function test_archiving_a_resident_keeps_the_row_and_records_who_did_it(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)->delete(route('residents.destroy', $resident))->assertRedirect();

        $this->assertSoftDeleted($resident);
        $this->assertSame($this->secretary->id, Resident::withTrashed()->find($resident->id)->deleted_by);
        $this->assertNull(Resident::find($resident->id));
        $this->assertSame('deleted', ActivityLog::where('loggable_id', $resident->id)->where('loggable_type', Resident::class)->latest('id')->value('action'));
    }

    public function test_permanent_delete_is_blocked(): void
    {
        $resident = $this->resident();

        try {
            $resident->forceDelete();
            $this->fail('forceDelete should throw');
        } catch (LogicException) {
        }
        $this->assertNotNull(Resident::find($resident->id));

        // Only the explicit escape hatch (maintenance scripts) can remove a row
        PermanentDelete::allow(fn () => $resident->forceDelete());
        $this->assertNull(Resident::withTrashed()->find($resident->id));
        $this->assertFalse(PermanentDelete::allowed());
    }

    public function test_query_delete_archives_instead_of_removing_rows(): void
    {
        $this->resident();
        $this->resident();

        Resident::query()->delete();

        $this->assertSame(0, Resident::count());
        $this->assertSame(2, Resident::withTrashed()->count());
    }

    public function test_only_the_admin_can_open_the_recycle_bin_and_restore(): void
    {
        $resident = $this->resident();
        $resident->delete();

        $this->actingAs($this->secretary)->get(route('recycle-bin.index'))->assertRedirect(route('dashboard'));
        $this->actingAs($this->secretary)->patch(route('recycle-bin.restore', ['resident', $resident->id]))->assertRedirect(route('dashboard'));
        $this->assertSoftDeleted($resident);
    }

    public function test_recycle_bin_lists_archived_records_with_who_archived_them(): void
    {
        $resident = $this->resident(['first_name' => 'Andrea', 'last_name' => 'Villareal']);
        $this->resident(['first_name' => 'Kept', 'last_name' => 'Zamora']);
        $this->actingAs($this->secretary);
        $resident->delete();

        $this->actingAs($this->admin)->get(route('recycle-bin.index'))
            ->assertOk()
            ->assertSee('Villareal')
            ->assertSee($this->secretary->name)
            ->assertDontSee('Zamora');

        $this->actingAs($this->admin)->get(route('recycle-bin.index', ['search' => 'nobody']))
            ->assertOk()->assertDontSee('Villareal');
    }

    public function test_restoring_brings_the_record_back_and_logs_it(): void
    {
        $resident = $this->resident();
        $this->actingAs($this->secretary);
        $resident->delete();

        $this->actingAs($this->admin)
            ->patch(route('recycle-bin.restore', ['resident', $resident->id]))
            ->assertRedirect()->assertSessionHas('success');

        $fresh = Resident::find($resident->id);
        $this->assertNotNull($fresh);
        $this->assertNull($fresh->deleted_by);
        $this->assertTrue(ActivityLog::where('action', 'restored')->where('loggable_id', $resident->id)->exists());
    }

    public function test_restoring_a_record_that_is_not_archived_is_not_found(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->admin)->patch(route('recycle-bin.restore', ['resident', $resident->id]))->assertNotFound();
        $this->actingAs($this->admin)->patch(route('recycle-bin.restore', ['no-such-type', 1]))->assertNotFound();
    }

    public function test_an_appointment_and_its_document_are_archived_and_restored_together(): void
    {
        $appointment = $this->appointment();
        $resident    = $this->resident();
        $document    = Document::create([
            'doc_number' => 'BC-2026-0001', 'appointment_id' => $appointment->id, 'resident_id' => $resident->id,
            'document_type' => 'Barangay Clearance', 'purpose' => 'Employment', 'fee_paid' => 50, 'status' => 'Pending',
            'issued_by' => $this->admin->id,
        ]);

        $this->actingAs($this->admin)->delete(route('appointments.destroy', $appointment));
        $this->assertSoftDeleted($appointment);
        $this->assertSoftDeleted($document);

        $this->actingAs($this->admin)->patch(route('recycle-bin.restore', ['appointment', $appointment->id]));
        $this->assertNotSoftDeleted($appointment);
        $this->assertNotSoftDeleted($document);
    }

    public function test_a_household_with_living_members_cannot_be_archived(): void
    {
        $resident  = $this->resident();
        $household = $resident->household;
        $this->assertNotNull($household);

        $this->actingAs($this->secretary)->delete(route('households.destroy', $household));

        $this->assertNotSoftDeleted($household);
    }

    public function test_restoring_a_resident_also_restores_an_archived_household(): void
    {
        $resident  = $this->resident();
        $household = $resident->household;
        $resident->delete();
        $household->delete();

        $this->actingAs($this->admin)->patch(route('recycle-bin.restore', ['resident', $resident->id]));

        $this->assertNotSoftDeleted($resident);
        $this->assertNotSoftDeleted($household);
    }

    public function test_archived_numbers_are_never_reused(): void
    {
        $first = BlotterCase::factory()->create(['case_number' => BlotterCase::generateCaseNumber(), 'filed_by' => $this->admin->id]);
        $first->delete();

        $this->assertNotSame($first->case_number, BlotterCase::generateCaseNumber());

        $appointment = $this->appointment();
        $appointment->delete();
        $this->assertTrue(DocumentAppointment::withTrashed()->where('appointment_number', $appointment->appointment_number)->exists());
    }

    public function test_an_archived_user_cannot_sign_in(): void
    {
        $user = User::factory()->create(['role' => 'Secretary', 'password' => bcrypt('Secret@123')]);

        $this->actingAs($this->admin)->delete(route('users.destroy', $user));
        $this->assertSoftDeleted($user);

        auth()->logout();
        $this->post('/login', ['email' => $user->email, 'password' => 'Secret@123']);
        $this->assertGuest();
    }
}
