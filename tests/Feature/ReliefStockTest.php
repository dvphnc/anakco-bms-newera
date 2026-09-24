<?php

namespace Tests\Feature;

use App\Models\AssistanceProgram;
use App\Models\Purok;
use App\Models\ReliefSupply;
use App\Models\ReliefSupplyMovement;
use App\Models\Resident;
use App\Models\ResidentTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Relief programs draw from the BDRRM relief supplies inventory.
 */
class ReliefStockTest extends TestCase
{
    use RefreshDatabase;

    private User $secretary;
    private Purok $purok;
    private ReliefSupply $rice;
    private ReliefSupply $sardines;

    protected function setUp(): void
    {
        parent::setUp();

        $this->secretary = User::factory()->create(['role' => 'Secretary']);
        $this->purok     = Purok::create(['name' => 'Purok 1']);
        $this->rice      = $this->supply('Rice', 10, 'kg');
        $this->sardines  = $this->supply('Sardines', 20, 'cans');
    }

    private function supply(string $name, int $qty, string $unit): ReliefSupply
    {
        return ReliefSupply::create([
            'item_name' => $name, 'category' => 'Food', 'quantity' => $qty, 'unit' => $unit,
            'source' => 'DSWD', 'status' => 'Available',
        ]);
    }

    private int $street = 1;

    /** A living resident in their own household. */
    private function resident(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            'purok_id' => $this->purok->id, 'address' => ($this->street++).' Luna St.',
            'birthdate' => '1980-01-01', 'residency_status' => 'Active',
        ], $attrs))->fresh();
    }

    /** Each claim: 3 kg rice + 5 cans of sardines. */
    private function program(array $uses = null): AssistanceProgram
    {
        $program = AssistanceProgram::create([
            'name' => 'Relief Pack — Typhoon Kristine', 'type' => 'relief', 'claim_scope' => 'household',
            'max_claims' => 1, 'starts_on' => now()->subDay(), 'is_active' => true,
        ]);
        $program->supplies()->sync($uses ?? [
            $this->rice->id     => ['quantity_per_claim' => 3],
            $this->sardines->id => ['quantity_per_claim' => 5],
        ]);

        return $program;
    }

    private function claim(Resident $r, AssistanceProgram $p)
    {
        return $this->actingAs($this->secretary)
            ->postJson(route('residents.transactions.store', $r), ['assistance_program_id' => $p->id]);
    }

    public function test_a_claim_takes_its_items_out_of_stock(): void
    {
        $program = $this->program();

        $ref = $this->claim($this->resident(), $program)->assertOk()->json('reference_no');

        $this->assertSame(7, $this->rice->fresh()->quantity);
        $this->assertSame(15, $this->sardines->fresh()->quantity);

        $out = ReliefSupplyMovement::where('change', '<', 0)->get();
        $this->assertCount(2, $out);
        $this->assertTrue($out->every(fn ($m) => $m->transaction->reference_no === $ref && $m->performed_by === $this->secretary->id));
        $this->assertSame([10, 7], [$out->firstWhere('relief_supply_id', $this->rice->id)->stock_before,
                                    $out->firstWhere('relief_supply_id', $this->rice->id)->stock_after]);
    }

    public function test_a_claim_is_refused_when_stock_runs_out_and_nothing_is_taken(): void
    {
        $program = $this->program();
        $this->claim($this->resident(), $program)->assertOk();   // rice 10 → 7
        $this->claim($this->resident(), $program)->assertOk();   // 7 → 4
        $this->claim($this->resident(), $program)->assertOk();   // 4 → 1

        $this->claim($this->resident(), $program)
            ->assertStatus(422)
            ->assertJsonPath('errors.assistance_program_id.0', fn ($m) => str_contains($m, 'Not enough stock')
                && str_contains($m, 'Rice (1 kg left, 3 per claim)')
                && ! str_contains($m, 'Sardines'));   // 5 cans left still covers one claim

        $this->assertSame(1, $this->rice->fresh()->quantity);
        $this->assertSame(5, $this->sardines->fresh()->quantity);   // not taken either
        $this->assertSame(3, ResidentTransaction::count());
    }

    public function test_using_up_an_item_marks_it_depleted_and_voiding_brings_it_back(): void
    {
        $this->rice->update(['quantity' => 3]);
        $program = $this->program();
        $resident = $this->resident();

        $this->claim($resident, $program)->assertOk();
        $this->assertSame(0, $this->rice->fresh()->quantity);
        $this->assertSame('Depleted', $this->rice->fresh()->status);

        $tx = ResidentTransaction::sole();
        $this->actingAs($this->secretary)
            ->patchJson(route('transactions.void', $tx), ['reason' => 'Wrong household'])
            ->assertOk();

        $this->assertSame(3, $this->rice->fresh()->quantity);
        $this->assertSame('Available', $this->rice->fresh()->status);
        $this->assertSame(20, $this->sardines->fresh()->quantity);

        // The household can claim again, and stock is taken again
        $this->claim($resident, $program)->assertOk();
        $this->assertSame(0, $this->rice->fresh()->quantity);
    }

    public function test_voiding_returns_what_the_claim_took_even_if_the_program_changed_since(): void
    {
        $program = $this->program();
        $this->claim($this->resident(), $program)->assertOk();

        // Program now uses only 1 kg rice per claim, and no sardines
        $program->supplies()->sync([$this->rice->id => ['quantity_per_claim' => 1]]);

        $this->actingAs($this->secretary)
            ->patchJson(route('transactions.void', ResidentTransaction::sole()), ['reason' => 'Recorded twice'])
            ->assertOk();

        $this->assertSame(10, $this->rice->fresh()->quantity);
        $this->assertSame(20, $this->sardines->fresh()->quantity);
    }

    public function test_eligibility_check_reports_stock(): void
    {
        $program = $this->program();
        $resident = $this->resident();

        $check = fn () => $this->actingAs($this->secretary)
            ->getJson(route('residents.eligibility', [$resident, $program]))->assertOk();

        $check()->assertJsonPath('eligible', true)->assertJsonPath('claims_left', 3);   // rice: 10 / 3

        $this->rice->update(['quantity' => 2]);
        $check()->assertJsonPath('eligible', false)->assertJsonPath('claims_left', 0)
            ->assertJsonPath('reason', fn ($m) => str_contains($m, 'Not enough stock'));
    }

    public function test_programs_without_supplies_are_unaffected(): void
    {
        $program = $this->program([]);

        $this->claim($this->resident(), $program)->assertOk();

        $this->assertNull($program->claimsLeft());
        $this->assertSame(10, $this->rice->fresh()->quantity);
    }

    public function test_the_program_form_saves_what_each_claim_uses(): void
    {
        $form = [
            'name' => 'Rice Subsidy', 'type' => 'relief', 'claim_scope' => 'household', 'max_claims' => 1, 'is_active' => '1',
            'supplies' => [
                7  => ['relief_supply_id' => $this->rice->id, 'quantity_per_claim' => 2],
                9  => ['relief_supply_id' => '', 'quantity_per_claim' => 1],   // blank row: ignored
            ],
        ];

        $this->actingAs($this->secretary)->post(route('programs.store'), $form)->assertSessionHasNoErrors();

        $program = AssistanceProgram::where('name', 'Rice Subsidy')->sole();
        $this->assertSame([$this->rice->id => 2], $program->supplies->pluck('pivot.quantity_per_claim', 'id')->all());

        // Listing the same item twice is rejected, on the row that repeats it
        $form['supplies'][9] = ['relief_supply_id' => $this->rice->id, 'quantity_per_claim' => 1];
        $this->actingAs($this->secretary)->put(route('programs.update', $program), $form)
            ->assertSessionHasErrors('supplies.9.relief_supply_id');

        // Removing every row unlinks the program from stock
        unset($form['supplies']);
        $this->actingAs($this->secretary)->put(route('programs.update', $program), $form)->assertSessionHasNoErrors();
        $this->assertCount(0, $program->fresh()->supplies);
    }

    public function test_the_programs_list_shows_how_many_claims_the_stock_covers(): void
    {
        $this->program();

        $this->actingAs($this->secretary)->get(route('programs.index'))
            ->assertOk()
            ->assertSee('Enough for 3');
    }

    public function test_a_supply_used_by_a_program_cannot_be_deleted(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $this->program();
        $spare = $this->supply('Blankets', 5, 'pcs');

        $this->actingAs($admin)
            ->deleteJson(route('committees.destroyRelief', ['bdrrm', $this->rice->id]))
            ->assertStatus(422)
            ->assertJsonPath('message', fn ($m) => str_contains($m, 'Relief Pack — Typhoon Kristine'));
        $this->assertNotNull($this->rice->fresh());

        $this->actingAs($admin)
            ->deleteJson(route('committees.destroyRelief', ['bdrrm', $spare->id]))
            ->assertOk();
        $this->assertNull($spare->fresh());
    }

    public function test_stock_added_or_corrected_by_hand_is_logged(): void
    {
        $this->actingAs($this->secretary);
        $this->rice->update(['quantity' => 25]);

        $log = $this->rice->movements()->orderBy('id')->get();
        $this->assertSame(['Received from DSWD', 'Edited in the relief inventory'], $log->pluck('reason')->all());
        $this->assertSame([10, 15], $log->pluck('change')->all());
        $this->assertSame(25, $log->last()->stock_after);
    }
}
