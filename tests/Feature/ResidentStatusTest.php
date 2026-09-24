<?php

namespace Tests\Feature;

use App\Models\Purok;
use App\Models\Resident;
use App\Models\ResidentStatusLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Task 1.4 — Life Event Statuses (Alive / Deceased / Moved Out).
 */
class ResidentStatusTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $secretary;
    private Purok $purok;

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
            'purok_id'         => $this->purok->id,
            'household_id'     => null,
            'birthdate'        => '1970-01-15',
            'residency_status' => 'Active',
        ], $attrs));
    }

    public function test_marking_a_resident_as_moved_out_records_date_destination_and_history(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)
            ->patch(route('residents.status.update', $resident), [
                'to_status'      => 'Transferred',
                'effective_date' => '2026-03-02',
                'moved_to'       => 'Caloocan City',
                'remarks'        => 'Moved with family',
            ])
            ->assertRedirect(route('residents.show', $resident))
            ->assertSessionHasNoErrors();

        $resident->refresh();
        $this->assertSame('Transferred', $resident->residency_status);
        $this->assertSame('Moved Out', $resident->residency_label);
        $this->assertSame('2026-03-02', $resident->status_effective_date->toDateString());

        $log = ResidentStatusLog::sole();
        $this->assertSame('Active', $log->from_status);
        $this->assertSame('Transferred', $log->to_status);
        $this->assertSame('Caloocan City', $log->moved_to);
        $this->assertSame($this->secretary->id, $log->changed_by);
    }

    public function test_destination_is_required_when_moving_out(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)
            ->patch(route('residents.status.update', $resident), [
                'to_status'      => 'Transferred',
                'effective_date' => '2026-03-02',
            ])
            ->assertSessionHasErrors('moved_to');

        $this->assertSame('Active', $resident->fresh()->residency_status);
        $this->assertSame(0, ResidentStatusLog::count());
    }

    public function test_effective_date_cannot_be_in_the_future_or_before_birth(): void
    {
        $resident = $this->resident();

        foreach ([now()->addDay()->toDateString(), '1960-01-01'] as $badDate) {
            $this->actingAs($this->secretary)
                ->patch(route('residents.status.update', $resident), [
                    'to_status'      => 'Deceased',
                    'effective_date' => $badDate,
                ])
                ->assertSessionHasErrors('effective_date');
        }

        $this->assertSame('Active', $resident->fresh()->residency_status);
    }

    public function test_setting_the_same_status_again_is_rejected(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)
            ->patch(route('residents.status.update', $resident), [
                'to_status'      => 'Active',
                'effective_date' => '2026-03-02',
            ])
            ->assertSessionHasErrors('to_status');

        $this->assertSame(0, ResidentStatusLog::count());
    }

    public function test_only_an_admin_can_change_a_deceased_resident(): void
    {
        $resident = $this->resident(['residency_status' => 'Deceased']);
        $payload  = ['to_status' => 'Active', 'effective_date' => '2026-03-02', 'remarks' => 'Recorded in error'];

        $this->actingAs($this->secretary)
            ->patch(route('residents.status.update', $resident), $payload)
            ->assertForbidden();
        $this->assertSame('Deceased', $resident->fresh()->residency_status);

        $this->actingAs($this->admin)
            ->patch(route('residents.status.update', $resident), $payload)
            ->assertSessionHasNoErrors();
        $this->assertSame('Active', $resident->fresh()->residency_status);
    }

    public function test_destination_is_not_kept_for_statuses_other_than_moved_out(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)
            ->patch(route('residents.status.update', $resident), [
                'to_status'      => 'Deceased',
                'effective_date' => '2026-03-02',
                'moved_to'       => 'should be ignored',
            ]);

        $this->assertNull(ResidentStatusLog::sole()->moved_to);
    }

    public function test_new_residents_always_start_as_alive(): void
    {
        $this->actingAs($this->secretary)->post(route('residents.store'), [
            'last_name'        => 'Santos',
            'first_name'       => 'Maria',
            'birthdate'        => '1980-05-05',
            'gender'           => 'Female',
            'address'          => '12 Rosal St.',
            'purok_id'         => $this->purok->id,
            'residency_status' => 'Deceased',   // must be ignored
        ])->assertSessionHasNoErrors();

        $this->assertSame('Active', Resident::sole()->residency_status);
    }

    public function test_the_edit_form_cannot_change_status(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)->put(route('residents.update', $resident), [
            'last_name'        => $resident->last_name,
            'first_name'       => $resident->first_name,
            'birthdate'        => '1970-01-15',
            'gender'           => $resident->gender,
            'address'          => $resident->address,
            'purok_id'         => $this->purok->id,
            'residency_status' => 'Deceased',   // must be ignored
        ])->assertSessionHasNoErrors();

        $this->assertSame('Active', $resident->fresh()->residency_status);
        $this->assertSame(0, ResidentStatusLog::count());
    }

    public function test_old_one_click_toggle_route_is_gone(): void
    {
        $resident = $this->resident();

        $this->actingAs($this->secretary)
            ->patch("/residents/{$resident->id}/toggle-status")
            ->assertNotFound();
    }

    public function test_residents_list_filters_by_status_and_all_returns_everyone(): void
    {
        $this->resident(['residency_status' => 'Active']);
        $this->resident(['residency_status' => 'Deceased']);
        $this->resident(['residency_status' => 'Transferred']);

        $ajax = fn (string $status) => $this->actingAs($this->secretary)
            ->getJson(route('residents.index', ['status' => $status, 'draw' => 1, 'start' => 0, 'length' => 50]), ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->json('recordsFiltered');

        $this->assertSame(1, $ajax('Active'));
        $this->assertSame(1, $ajax('Deceased'));
        $this->assertSame(3, $ajax('all'));
    }

    public function test_profile_shows_status_label_and_history(): void
    {
        $resident = $this->resident();
        $this->actingAs($this->secretary)->patch(route('residents.status.update', $resident), [
            'to_status' => 'Transferred', 'effective_date' => '2026-03-02', 'moved_to' => 'Caloocan City',
        ]);

        $this->actingAs($this->secretary)
            ->get(route('residents.show', $resident))
            ->assertOk()
            ->assertSee('Moved Out')
            ->assertSee('Caloocan City')
            ->assertSee('03/02/2026');
    }
}
