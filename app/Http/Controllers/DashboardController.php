<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;

class DashboardController extends Controller
{
    public function index()
    {
        // Stats
        $totalResidents = Resident::count();
        $totalActive = Resident::where('residency_status', 'Active')->count();
        $totalDeceased = Resident::where('residency_status', 'Deceased')->count();
        $totalTransferred = Resident::where('residency_status', 'Transferred')->count();
        $totalMale = Resident::where('gender', 'Male')->count();
        $totalFemale = Resident::where('gender', 'Female')->count();
        $totalHouseholds = Household::count();
        $totalVoters = Resident::where('is_voter', true)->count();
        $totalSeniors = Resident::where('is_senior', true)->count();
        $totalPwd = Resident::where('is_pwd', true)->count();
        $totalSoloParent = Resident::where('is_solo_parent', true)->count();
        $total4ps = Resident::where('is_4ps', true)->count();
        $totalBusinesses = Business::count();
        $activeBusinesses = Business::where('status', 'Active')->count();
        $expiredBusinesses = Business::where('status', 'Expired')->count();
        $pendingDocuments = Document::where('status', 'Pending')->count();
        $releasedDocuments = Document::where('status', 'Released')->count();
        $totalDocuments = Document::count();
        $activeBlotter = BlotterCase::where('status', 'Active')->count();
        $pendingAppointments = DocumentAppointment::where('status', 'Pending')->count();
        $settledBlotter = BlotterCase::whereIn('status', ['Settled', 'Closed'])->count();
        $totalBlotter = BlotterCase::count();

        // Charts
        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = Document::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $m)->count();
        }

        $ageGroups = [
            'Children (0-12)' => Resident::whereBetween(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), [0, 12])->count(),
            'Teens (13-17)' => Resident::whereBetween(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), [13, 17])->count(),
            'Adults (18-59)' => Resident::whereBetween(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), [18, 59])->count(),
            'Seniors (60+)' => Resident::where(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), '>=', 60)->count(),
        ];

        $residentsByPurok = Purok::withCount([
            'residents' => fn ($q) => $q->where('residency_status', 'Active'),
        ])->orderByDesc('residents_count')->get();

        $documentsByType = Document::selectRaw('document_type, count(*) as count')
            ->groupBy('document_type')->pluck('count', 'document_type');

        $blotterByType = BlotterCase::selectRaw('incident_type, count(*) as count')
            ->groupBy('incident_type')->pluck('count', 'incident_type');

        // Recent records
        $recentResidents = Resident::with('purok')->latest()->limit(5)->get();
        $recentDocuments = Document::with('resident')->latest()->limit(5)->get();
        $recentBlotter = BlotterCase::latest()->limit(5)->get();

        // Activity feed
        $recentActivity = ActivityLog::with('user')->latest()->limit(10)->get();

        return view('dashboard', compact(
            'totalResidents', 'totalActive', 'totalDeceased', 'totalTransferred',
            'totalMale', 'totalFemale', 'totalHouseholds',
            'totalVoters', 'totalSeniors', 'totalPwd', 'totalSoloParent', 'total4ps',
            'totalBusinesses', 'activeBusinesses', 'expiredBusinesses',
            'pendingDocuments', 'releasedDocuments', 'totalDocuments',
            'activeBlotter', 'settledBlotter', 'totalBlotter',
            'pendingAppointments',
            'monthlyData', 'ageGroups', 'residentsByPurok',
            'documentsByType', 'blotterByType',
            'recentResidents', 'recentDocuments', 'recentBlotter',
            'recentActivity'
        ));
    }
}
