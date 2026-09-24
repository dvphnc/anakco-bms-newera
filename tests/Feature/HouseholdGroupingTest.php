<?php

namespace Tests\Feature;

use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Task 1.2 — automatic household grouping.
 */
class HouseholdGroupingTest extends TestCase
{
    use RefreshDatabase;

    private User $secretary;
    private Purok $purok;
    private Purok $otherPurok;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretary  = User::factory()->create(['role' => 'Secretary']);
        $this->purok      = Purok::create(['name' => 'Purok 1']);
        $this->otherPurok = Purok::create(['name' => 'Purok 2']);
    }

    private function resident(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            // Fixed name: the factory's random names include "Maria", which the form
            // tests below register and then look up — that made those tests flaky.
            'first_name'       => 'Existing',
            'purok_id'         => $this->purok->id,
            'household_id'     => null,
            'address'          => '12 Rosal St., Barangay New Era, Quezon City',
            'birthdate'        => '1980-01-15',
            'residency_status' => 'Active',
            'is_voter'         => false,
            'relationship_to_head' => null,
        ], $attrs));
    }

    private function formData(array $overrides = []): array
    {
        return array_merge([
            'last_name' => 'Santos', 'first_name' => 'Maria', 'birthdate' => '1990-05-05',
            'gender' => 'Female', 'address' => '12 Rosal St.', 'purok_id' => $this->purok->id,
        ], $overrides);
    }

    public function test_residents_at_the_same_address_are_grouped_into_one_household(): void
    {
        $parent = $this->resident(['birthdate' => '1960-03-01']);
        $child  = $this->resident(['address' => '#12 ROSAL STREET', 'birthdate' => '1995-07-07']);

        $this->assertNotNull($parent->fresh()->household_id);
        $this->assertSame($parent->fresh()->household_id, $child->fresh()->household_id);

        $household = Household::sole();
        $this->assertSame('HH-'.now()->format('Y').'-0001', $household->household_number);
        $this->assertSame(2, $household->family_size);
        $this->assertSame($parent->id, $household->head_resident_id);      // eldest living member
        $this->assertSame($parent->full_name, $household->household_head);
    }

    public function test_same_address_in_a_different_purok_is_a_different_household(): void
    {
        $a = $this->resident();
        $b = $this->resident(['purok_id' => $this->otherPurok->id]);

        $this->assertNotSame($a->fresh()->household_id, $b->fresh()->household_id);
        $this->assertSame(2, Household::count());
    }

    public function test_registering_through_the_form_groups_automatically(): void
    {
        $existing = $this->resident();

        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->formData(['household_mode' => 'auto']))
            ->assertSessionHasNoErrors();

        $new = Resident::where('first_name', 'Maria')->sole();
        $this->assertSame($existing->fresh()->household_id, $new->household_id);
        $this->assertSame('auto', $new->household_assignment);
    }

    public function test_a_manual_household_choice_is_never_overwritten(): void
    {
        $other = $this->resident(['address' => '99 Camia St.']);
        $chosen = $other->fresh()->household_id;

        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->formData(['household_mode' => 'manual', 'household_id' => $chosen]))
            ->assertSessionHasNoErrors();

        $boarder = Resident::where('first_name', 'Maria')->sole();
        $this->assertSame($chosen, $boarder->household_id);
        $this->assertSame('manual', $boarder->household_assignment);

        // Editing their address later still leaves the manual choice alone
        $this->actingAs($this->secretary)
            ->put(route('residents.update', $boarder), $this->formData([
                'household_mode' => 'manual', 'household_id' => $chosen, 'address' => '1 Luna St.',
            ]))
            ->assertSessionHasNoErrors();
        $this->assertSame($chosen, $boarder->fresh()->household_id);
    }

    public function test_manual_mode_requires_a_household(): void
    {
        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->formData(['household_mode' => 'manual']))
            ->assertSessionHasErrors('household_id');
    }

    public function test_switching_back_to_automatic_regroups_by_address(): void
    {
        $atRosal = $this->resident();
        $boarder = $this->resident(['address' => '99 Camia St.', 'household_assignment' => 'manual', 'household_id' => $atRosal->fresh()->household_id]);
        $this->assertSame($atRosal->fresh()->household_id, $boarder->fresh()->household_id);

        $this->actingAs($this->secretary)
            ->put(route('residents.update', $boarder), $this->formData([
                'first_name' => $boarder->first_name, 'last_name' => $boarder->last_name,
                'address' => '99 Camia St.', 'household_mode' => 'auto',
            ]))
            ->assertSessionHasNoErrors();

        $this->assertNotSame($atRosal->fresh()->household_id, $boarder->fresh()->household_id);
        $this->assertSame('auto', $boarder->fresh()->household_assignment);
    }

    public function test_moving_out_keeps_history_but_updates_size_and_head(): void
    {
        $head  = $this->resident(['birthdate' => '1950-01-01']);
        $other = $this->resident(['birthdate' => '1985-01-01']);
        $household = Household::sole();
        $this->assertSame($head->id, $household->fresh()->head_resident_id);

        $this->actingAs($this->secretary)->patch(route('residents.status.update', $head), [
            'to_status' => 'Transferred', 'effective_date' => now()->toDateString(), 'moved_to' => 'Cavite',
        ])->assertSessionHasNoErrors();

        $household->refresh();
        $this->assertSame($household->id, $head->fresh()->household_id);   // still linked, for history
        $this->assertSame(1, $household->family_size);
        $this->assertSame($other->id, $household->head_resident_id);
    }

    public function test_marking_someone_as_head_makes_them_the_only_head(): void
    {
        $eldest = $this->resident(['birthdate' => '1950-01-01', 'relationship_to_head' => 'Head']);
        $younger = $this->resident(['birthdate' => '1985-01-01']);
        $this->assertSame($eldest->id, Household::sole()->head_resident_id);

        $younger->update(['relationship_to_head' => 'Head']);

        $this->assertSame($younger->id, Household::sole()->head_resident_id);
        $this->assertNull($eldest->fresh()->relationship_to_head);
    }

    public function test_make_head_button_only_accepts_living_members(): void
    {
        $a = $this->resident(['birthdate' => '1950-01-01']);
        $b = $this->resident(['birthdate' => '1985-01-01']);
        $outsider = $this->resident(['address' => '5 Luna St.']);
        $household = Household::where('address_key', '12 rosal street')->sole();

        $this->actingAs($this->secretary)
            ->patch(route('households.head', $household), ['resident_id' => $b->id])
            ->assertSessionHasNoErrors();
        $this->assertSame($b->id, $household->fresh()->head_resident_id);

        $this->actingAs($this->secretary)
            ->patch(route('households.head', $household), ['resident_id' => $outsider->id])
            ->assertSessionHasErrors('resident_id');
    }

    public function test_voter_household_flag_follows_living_members(): void
    {
        $member = $this->resident();
        $this->assertFalse(Household::sole()->is_voter_household);

        $member->update(['is_voter' => true]);
        $this->assertTrue(Household::sole()->is_voter_household);
    }

    public function test_editing_a_household_address_does_not_split_its_members(): void
    {
        $member = $this->resident();
        $household = Household::sole();
        $household->update(['address' => '12-A Rosal St.']);   // staff correct the household's address

        $member->update(['is_voter' => true]);                 // unrelated edit to a member

        $this->assertSame($household->id, $member->fresh()->household_id);
        $this->assertSame(1, Household::count());
    }

    public function test_live_match_endpoint(): void
    {
        $member = $this->resident();
        $number = Household::sole()->household_number;

        $match = fn (array $params) => $this->actingAs($this->secretary)
            ->getJson(route('households.match', $params))->assertOk()->json();

        $hit = $match(['address' => '12 ROSAL STREET, QC', 'purok_id' => $this->purok->id]);
        $this->assertSame($number, $hit['household']['number']);
        $this->assertSame(1, $hit['household']['members']);
        $this->assertFalse($hit['household']['is_current']);

        $self = $match(['address' => '12 Rosal St', 'purok_id' => $this->purok->id, 'resident_id' => $member->id]);
        $this->assertTrue($self['household']['is_current']);

        $this->assertNull($match(['address' => '77 Luna St', 'purok_id' => $this->purok->id])['household']);
        $this->assertNull($match(['address' => '12 Rosal St', 'purok_id' => $this->otherPurok->id])['household']);
    }

    public function test_household_numbers_do_not_collide_after_a_deletion(): void
    {
        $this->resident(['address' => '1 Luna St.']);
        $this->resident(['address' => '2 Luna St.']);
        $this->resident(['address' => '3 Luna St.']);
        Household::where('household_number', 'like', '%-0002')->first()->delete();

        $this->resident(['address' => '4 Luna St.']);   // old code would reuse "-0003" and fail

        $this->assertSame(3, Household::count());
        $this->assertTrue(Household::where('household_number', 'like', '%-0004')->exists());
    }

    public function test_adding_a_household_at_an_existing_address_is_rejected(): void
    {
        $this->resident();

        $this->actingAs($this->secretary)
            ->post(route('households.store'), ['purok_id' => $this->purok->id, 'address' => '12 Rosal Street'])
            ->assertSessionHasErrors('address');

        $this->assertSame(1, Household::count());
    }

    public function test_profile_household_tab_lists_members_and_head(): void
    {
        $head = $this->resident(['first_name' => 'Pedro', 'birthdate' => '1950-01-01']);
        $kid  = $this->resident(['first_name' => 'Ana', 'birthdate' => '2000-01-01', 'relationship_to_head' => 'Child']);

        $this->actingAs($this->secretary)
            ->get(route('residents.show', $kid))
            ->assertOk()
            ->assertSee('Grouped by address')
            ->assertSee($head->full_name)
            ->assertSee('Child');
    }
}
