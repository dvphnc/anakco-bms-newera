<?php

namespace Tests\Feature;

use App\Models\ActivityLog;
use App\Models\Pabahay;
use App\Models\PabahayUnit;
use App\Models\Purok;
use App\Models\Religion;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Part 2.0: religion and minister data is Admin-only. Every place it could leak is
 * checked here: forms, profile, table JSON, filters, exports, the activity log, and
 * hand-crafted requests.
 */
class ReligionAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $secretary;
    private Purok $purok;
    private Religion $inc;
    private Religion $catholic;
    private PabahayUnit $unit;
    private Resident $minister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin     = User::factory()->create(['role' => 'Admin']);
        $this->secretary = User::factory()->create(['role' => 'Secretary']);
        $this->purok     = Purok::create(['name' => 'Purok 1']);
        $this->inc       = Religion::where('is_inc', true)->sole();
        $this->catholic  = Religion::where('name', 'Roman Catholic')->sole();

        $pabahay = Pabahay::create(['name' => 'Pabahay A']);
        $this->unit = $pabahay->units()->create(['unit_no' => 'A-1']);

        $this->minister = Resident::factory()->create([
            'purok_id' => $this->purok->id, 'first_name' => 'Pedro', 'last_name' => 'Ministro', 'birthdate' => '1970-01-01',
            'residency_status' => 'Active', 'religion_id' => $this->inc->id, 'is_minister_family' => true, 'pabahay_unit_id' => $this->unit->id,
        ]);
    }

    private function json(User $user, array $params = [])
    {
        return $this->actingAs($user)->getJson(
            route('residents.index', array_merge(['draw' => 1, 'start' => 0, 'length' => 25, 'status' => 'Active'], $params)),
            ['X-Requested-With' => 'XMLHttpRequest']
        );
    }

    public function test_secretary_does_not_see_religion_on_the_forms_or_profile(): void
    {
        $this->actingAs($this->secretary)->get(route('residents.create'))
            ->assertOk()->assertDontSee('name="religion_id"', false)->assertDontSee('Family of Ministers');

        $this->actingAs($this->secretary)->get(route('residents.edit', $this->minister))
            ->assertOk()->assertDontSee('name="religion_id"', false)->assertDontSee('Pabahay');

        $this->actingAs($this->secretary)->get(route('residents.show', $this->minister))
            ->assertOk()->assertDontSee('Iglesia ni Cristo')->assertDontSee('Family of Ministers')->assertDontSee('Pabahay A');
    }

    public function test_admin_sees_religion_on_the_forms_and_profile(): void
    {
        $this->actingAs($this->admin)->get(route('residents.edit', $this->minister))
            ->assertOk()->assertSee('name="religion_id"', false)->assertSee('name="pabahay_unit_id"', false);

        $this->actingAs($this->admin)->get(route('residents.show', $this->minister))
            ->assertOk()->assertSee('Iglesia ni Cristo')->assertSee('Family of Ministers')->assertSee('Pabahay A · Unit A-1');
    }

    public function test_the_table_json_does_not_leak_religion_to_a_secretary(): void
    {
        // The page hides the columns, but the AJAX response used to carry every field
        $this->json($this->secretary)->assertOk()
            ->assertJsonMissingPath('data.0.religion_id')
            ->assertJsonMissingPath('data.0.is_minister_family')
            ->assertJsonMissingPath('data.0.pabahay_unit_id');

        $this->json($this->admin)->assertOk()
            ->assertJsonPath('data.0.religion_id', $this->inc->id)
            ->assertJsonPath('data.0.is_minister_family', true);
    }

    public function test_religion_fields_are_hidden_from_arrays_unless_an_admin_is_signed_in(): void
    {
        $fresh = Resident::find($this->minister->id);

        $this->assertArrayNotHasKey('religion_id', $fresh->toArray());          // nobody signed in
        $this->actingAs($this->secretary);
        $this->assertArrayNotHasKey('is_minister_family', Resident::find($fresh->id)->toArray());
        $this->actingAs($this->admin);
        $this->assertArrayHasKey('religion_id', Resident::find($fresh->id)->toArray());
    }

    public function test_a_secretary_cannot_use_the_religion_filter_even_by_typing_the_url(): void
    {
        $this->json($this->secretary, ['religion_group' => 'inc'])->assertForbidden();
        $this->json($this->secretary, ['fom' => 1])->assertForbidden();
        $this->json($this->secretary, ['pabahay_unit' => $this->unit->id])->assertForbidden();

        $this->json($this->admin, ['religion_group' => 'inc'])->assertOk();
    }

    public function test_exports_follow_the_filter_for_admin_and_refuse_a_secretary(): void
    {
        $this->actingAs($this->admin)->get(route('export.excel', ['residents', 'religion_group' => 'inc', 'fom' => 1]))->assertOk();
        $this->actingAs($this->secretary)->get(route('export.excel', ['residents', 'religion_group' => 'inc']))->assertForbidden();
        $this->actingAs($this->secretary)->get(route('export.pdf', ['residents', 'pabahay_unit' => $this->unit->id]))->assertForbidden();
    }

    public function test_a_secretary_cannot_change_religion_with_a_hand_made_request(): void
    {
        $this->actingAs($this->secretary)->put(route('residents.update', $this->minister), [
            'last_name' => 'Ministro', 'first_name' => 'Pedro', 'birthdate' => '1970-01-01', 'gender' => 'Male',
            'address' => '1 Luna St.', 'purok_id' => $this->purok->id,
            'religion_id' => $this->catholic->id, 'is_minister_family' => 0, 'pabahay_unit_id' => '',
        ])->assertSessionHasNoErrors();

        $kept = $this->minister->fresh();
        $this->assertSame($this->inc->id, $kept->religion_id);
        $this->assertTrue($kept->is_minister_family);
        $this->assertSame($this->unit->id, $kept->pabahay_unit_id);
    }

    public function test_a_secretary_cannot_open_the_religion_or_pabahay_pages(): void
    {
        foreach (['religions.index', 'pabahays.index', 'pabahays.create'] as $route) {
            $this->actingAs($this->secretary)->get(route($route))->assertForbidden();
            $this->actingAs($this->admin)->get(route($route))->assertOk();
        }
        $this->actingAs($this->secretary)->post(route('religions.store'), ['name' => 'Test'])->assertForbidden();
        $this->actingAs($this->secretary)->post(route('pabahays.units.store', Pabahay::first()), ['unit_numbers' => 'A-9'])->assertForbidden();
    }

    public function test_only_the_admin_gets_the_religion_filter_in_the_sidebar(): void
    {
        $this->actingAs($this->admin)->get(route('residents.index'))
            ->assertOk()->assertSee('id="religionTree"', false)->assertSee('Family of Ministers')
            ->assertSee('Pabahay A')->assertSee('A-1 (1)');

        $this->actingAs($this->secretary)->get(route('residents.index'))
            ->assertOk()->assertDontSee('religionTree')->assertDontSee('Pabahay A')->assertDontSee('Filter by religion');
    }

    public function test_religion_changes_are_kept_out_of_the_activity_log(): void
    {
        // The log is readable by Secretaries, so it must not carry religion values
        $this->actingAs($this->admin)->put(route('residents.update', $this->minister), $this->form([
            'last_name' => 'Renamed', 'religion_id' => $this->catholic->id,
        ]))->assertSessionHasNoErrors();

        $log = ActivityLog::where('loggable_id', $this->minister->id)->where('action', 'updated')->sole();
        $this->assertArrayHasKey('last_name', $log->changes);
        $this->assertSame([], array_intersect(array_keys($log->changes), ['religion_id', 'is_minister_family', 'pabahay_unit_id']));

        // A change to religion alone leaves no entry at all
        ActivityLog::query()->delete();
        $this->actingAs($this->admin)->put(route('residents.update', $this->minister), $this->form([
            'last_name' => 'Renamed', 'religion_id' => $this->inc->id,
        ]))->assertSessionHasNoErrors();
        $this->assertSame(0, ActivityLog::count());
    }

    private function form(array $overrides = []): array
    {
        return array_merge([
            'last_name' => 'Ministro', 'first_name' => 'Pedro', 'birthdate' => '1970-01-01', 'gender' => 'Male',
            'address' => '1 Luna St.', 'purok_id' => $this->purok->id,
        ], $overrides);
    }
}
