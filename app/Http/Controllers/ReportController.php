<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\Document;
use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Purok;

class ReportController extends Controller
{
    public function index()
    {
        // --- Resident Population Stats ---
        $totalActive      = Resident::active()->count();
        $totalDeceased    = Resident::where('residency_status', 'Deceased')->count();
        $totalTransferred = Resident::where('residency_status', 'Transferred')->count();
        $totalMale        = Resident::active()->where('gender', 'Male')->count();
        $totalFemale      = Resident::active()->where('gender', 'Female')->count();
        $totalVoters      = Resident::active()->voters()->count();
        $totalSeniors     = Resident::active()->seniors()->count();
        $totalPwd         = Resident::active()->pwd()->count();
        $totalSoloParent  = Resident::active()->where('is_solo_parent', true)->count();
        $total4ps         = Resident::active()->where('is_4ps', true)->count();

        // --- Age Groups ---
        $ageGroups = [
            '0 - 12'  => Resident::active()->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 0 AND 12')->count(),
            '13 - 17' => Resident::active()->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 13 AND 17')->count(),
            '18 - 59' => Resident::active()->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) BETWEEN 18 AND 59')->count(),
            '60+'     => Resident::active()->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= 60')->count(),
        ];

        // --- Residents per Purok ---
        $residentsByPurok = Purok::withCount(['residents' => function ($q) {
            $q->where('residency_status', 'Active');
        }])->orderBy('name')->get();

        // --- Households ---
        $totalHouseholds = Household::count();

        // --- Documents ---
        $totalDocuments   = Document::count();
        $pendingDocuments = Document::where('status', 'Pending')->count();
        $releasedDocuments= Document::where('status', 'Released')->count();

        // Documents by type
        $documentsByType = Document::selectRaw('document_type, COUNT(*) as total')
            ->groupBy('document_type')
            ->orderByDesc('total')
            ->pluck('total', 'document_type')
            ->toArray();

        // Monthly documents (current year)
        $monthlyDocuments = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = $monthlyDocuments[$i] ?? 0;
        }

        // --- Blotter ---
        $totalBlotter  = BlotterCase::count();
        $activeBlotter = BlotterCase::where('status', 'Active')->count();
        $settledBlotter= BlotterCase::whereIn('status', ['Settled', 'Closed'])->count();

        $blotterByType = BlotterCase::selectRaw('incident_type, COUNT(*) as total')
            ->groupBy('incident_type')
            ->orderByDesc('total')
            ->pluck('total', 'incident_type')
            ->toArray();

        // --- Businesses ---
        $totalBusinesses  = Business::count();
        $activeBusinesses = Business::where('status', 'Active')->count();
        $expiredBusinesses= Business::where('status', 'Expired')->count();

        return view('reports.reports-index', compact(
            'totalActive',
            'totalDeceased',
            'totalTransferred',
            'totalMale',
            'totalFemale',
            'totalVoters',
            'totalSeniors',
            'totalPwd',
            'totalSoloParent',
            'total4ps',
            'ageGroups',
            'residentsByPurok',
            'totalHouseholds',
            'totalDocuments',
            'pendingDocuments',
            'releasedDocuments',
            'documentsByType',
            'monthlyData',
            'totalBlotter',
            'activeBlotter',
            'settledBlotter',
            'blotterByType',
            'totalBusinesses',
            'activeBusinesses',
            'expiredBusinesses'
        ));
    }
}