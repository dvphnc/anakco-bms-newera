<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')
            ->when($request->module, function ($q) use ($request) {
                $modelMap = [
                    'residents'   => 'App\Models\Resident',
                    'households'  => 'App\Models\Household',
                    'documents'   => 'App\Models\Document',
                    'blotter'     => 'App\Models\BlotterCase',
                    'businesses'  => 'App\Models\Business',
                    'officials'   => 'App\Models\Official',
                    'users'       => 'App\Models\User',
                ];
                if (isset($modelMap[$request->module])) {
                    $q->where('loggable_type', $modelMap[$request->module]);
                }
            })
            ->when($request->action, fn($q) => $q->where('action', $request->action))
            ->when($request->user_id, fn($q) => $q->where('user_id', $request->user_id))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        // Summary counts per module
        $moduleCounts = ActivityLog::selectRaw('loggable_type, count(*) as total')
            ->groupBy('loggable_type')
            ->pluck('total', 'loggable_type');

        $users = \App\Models\User::orderBy('name')->get();

        return view('activity-log.index', compact('query', 'moduleCounts', 'users'));
    }
}