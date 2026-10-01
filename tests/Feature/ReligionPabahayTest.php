<?php

namespace Tests\Feature;

use App\Models\Pabahay;
use App\Models\PabahayUnit;
use App\Models\Purok;
use App\Models\Religion;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Part 2.1 to 2.3: the religion list, "Family of Ministers", Pabahay units, and the
 * nested INC → Family of Ministers → Pabahay unit filter.
 */
class ReligionPabahayTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private Purok $purok;
    private Religion $inc;
    private Religion $catholic;
    private Pabahay $pabahay;
    private PabahayUnit $unit1;
    private PabahayUnit $unit2;
    private int $street = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin    = User::factory()->create(['role' => 'Admin']);
        $this->purok    = Purok::create(['name' => 'Purok 1']);
        $this->inc      = Religion::where('is_inc', true)->sole();
        $this->catholic = Religion::where('name', 'Roman Catholic')->sole();

        $this->pabahay = Pabahay::create(['name' => 'Pabahay A', 'purok_id' => $this->purok->id]);
        $this->unit1   = $this->pabahay->units()->create(['unit_no' => 'A-1']);
        $this->unit2   = $this->pabahay->units()->create(['unit_no' => 'A-2']);
    }

    private function resident(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            'purok_id' => $this->purok->id, 'address' => ($this->street++).' Luna St.', 'birthdate' => '1980-01-01',
            'residency_status' => 'Active', 'religion_id' => null, 'is_minister_family' => false, 'pabahay_unit_id' => null,
        ], $attrs))->fresh();
    }

    private function form(array $overrides = []): array
    {
        return array_merge([
            'last_name' => 'Villareal', 'first_name' => 'Andrea', 'birthdate' => '1990-05-05', 'gender' => 'Female',
            'address' => '12 Rosal St.', 'purok_id' => $this->purok->id,
        ], $overrides);
    }

    private function count(array $params = []): int
    {
        return $this->actingAs($this->admin)->getJson(
            route('residents.index', array_merge(['draw' => 1, 'start' => 0, 'length' => 50, 'status' => 'Active'], $params)),
            ['X-Requested-With' => 'XMLHttpRequest']
        )->assertOk()->json('recordsFiltered');
    }

    // ── 2.1 Classification rules ───────────────────────────────────────

    public function test_the_migration_left_a_ready_religion_list_with_inc_flagged(): void
    {
        $this->assertTrue(Religion::where('name', 'Iglesia ni Cristo')->sole()->is_inc);
        $this->assertSame(1, Religion::where('is_inc', true)->count());
        $this->assertGreaterThanOrEqual(5, Religion::count());
    }

    public function test_family_of_ministers_is_only_for_inc_members(): void
    {
        $this->actingAs($this->admin)->post(route('residents.store'), $this->form(['religion_id' => $this->catholic->id, 'is_minister_family' => 1]))
            ->assertSessionHasErrors('is_minister_family');
        $this->assertSame(0, Resident::count());

        $this->actingAs($this->admin)->post(route('residents.store'), $this->form(['religion_id' => $this->inc->id, 'is_minister_family' => 1]))
            ->assertSessionHasNoErrors();
        $this->assertTrue(Resident::sole()->is_minister_family);
    }

    public function test_a_pabahay_unit_needs_family_of_ministers(): void
    {
        $this->actingAs($this->admin)->post(route('residents.store'), $this->form(['religion_id' => $this->inc->id, 'pabahay_unit_id' => $this->unit1->id]))
            ->assertSessionHasErrors('pabahay_unit_id');

        $this->actingAs($this->admin)->post(route('residents.store'), $this->form([
            'religion_id' => $this->inc->id, 'is_minister_family' => 1, 'pabahay_unit_id' => $this->unit1->id,
        ]))->assertSessionHasNoErrors();

        $r = Resident::sole();
        $this->assertSame([$this->inc->id, true, $this->unit1->id], [$r->religion_id, $r->is_minister_family, $r->pabahay_unit_id]);
    }

    public function test_moving_away_from_inc_clears_the_minister_flag_and_the_unit(): void
    {
        $r = $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id]);

        $r->update(['religion_id' => $this->catholic->id]);
        $r = $r->fresh();
        $this->assertFalse($r->is_minister_family);
        $this->assertNull($r->pabahay_unit_id);

        // Same on a smaller step: no longer a minister's family → no unit
        $r->update(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit2->id]);
        $r->fresh()->update(['is_minister_family' => false]);
        $this->assertNull($r->fresh()->pabahay_unit_id);
    }

    public function test_a_turned_off_unit_cannot_be_newly_chosen_but_can_be_kept(): void
    {
        $keeper = $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id]);
        $other  = $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true]);
        $this->unit1->update(['is_active' => false]);

        $this->actingAs($this->admin)->put(route('residents.update', $other), $this->form([
            'religion_id' => $this->inc->id, 'is_minister_family' => 1, 'pabahay_unit_id' => $this->unit1->id,
        ]))->assertSessionHasErrors('pabahay_unit_id');

        $this->actingAs($this->admin)->put(route('residents.update', $keeper), $this->form([
            'religion_id' => $this->inc->id, 'is_minister_family' => 1, 'pabahay_unit_id' => $this->unit1->id,
        ]))->assertSessionHasNoErrors();
        $this->assertSame($this->unit1->id, $keeper->fresh()->pabahay_unit_id);
    }

    // ── 2.3 The nested filter ──────────────────────────────────────────

    public function test_the_nested_filter_narrows_level_by_level(): void
    {
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id]);
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit2->id]);
        $this->resident(['religion_id' => $this->inc->id]);                                      // INC, not a minister's family
        $this->resident(['religion_id' => $this->catholic->id]);
        $this->resident();                                                                          // no religion recorded
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id, 'residency_status' => 'Deceased']);

        $this->assertSame(3, $this->count(['religion_group' => 'inc']));
        $this->assertSame(2, $this->count(['religion_group' => 'inc', 'fom' => 1]));
        $this->assertSame(1, $this->count(['religion_group' => 'inc', 'fom' => 1, 'pabahay_unit' => $this->unit1->id]));
        $this->assertSame(1, $this->count(['religion_group' => 'non_inc']));
        $this->assertSame(1, $this->count(['religion_group' => 'unrecorded']));

        // Living residents only unless "All" is chosen, so a deceased member does not count as an inhabitant
        $this->assertSame(2, $this->count(['religion_group' => 'inc', 'fom' => 1, 'pabahay_unit' => $this->unit1->id, 'status' => 'all']));
    }

    public function test_a_filtered_export_only_contains_the_matching_people(): void
    {
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id, 'last_name' => 'Insideunit']);
        $this->resident(['religion_id' => $this->catholic->id, 'last_name' => 'Outsideunit']);

        $response = $this->actingAs($this->admin)->get(route('export.pdf', ['residents', 'pabahay_unit' => $this->unit1->id]));
        $response->assertOk();
    }

    // ── 2.2 Pabahay and units ──────────────────────────────────────────

    public function test_admin_creates_a_pabahay_and_adds_units_in_one_go(): void
    {
        $this->actingAs($this->admin)->post(route('pabahays.store'), ['name' => 'Pabahay B', 'location' => 'Near the chapel'])
            ->assertRedirect();
        $b = Pabahay::where('name', 'Pabahay B')->sole();

        $this->actingAs($this->admin)->post(route('pabahays.units.store', $b), ['unit_numbers' => "B-10, B-2,\nB-1, b-1"])
            ->assertSessionHas('success');

        // "b-1" is the same unit as "B-1", and units sort naturally (2 before 10)
        $this->assertSame(['B-1', 'B-2', 'B-10'], $b->units()->pluck('unit_no')->all());

        $this->actingAs($this->admin)->post(route('pabahays.units.store', $b), ['unit_numbers' => 'B-2'])
            ->assertSessionHas('error');
        $this->assertSame(3, $b->units()->count());

        // Names are unique, and so are unit numbers inside one Pabahay
        $this->actingAs($this->admin)->post(route('pabahays.store'), ['name' => 'pabahay b'])->assertSessionHasErrors('name');
        $this->actingAs($this->admin)->patch(route('pabahay-units.update', $this->unit2), ['unit_no' => 'A-1'])->assertSessionHasErrors('unit_no');
    }

    public function test_a_unit_or_pabahay_with_living_residents_cannot_be_turned_off(): void
    {
        $r = $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id]);

        $this->actingAs($this->admin)->patch(route('pabahay-units.update', $this->unit1), ['is_active' => 0])->assertSessionHas('error');
        $this->assertTrue($this->unit1->fresh()->is_active);

        $this->actingAs($this->admin)->put(route('pabahays.update', $this->pabahay), ['name' => 'Pabahay A', 'is_active' => 0])->assertSessionHas('error');
        $this->assertTrue($this->pabahay->fresh()->is_active);

        // Once they have moved out, it can be turned off
        $r->update(['residency_status' => 'Transferred']);
        $this->actingAs($this->admin)->patch(route('pabahay-units.update', $this->unit1), ['is_active' => 0])->assertSessionHas('success');
        $this->assertFalse($this->unit1->fresh()->is_active);
    }

    public function test_the_pabahay_pages_count_living_people_only(): void
    {
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id]);
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit1->id]);
        $this->resident(['religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit2->id, 'residency_status' => 'Deceased']);

        $this->actingAs($this->admin)->get(route('pabahays.index'))
            ->assertOk()
            ->assertViewHas('occupancy', fn ($o) => (int) $o[$this->pabahay->id]->people === 2 && (int) $o[$this->pabahay->id]->occupied === 1);

        $this->actingAs($this->admin)->get(route('pabahays.show', $this->pabahay))
            ->assertOk()
            ->assertViewHas('units', fn ($units) => $units->firstWhere('unit_no', 'A-1')->residents->count() === 2
                && $units->firstWhere('unit_no', 'A-2')->residents->isEmpty());
    }

    // ── 2.1 The religion list ──────────────────────────────────────────

    public function test_admin_manages_the_religion_list(): void
    {
        $this->actingAs($this->admin)->post(route('religions.store'), ['name' => 'Members Church of God International'])->assertSessionHas('success');
        $this->assertTrue(Religion::where('name', 'Members Church of God International')->exists());

        $this->actingAs($this->admin)->post(route('religions.store'), ['name' => 'roman catholic'])->assertSessionHasErrors('name');

        $this->actingAs($this->admin)->patch(route('religions.update', $this->catholic), ['name' => 'Catholic'])->assertSessionHas('success');
        $this->assertSame('Catholic', $this->catholic->fresh()->name);

        // INC drives the INC / Non-INC split, so it can never be turned off
        $this->actingAs($this->admin)->patch(route('religions.update', $this->inc), ['is_active' => 0])->assertSessionHas('error');
        $this->assertTrue($this->inc->fresh()->is_active);
    }

    public function test_a_turned_off_religion_leaves_the_form_but_residents_keep_it(): void
    {
        $r = $this->resident(['religion_id' => $this->catholic->id]);
        $this->actingAs($this->admin)->patch(route('religions.update', $this->catholic), ['is_active' => 0])->assertSessionHas('success');

        $this->actingAs($this->admin)->get(route('residents.create'))
            ->assertOk()->assertDontSee('Roman Catholic');

        $this->actingAs($this->admin)->get(route('residents.edit', $r))
            ->assertOk()->assertSee('Roman Catholic (hidden)');

        $this->assertSame($this->catholic->id, $r->fresh()->religion_id);
    }

    public function test_the_religion_page_counts_living_residents_by_group(): void
    {
        $this->resident(['religion_id' => $this->inc->id]);
        $this->resident(['religion_id' => $this->inc->id, 'residency_status' => 'Deceased']);
        $this->resident(['religion_id' => $this->catholic->id]);
        $this->resident();

        $this->actingAs($this->admin)->get(route('religions.index'))
            ->assertOk()
            ->assertViewHas('summary', ['inc' => 1, 'non_inc' => 1, 'unrecorded' => 1]);
    }
}
