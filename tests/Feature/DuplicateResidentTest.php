<?php

namespace Tests\Feature;

use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use App\Services\DuplicateResidentFinder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Warn before registering someone who is probably already on record.
 */
class DuplicateResidentTest extends TestCase
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

    private function existing(array $attrs = []): Resident
    {
        return Resident::factory()->create(array_merge([
            'purok_id' => $this->purok->id, 'first_name' => 'Maria', 'last_name' => 'Santos',
            'middle_name' => 'Reyes', 'birthdate' => '1968-03-14', 'residency_status' => 'Active',
        ], $attrs));
    }

    private function form(array $overrides = []): array
    {
        return array_merge([
            'first_name' => 'Ma.', 'last_name' => 'Santos', 'birthdate' => '1968-03-14',
            'gender' => 'Female', 'address' => '12 Rosal St.', 'purok_id' => $this->purok->id,
        ], $overrides);
    }

    private function finds(array $data): bool
    {
        return app(DuplicateResidentFinder::class)->find($data)->isNotEmpty();
    }

    public function test_likely_duplicates_are_found(): void
    {
        $this->existing();

        $this->assertTrue($this->finds(['first_name' => 'Ma.',    'last_name' => 'Santos',  'birthdate' => '1968-03-14']));
        $this->assertTrue($this->finds(['first_name' => 'Maira',  'last_name' => 'Santos',  'birthdate' => '1968-03-14'])); // typo
        $this->assertTrue($this->finds(['first_name' => 'MARIA',  'last_name' => 'Santoss', 'birthdate' => '1968-03-14'])); // typo
        $this->assertTrue($this->finds(['first_name' => 'Maria',  'last_name' => 'Santos',  'birthdate' => '1969-03-14'])); // birthdate typo
    }

    public function test_different_people_are_not_flagged(): void
    {
        $this->existing();

        $this->assertFalse($this->finds(['first_name' => 'Maria', 'last_name' => 'Santos', 'birthdate' => '1990-03-14']));     // other year
        $this->assertFalse($this->finds(['first_name' => 'Jose',  'last_name' => 'Santos', 'birthdate' => '1968-03-14']));     // twin brother
        $this->assertFalse($this->finds(['first_name' => 'Maria', 'last_name' => 'Garcia', 'birthdate' => '1968-03-14']));     // other family
        $this->assertFalse($this->finds(['first_name' => 'Maria', 'last_name' => 'Santos', 'middle_name' => 'Cruz', 'birthdate' => '1968-03-14'])); // other middle name
    }

    public function test_registration_pauses_and_shows_the_existing_record(): void
    {
        $this->existing();

        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->form())
            ->assertRedirect()
            ->assertSessionHas('possibleDuplicates', fn ($d) => count($d) === 1 && $d[0]['name'] === 'Maria R. Santos');

        $this->assertSame(1, Resident::count());   // nothing saved yet

        $this->actingAs($this->secretary)
            ->followingRedirects()
            ->from(route('residents.create'))
            ->post(route('residents.store'), $this->form())
            ->assertSee('This may be someone who is already registered')
            ->assertSee('register anyway');
    }

    public function test_confirming_a_different_person_saves(): void
    {
        $this->existing();

        $this->actingAs($this->secretary)
            ->post(route('residents.store'), $this->form(['confirm_not_duplicate' => '1']))
            ->assertSessionHasNoErrors()
            ->assertSessionMissing('possibleDuplicates');

        $this->assertSame(2, Resident::count());
    }

    public function test_moved_out_matches_are_included_with_a_hint(): void
    {
        $this->existing(['residency_status' => 'Transferred']);

        $this->actingAs($this->secretary)
            ->followingRedirects()
            ->from(route('residents.create'))
            ->post(route('residents.store'), $this->form())
            ->assertSee('Moved Out')
            ->assertSee('use Update Status instead');
    }
}
