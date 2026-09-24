<?php

namespace Tests\Feature;

use App\Exceptions\DuplicateClaimException;
use App\Models\AssistanceProgram;
use App\Models\Document;
use App\Models\MedicineInventory;
use App\Models\MedicineStockLog;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\ResidentTransaction;
use App\Models\User;
use App\Services\TransactionService;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Task 1.1 — transaction history & double-claim prevention.
 */
class ResidentTransactionTest extends TestCase
{
    use RefreshDatabase;

    private User $secretary;
    private Purok $purok;
    private Resident $maria;
    private Resident $juan;       // same household as Maria
    private Resident $neighbor;   // different household

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretary = User::factory()->create(['role' => 'Secretary']);
        $this->purok     = Purok::create(['name' => 'Purok 1']);
        $this->maria     = $this->resident(['first_name' => 'Maria', 'birthdate' => '1970-01-01']);
        $this->juan      = $this->resident(['first_name' => 'Juan', 'birthdate' => '1995-01-01']);
        $this->neighbor  = $this->resident(['first_name' => 'Pedro', 'address' => '14 Rosal St.']);
    }

    private function resident(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            'purok_id' => $this->purok->id, 'last_name' => 'Santos', 'address' => '12 Rosal St.',
            'birthdate' => '1980-01-01', 'residency_status' => 'Active',
        ], $attrs))->fresh();
    }

    private function program(array $attrs = []): AssistanceProgram
    {
        return AssistanceProgram::create(array_merge([
            'name' => 'Relief Pack — Typhoon Kristine', 'type' => 'relief', 'claim_scope' => 'household',
            'max_claims' => 1, 'starts_on' => now()->subDay(), 'is_active' => true,
        ], $attrs));
    }

    private function claim(Resident $r, AssistanceProgram $p)
    {
        return $this->actingAs($this->secretary)
            ->postJson(route('residents.transactions.store', $r), ['assistance_program_id' => $p->id]);
    }

    public function test_a_household_can_claim_a_per_household_program_only_once(): void
    {
        $relief = $this->program();
        $this->assertSame($this->maria->household_id, $this->juan->household_id);

        $this->claim($this->maria, $relief)->assertOk()->assertJsonPath('reference_no', fn ($ref) => str_starts_with($ref, 'TXN-'));

        $this->claim($this->juan, $relief)
            ->assertStatus(422)
            ->assertJsonPath('errors.assistance_program_id.0', fn ($msg) => str_contains($msg, 'Already claimed by Maria')
                && str_contains($msg, 'same household'));

        $this->claim($this->neighbor, $relief)->assertOk();   // another household is unaffected
        $this->assertSame(2, ResidentTransaction::count());
    }

    public function test_per_resident_programs_count_each_person_separately(): void
    {
        $aid = $this->program(['name' => 'Senior Aid', 'type' => 'financial', 'claim_scope' => 'resident']);

        $this->claim($this->maria, $aid)->assertOk();
        $this->claim($this->juan, $aid)->assertOk();
        $this->claim($this->maria, $aid)->assertStatus(422);
    }

    public function test_a_program_can_allow_several_claims(): void
    {
        $meds = $this->program(['name' => 'Maintenance Meds', 'type' => 'medicine', 'max_claims' => 2]);

        $this->claim($this->maria, $meds)->assertOk();
        $this->claim($this->juan, $meds)->assertOk();
        $this->claim($this->maria, $meds)->assertStatus(422);
    }

    public function test_voiding_a_claim_frees_it_and_keeps_it_on_record(): void
    {
        $relief = $this->program();
        $this->claim($this->maria, $relief)->assertOk();
        $tx = ResidentTransaction::sole();

        $this->actingAs($this->secretary)
            ->patchJson(route('transactions.void', $tx), ['reason' => 'x'])
            ->assertStatus(422);                                   // reason too short

        $this->actingAs($this->secretary)
            ->patchJson(route('transactions.void', $tx), ['reason' => 'Recorded for the wrong resident'])
            ->assertOk();

        $tx->refresh();
        $this->assertNotNull($tx->voided_at);
        $this->assertNull($tx->claim_lock);
        $this->assertSame($this->secretary->id, $tx->voided_by);

        $this->claim($this->juan, $relief)->assertOk();            // household can claim again
        $this->assertSame(2, ResidentTransaction::count());        // nothing deleted
    }

    public function test_only_living_residents_can_receive_anything(): void
    {
        $relief = $this->program();
        $this->maria->residency_status = 'Deceased';
        $this->maria->save();

        $this->claim($this->maria, $relief)->assertStatus(422)->assertJsonValidationErrors('resident');
    }

    public function test_closed_or_inactive_programs_are_rejected(): void
    {
        $ended    = $this->program(['name' => 'Old', 'starts_on' => now()->subMonth(), 'ends_on' => now()->subDay()]);
        $inactive = $this->program(['name' => 'Paused', 'is_active' => false]);

        $this->claim($this->maria, $ended)->assertStatus(422)->assertJsonValidationErrors('assistance_program_id');
        $this->claim($this->maria, $inactive)->assertStatus(422)->assertJsonValidationErrors('assistance_program_id');
    }

    public function test_the_database_itself_rejects_a_second_identical_claim(): void
    {
        // Simulates two PCs passing the check at the same instant: the unique
        // claim_lock column is the final guard.
        $relief = $this->program();
        app(TransactionService::class)->record($this->maria, ['assistance_program_id' => $relief->id], $this->secretary);
        $lock = ResidentTransaction::sole()->claim_lock;

        $this->expectException(UniqueConstraintViolationException::class);
        ResidentTransaction::create([
            'resident_id' => $this->juan->id, 'household_id' => $this->juan->household_id,
            'assistance_program_id' => $relief->id, 'type' => 'relief', 'description' => 'race',
            'transacted_at' => now(), 'claim_lock' => $lock,
        ]);
    }

    public function test_service_throws_a_duplicate_claim_exception_naming_the_prior_claim(): void
    {
        $relief = $this->program();
        $service = app(TransactionService::class);
        $first = $service->record($this->maria, ['assistance_program_id' => $relief->id], $this->secretary);

        try {
            $service->record($this->juan, ['assistance_program_id' => $relief->id], $this->secretary);
            $this->fail('Expected a DuplicateClaimException');
        } catch (DuplicateClaimException $e) {
            $this->assertSame($first->id, $e->priorClaim->id);
        }
    }

    public function test_eligibility_check_explains_why_not(): void
    {
        $relief = $this->program();
        $check = fn (Resident $r) => $this->actingAs($this->secretary)
            ->getJson(route('residents.eligibility', [$r, $relief]))->assertOk()->json();

        $this->assertTrue($check($this->juan)['eligible']);

        $this->claim($this->maria, $relief)->assertOk();

        $result = $check($this->juan);
        $this->assertFalse($result['eligible']);
        $this->assertSame('household', $result['scope']);
        $this->assertStringContainsString('Maria', $result['reason']);
        $this->assertFalse($result['prior']['same_person']);
    }

    public function test_one_off_items_need_a_type_and_description(): void
    {
        $this->actingAs($this->secretary)
            ->postJson(route('residents.transactions.store', $this->maria), [])
            ->assertStatus(422)
            ->assertJsonValidationErrors(['type', 'description']);

        $this->actingAs($this->secretary)
            ->postJson(route('residents.transactions.store', $this->maria), [
                'type' => 'relief', 'description' => '5 kg rice', 'quantity' => 5, 'unit' => 'kg',
            ])
            ->assertOk();

        $tx = ResidentTransaction::sole();
        $this->assertNull($tx->assistance_program_id);
        $this->assertNull($tx->claim_lock);
        $this->assertSame($this->maria->household_id, $tx->household_id);
    }

    public function test_released_documents_are_recorded_once(): void
    {
        $doc = Document::create([
            'doc_number' => 'BC-2026-0001', 'resident_id' => $this->maria->id, 'document_type' => 'Barangay Clearance',
            'purpose' => 'Employment', 'fee_paid' => 50, 'status' => 'Pending', 'issued_by' => $this->secretary->id,
        ]);
        $this->assertSame(0, ResidentTransaction::count());

        $doc->update(['status' => 'Released', 'released_at' => now()]);
        $doc->update(['purpose' => 'Employment (abroad)']);        // unrelated edit
        $doc->update(['status' => 'Processing']);
        $doc->update(['status' => 'Released']);                    // released again

        $tx = ResidentTransaction::sole();
        $this->assertSame('document', $tx->type);
        $this->assertStringContainsString('Barangay Clearance', $tx->description);
        $this->assertSame('50.00', $tx->amount);
    }

    public function test_medicine_given_to_a_resident_is_recorded(): void
    {
        $med = MedicineInventory::create(['medicine_name' => 'Paracetamol', 'unit' => 'tablet', 'current_stock' => 100]);

        MedicineStockLog::create([
            'medicine_id' => $med->id, 'adjustment_type' => 'out', 'quantity' => 10, 'stock_before' => 100,
            'stock_after' => 90, 'beneficiary_resident_id' => $this->maria->id, 'purpose' => 'Fever',
        ]);
        MedicineStockLog::create([   // restock: not a transaction
            'medicine_id' => $med->id, 'adjustment_type' => 'in', 'quantity' => 50, 'stock_before' => 90, 'stock_after' => 140,
        ]);

        $tx = ResidentTransaction::sole();
        $this->assertSame('medicine', $tx->type);
        $this->assertSame('Paracetamol — Fever', $tx->description);
        $this->assertSame('10.00', $tx->quantity);
        $this->assertSame('tablet', $tx->unit);
    }

    public function test_history_list_filters_by_type_and_marks_voided_entries(): void
    {
        $service = app(TransactionService::class);
        $service->record($this->maria, ['type' => 'relief', 'description' => 'Rice'], $this->secretary);
        $wrong = $service->record($this->maria, ['type' => 'financial', 'description' => 'Cash aid', 'amount' => 500], $this->secretary);
        $service->void($wrong, $this->secretary, 'Duplicate entry');

        $list = fn (array $p = []) => $this->actingAs($this->secretary)
            ->getJson(route('residents.transactions.index', array_merge([$this->maria, 'draw' => 1, 'start' => 0, 'length' => 25], $p)),
                ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->json();

        $this->assertSame(2, $list()['recordsFiltered']);
        $financial = $list(['type' => 'financial']);
        $this->assertSame(1, $financial['recordsFiltered']);
        $this->assertStringContainsString('Voided', $financial['data'][0]['item_col']);
        $this->assertSame('', $financial['data'][0]['actions']);   // can't void twice
    }

    public function test_programs_can_be_created_and_archived(): void
    {
        $this->actingAs($this->secretary)
            ->post(route('programs.store'), [
                'name' => 'Rice Subsidy', 'type' => 'relief', 'claim_scope' => 'household', 'max_claims' => 2, 'is_active' => '1',
            ])
            ->assertRedirect(route('programs.index'));

        $program = AssistanceProgram::sole();
        $this->assertSame(2, $program->max_claims);
        $this->claim($this->maria, $program)->assertOk();

        $this->actingAs($this->secretary)->delete(route('programs.destroy', $program))->assertRedirect();
        $this->assertSoftDeleted($program);
        $this->assertSame(1, ResidentTransaction::count());        // history kept

        $this->actingAs($this->secretary)->get(route('programs.index'))->assertOk()->assertDontSee('Rice Subsidy');
    }

    public function test_profile_shows_transactions_tab_and_household_claims(): void
    {
        $relief = $this->program();
        $this->claim($this->maria, $relief)->assertOk();

        $this->actingAs($this->secretary)
            ->get(route('residents.show', $this->juan))
            ->assertOk()
            ->assertSee('Record Transaction')
            ->assertSee('Claimed by others in this household')
            ->assertSee('Relief Pack — Typhoon Kristine');
    }
}
