<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->when($request->period, function ($q) use ($request) {
                if ($request->period === 'today') {
                    $q->whereDate('created_at', today());
                } elseif ($request->period === 'week') {
                    $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                } elseif ($request->period === 'month') {
                    $q->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
                }
            })
            ->when($request->module, function ($q) use ($request) {
                $modelMap = [
                    'residents' => 'App\Models\Resident',
                    'households' => 'App\Models\Household',
                    'documents' => 'App\Models\Document',
                    'blotter' => 'App\Models\BlotterCase',
                    'businesses' => 'App\Models\Business',
                    'officials' => 'App\Models\Official',
                    'users' => 'App\Models\User',
                    'puroks' => 'App\Models\Purok',
                ];
                if (isset($modelMap[$request->module])) {
                    $q->where('loggable_type', $modelMap[$request->module]);
                }
            })
            ->when($request->action, fn ($q) => $q->where('action', $request->action))
            ->when($request->user_id, fn ($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'html'  => view('activity-log._feed', compact('query'))->render(),
                'total' => $query->total(),
            ]);
        }

        // Summary counts per module
        $moduleCounts = ActivityLog::selectRaw('loggable_type, count(*) as total')
            ->groupBy('loggable_type')
            ->pluck('total', 'loggable_type');

        $users = \App\Models\User::orderBy('name')->get();

        // Weekly chart — last 7 days broken by action
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = now()->subDays($i);
            $dayLogs = ActivityLog::whereDate('created_at', $day)->get();
            $weeklyData[] = [
                'label' => $day->format('D'),
                'date' => $day->format('M d'),
                'created' => $dayLogs->where('action', 'created')->count(),
                'updated' => $dayLogs->where('action', 'updated')->count(),
                'deleted' => $dayLogs->where('action', 'deleted')->count(),
                'total' => $dayLogs->count(),
            ];
        }

        // Monthly chart — last 12 months
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $monthlyData[] = [
                'label' => $month->format('M'),
                'year' => $month->format('Y'),
                'total' => ActivityLog::whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->count(),
            ];
        }

        // Action breakdown totals
        $actionTotals = [
            'created' => ActivityLog::where('action', 'created')->count(),
            'updated' => ActivityLog::where('action', 'updated')->count(),
            'deleted' => ActivityLog::where('action', 'deleted')->count(),
        ];

        // Today vs yesterday
        $todayCount = ActivityLog::whereDate('created_at', today())->count();
        $yesterdayCount = ActivityLog::whereDate('created_at', today()->subDay())->count();
        $thisWeekCount = ActivityLog::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $thisMonthCount = ActivityLog::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)->count();

        return view('activity-log.index', compact(
            'query', 'moduleCounts', 'users',
            'weeklyData', 'monthlyData', 'actionTotals',
            'todayCount', 'yesterdayCount', 'thisWeekCount', 'thisMonthCount'
        ));
    }
}
