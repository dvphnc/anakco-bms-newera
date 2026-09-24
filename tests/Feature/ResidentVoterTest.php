<?php

namespace Tests\Feature;

use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Task 1.3 — Voter & Residency: registered voters currently living in the barangay.
 */
class ResidentVoterTest extends TestCase
{
    use RefreshDatabase;

    private User $secretary;
    private Purok $purok;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretary = User::factory()->create(['role' => 'Secretary']);
        $this->purok     = Purok::create(['name' => 'Purok 1']);
    }

    private function resident(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            'purok_id'         => $this->purok->id,
            'household_id'     => null,
            'birthdate'        => '1980-01-15',
            'residency_status' => 'Active',
            'is_voter'         => false,
            'is_senior'        => false,
        ], $attrs));
    }

    private function formData(array $overrides = []): array
    {
        return array_merge([
            'last_name'  => 'Santos',
            'first_name' => 'Maria',
            'birthdate'  => '1980-05-05',
            'gender'     => 'Female',
            'address'    => '12 Rosal St.',
            'purok_id'   => $this->purok->id,
        ], $overrides);
    }

    public function test_resident_voters_are_only_voters_who_still_live_here(): void
    {
        $living   = $this->resident(['is_voter' => true]);
        $movedOut = $this->resident(['is_voter' => true, 'residency_status' => 'Transferred']);
        $this->resident(['is_voter' => true, 'residency_status' => 'Deceased']);
        $this->resident(['is_voter' => false]);

        $this->assertSame([$living->id], Resident::residentVoters()->pluck('id')->all());
        $this->assertSame([$movedOut->id], Resident::nonResidentVoters()->pluck('id')->all());
    }

    public function test_dashboard_counts_only_living_voters_and_living_seniors(): void
    {
        $this->resident(['is_voter' => true]);
        $this->resident(['is_voter' => true, 'residency_status' => 'Transferred']);
        $this->resident(['is_voter' => true, 'residency_status' => 'Deceased', 'is_senior' => true, 'birthdate' => '1940-01-01']);
        $this->resident(['is_senior' => true, 'birthdate' => '1950-01-01']);

        $this->actingAs($this->secretary)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('totalVoters', 1)
            ->assertViewHas('totalSeniors', 1);
    }

    public function test_residents_under_18_cannot_be_marked_as_voters(): void
    {
        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->formData([
                'birthdate' => now()->subYears(17)->toDateString(),
                'is_voter'  => '1',
            ]))
            ->assertSessionHasErrors('is_voter');

        $this->assertSame(0, Resident::count());
    }

    public function test_an_18_year_old_can_be_marked_as_voter_with_precinct(): void
    {
        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->formData([
                'birthdate'    => now()->subYears(18)->toDateString(),
                'is_voter'     => '1',
                'precinct_no'  => '0123A',
                'voters_id_no' => '1234-5678',
            ]))
            ->assertSessionHasNoErrors();

        $r = Resident::sole();
        $this->assertTrue($r->is_voter);
        $this->assertSame('0123A', $r->precinct_no);
        $this->assertSame('1234-5678', $r->voters_id_no);
    }

    public function test_unticking_voter_clears_precinct_and_voter_id(): void
    {
        $resident = $this->resident(['is_voter' => true, 'precinct_no' => '0123A', 'voters_id_no' => '1234']);

        $this->actingAs($this->secretary)
            ->put(route('residents.update', $resident), $this->formData([
                'birthdate'   => '1980-01-15',
                'precinct_no' => '0123A',   // still sent by the hidden fields
                // is_voter not sent = unticked
            ]))
            ->assertSessionHasNoErrors();

        $resident->refresh();
        $this->assertFalse($resident->is_voter);
        $this->assertNull($resident->precinct_no);
        $this->assertNull($resident->voters_id_no);
    }

    public function test_residents_list_can_show_moved_out_voters_for_cleanup(): void
    {
        $this->resident(['is_voter' => true]);
        $this->resident(['is_voter' => true, 'residency_status' => 'Transferred']);
        $this->resident(['is_voter' => false, 'residency_status' => 'Transferred']);

        $count = fn (string $status) => $this->actingAs($this->secretary)
            ->getJson(route('residents.index', ['tags' => ['voter'], 'status' => $status, 'draw' => 1, 'start' => 0, 'length' => 50]),
                ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()
            ->json('recordsFiltered');

        $this->assertSame(1, $count('Active'));        // residing voters
        $this->assertSame(1, $count('Transferred'));   // moved out, still registered
    }

    public function test_profile_flags_a_moved_out_voter(): void
    {
        $resident = $this->resident(['is_voter' => true, 'residency_status' => 'Transferred', 'precinct_no' => '0456B']);

        $this->actingAs($this->secretary)
            ->get(route('residents.show', $resident))
            ->assertOk()
            ->assertSee('Voter · not residing')
            ->assertSee('0456B');
    }
}
