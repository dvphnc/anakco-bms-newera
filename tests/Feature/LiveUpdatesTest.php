<?php

namespace Tests\Feature;

use App\Http\Controllers\LiveController;
use App\Models\Purok;
use App\Models\Resident;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Tasks 26.2 and 26.3: live dashboard numbers and live lists over Server-Sent Events.
 */
class LiveUpdatesTest extends TestCase
{
    use RefreshDatabase;

    private function resident(array $attrs = []): Resident
    {
        $purok = Purok::first() ?? Purok::create(['name' => 'Purok 1']);

        return Resident::factory()->create(array_merge(['purok_id' => $purok->id, 'religion_id' => null, 'residency_status' => 'Active'], $attrs));
    }

    public function test_dashboard_numbers_match_what_the_dashboard_shows(): void
    {
        foreach (range(1, 3) as $i) { $this->resident(); }
        $this->resident(['residency_status' => 'Deceased']);
        $admin = User::factory()->create(['role' => 'Admin']);

        $data = $this->actingAs($admin)->getJson(route('live.snapshot', ['topics' => 'dashboard']))->assertOk()->json();

        $this->assertSame(4, $data['dashboard']['totalResidents']);
        $this->assertSame(3, $data['dashboard']['totalActive']);
        $this->assertEquals(LiveController::dashboardNumbers(), $data['dashboard']);
        $this->actingAs($admin)->get(route('dashboard'))->assertOk()->assertSee('data-live="totalResidents"', false);
    }

    public function test_a_list_version_changes_when_its_data_changes(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        $before = $this->actingAs($admin)->getJson(route('live.snapshot', ['topics' => 'residents']))->json('lists.residents');

        $resident = $this->resident();
        $added = $this->actingAs($admin)->getJson(route('live.snapshot', ['topics' => 'residents']))->json('lists.residents');
        $this->assertNotSame($before, $added);

        $resident->delete(); // archive
        $archived = $this->actingAs($admin)->getJson(route('live.snapshot', ['topics' => 'residents']))->json('lists.residents');
        $this->assertNotSame($added, $archived);
    }

    public function test_lists_are_only_sent_to_roles_that_can_view_them(): void
    {
        $committee = User::factory()->create(['role' => 'Committee']);

        $data = $this->actingAs($committee)
            ->getJson(route('live.snapshot', ['topics' => 'residents,households,documents,blotter,businesses,appointments']))
            ->assertOk()->json();

        $this->assertArrayNotHasKey('lists', $data);
    }

    public function test_guests_cannot_open_the_stream(): void
    {
        $this->get(route('live.stream'))->assertRedirect();
        $this->get(route('live.snapshot'))->assertRedirect();
    }

    public function test_the_stream_sends_server_sent_events(): void
    {
        config(['bms.live_stream_seconds' => 0]); // end at once so the test does not wait
        $admin = User::factory()->create(['role' => 'Admin']);

        $response = $this->actingAs($admin)->get(route('live.stream', ['topics' => 'dashboard']));

        $response->assertOk();
        $this->assertStringStartsWith('text/event-stream', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('retry: 3000', $response->streamedContent());
    }
}
