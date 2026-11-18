<?php

namespace App\Http\Controllers;

use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Models\Household;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Live updates (tasks 26.2 and 26.3) over Server-Sent Events, the same method as the
 * portal badge (task 26.1): one open connection, data sent only when something changes.
 *
 *  - Dashboard numbers: the stat cards and demographics refresh on their own.
 *  - Lists: each list sends a small "version" (count + latest change). When it changes,
 *    the page reloads that table in place, keeping the current page and search.
 *
 * Each stream ends after a few minutes so it never holds a server worker forever;
 * the browser reconnects by itself. /live/snapshot is the plain JSON fallback.
 */
class LiveController extends Controller
{
    /** List name => [model, permission needed to see it]. */
    private const LISTS = [
        'residents'    => [Resident::class, 'residents.view'],
        'households'   => [Household::class, 'households.view'],
        'documents'    => [Document::class, 'documents.view'],
        'blotter'      => [BlotterCase::class, 'blotter.view'],
        'businesses'   => [Business::class, 'businesses.view'],
        'appointments' => [DocumentAppointment::class, 'appointments.view'],
    ];

    /** Dashboard numbers. DashboardController uses the same method, so both always agree. */
    public static function dashboardNumbers(): array
    {
        return [
            'totalResidents'      => Resident::count(),
            'pendingDocuments'    => Document::where('status', 'Pending')->count(),
            'activeBlotter'       => BlotterCase::where('status', 'Active')->count(),
            'activeBusinesses'    => Business::where('status', 'Active')->count(),
            'pendingAppointments' => DocumentAppointment::where('status', 'Pending')->count(),
            'totalActive'         => Resident::where('residency_status', 'Active')->count(),
            'totalSeniors'        => Resident::active()->seniors()->count(),
            'totalVoters'         => Resident::residentVoters()->count(),
            'totalPwd'            => Resident::active()->where('is_pwd', true)->count(),
            'totalSoloParent'     => Resident::active()->where('is_solo_parent', true)->count(),
            'total4ps'            => Resident::active()->where('is_4ps', true)->count(),
            'totalMale'           => Resident::active()->where('gender', 'Male')->count(),
            'totalFemale'         => Resident::active()->where('gender', 'Female')->count(),
            'totalHouseholds'     => Household::count(),
        ];
    }

    /** What this user asked for and is allowed to see. */
    public function snapshotFor(Request $request): array
    {
        $topics = array_filter(explode(',', (string) $request->query('topics', '')));
        $user   = $request->user();
        $out    = [];

        if (in_array('dashboard', $topics, true)) {
            $out['dashboard'] = self::dashboardNumbers();
        }

        foreach (self::LISTS as $name => [$model, $permission]) {
            if (! in_array($name, $topics, true) || ! $user->hasPermission($permission)) {
                continue;
            }
            // count of live rows + latest change of any row, archived ones included
            $row = $model::withTrashed()
                ->selectRaw('COUNT(*) as total, SUM(deleted_at IS NULL) as live, MAX(updated_at) as changed')
                ->first();
            $out['lists'][$name] = $row->total . '|' . $row->live . '|' . $row->changed;
        }

        return $out;
    }

    public function snapshot(Request $request)
    {
        return response()->json($this->snapshotFor($request));
    }

    public function stream(Request $request)
    {
        $seconds = (int) config('bms.live_stream_seconds', 240);

        return response()->stream(function () use ($request, $seconds) {
            // send each event straight away (in tests, keep the buffer that captures the output)
            if (! app()->runningUnitTests()) {
                while (ob_get_level() > 0 && @ob_end_clean()) {
                }
            }
            set_time_limit($seconds + 30);

            echo "retry: 3000\n\n"; // reconnect after 3 s when this stream ends
            $last  = null;
            $until = time() + $seconds;

            while (time() < $until) {
                if (connection_aborted()) {
                    break;
                }

                $now = $this->snapshotFor($request);
                if ($now !== $last) {
                    echo 'data: ' . json_encode($now) . "\n\n";
                    $last = $now;
                } else {
                    echo ": heartbeat\n\n";
                }
                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                DB::disconnect(); // don't keep a database connection open while waiting
                sleep(4);
            }
        }, 200, [
            'Content-Type'      => 'text/event-stream',
            'Cache-Control'     => 'no-cache, no-store, must-revalidate',
            'X-Accel-Buffering' => 'no',
            'Connection'        => 'keep-alive',
        ]);
    }
}
