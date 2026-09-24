<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Step 1 before Part 2: living-only demographics, senior status from age,
 * and "residing since" / years of residency.
 */
class DemographicsTest extends TestCase
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
            'purok_id' => $this->purok->id, 'birthdate' => '1990-01-15', 'residency_status' => 'Active',
            'gender' => 'Female', 'residing_since' => null,
        ], $attrs));
    }

    private function form(array $overrides = []): array
    {
        return array_merge([
            'last_name' => 'Santos', 'first_name' => 'Maria', 'birthdate' => '1980-05-05',
            'gender' => 'Female', 'address' => '12 Rosal St.', 'purok_id' => $this->purok->id,
        ], $overrides);
    }

    // ── Living-only demographics ────────────────────────────────────────

    public function test_gender_and_age_group_totals_count_living_residents_only(): void
    {
        $this->resident(['gender' => 'Female', 'birthdate' => now()->subYears(8)->toDateString()]);
        $this->resident(['gender' => 'Male', 'birthdate' => now()->subYears(70)->toDateString()]);
        $this->resident(['gender' => 'Male', 'birthdate' => now()->subYears(80)->toDateString(), 'residency_status' => 'Deceased']);
        $this->resident(['gender' => 'Female', 'birthdate' => now()->subYears(30)->toDateString(), 'residency_status' => 'Transferred']);

        $this->actingAs($this->secretary)->get(route('dashboard'))
            ->assertOk()
            ->assertViewHas('totalMale', 1)
            ->assertViewHas('totalFemale', 1)
            ->assertViewHas('ageGroups', fn ($g) => $g['Children (0-12)'] === 1 && $g['Adults (18-59)'] === 0 && $g['Seniors (60+)'] === 1)
            ->assertViewHas('totalSeniors', 1);
    }

    // ── Senior status from age ─────────────────────────────────────────

    public function test_senior_status_is_calculated_from_the_birthdate(): void
    {
        $sixty    = $this->resident(['birthdate' => now()->subYears(60)->toDateString()]);
        $almost   = $this->resident(['birthdate' => now()->subYears(60)->addDay()->toDateString()]);

        $this->assertTrue($sixty->is_senior);
        $this->assertFalse($almost->is_senior);
        $this->assertSame([$sixty->id], Resident::seniors()->pluck('id')->all());
    }

    public function test_residents_become_seniors_on_their_60th_birthday_without_any_edit(): void
    {
        $r = $this->resident(['birthdate' => now()->subYears(60)->addDays(3)->toDateString()]);
        $this->assertFalse($r->fresh()->is_senior);
        $this->assertSame(0, Resident::seniors()->count());

        $this->travel(3)->days();

        $this->assertTrue($r->fresh()->is_senior);
        $this->assertSame(1, Resident::seniors()->count());
    }

    public function test_residents_list_senior_filter_uses_age(): void
    {
        $this->resident(['birthdate' => now()->subYears(65)->toDateString()]);
        $this->resident(['birthdate' => now()->subYears(40)->toDateString()]);

        $count = $this->actingAs($this->secretary)
            ->getJson(route('residents.index', ['tags' => ['senior'], 'status' => 'Active', 'draw' => 1, 'start' => 0, 'length' => 50]),
                ['X-Requested-With' => 'XMLHttpRequest'])
            ->assertOk()->json('recordsFiltered');

        $this->assertSame(1, $count);
    }

    public function test_the_form_has_no_senior_checkbox_any_more(): void
    {
        $this->actingAs($this->secretary)->get(route('residents.create'))
            ->assertOk()
            ->assertDontSee('name="is_senior"', false)
            ->assertSee('Automatic from date of birth');
    }

    // ── Residing since / years of residency ─────────────────────────────

    public function test_residing_since_is_saved_and_years_are_calculated(): void
    {
        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->form(['residing_since' => now()->subYears(12)->subDay()->toDateString()]))
            ->assertSessionHasNoErrors();

        $r = Resident::sole();
        $this->assertSame(12, $r->years_of_residency);

        $this->actingAs($this->secretary)->get(route('residents.show', $r))
            ->assertOk()
            ->assertSee('Residing Since')
            ->assertSee('(12 years)');
    }

    public function test_residing_since_cannot_be_in_the_future_or_before_birth(): void
    {
        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->form(['residing_since' => now()->addDay()->toDateString()]))
            ->assertSessionHasErrors('residing_since');

        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->form(['residing_since' => '1970-01-01']))   // born 1980
            ->assertSessionHasErrors('residing_since');

        $this->assertSame(0, Resident::count());
    }

    public function test_unknown_residing_since_shows_a_dash(): void
    {
        $r = $this->resident();
        $this->assertNull($r->years_of_residency);
    }

    public function test_certificate_of_residency_states_how_long_they_have_lived_here(): void
    {
        $known   = $this->resident(['first_name' => 'Ana', 'residing_since' => now()->subYears(18)->subDay()->toDateString()]);
        $unknown = $this->resident(['first_name' => 'Ben']);

        $cert = fn (Resident $r) => Document::create([
            'doc_number' => 'CR-'.$r->id, 'resident_id' => $r->id, 'document_type' => 'Certificate of Residency',
            'purpose' => 'Scholarship', 'fee_paid' => 0, 'status' => 'Pending', 'issued_by' => $this->secretary->id,
        ]);

        $year = now()->subYears(18)->subDay()->format('Y');
        $this->actingAs($this->secretary)->get(route('documents.show', $cert($known)))
            ->assertOk()
            ->assertSee("since <span class='highlight'>$year</span>", false)
            ->assertSee('18 years');

        $this->actingAs($this->secretary)->get(route('documents.show', $cert($unknown)))
            ->assertOk()
            ->assertSee('for a considerable period of time');
    }
}
