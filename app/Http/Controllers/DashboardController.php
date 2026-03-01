<?php

namespace App\Http\Controllers;

use App\Models\Resident;
use App\Models\Household;
use App\Models\Document;
use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Official;

class DashboardController extends Controller
{
    public function index()
    {
        // --- Resident Stats ---
        $totalResidents   = Resident::active()->count();
        $totalMale        = Resident::active()->where('gender', 'Male')->count();
        $totalFemale      = Resident::active()->where('gender', 'Female')->count();
        $totalVoters      = Resident::active()->voters()->count();
        $totalSeniors     = Resident::active()->seniors()->count();
        $totalPwd         = Resident::active()->pwd()->count();

        // --- Household Stats ---
        $totalHouseholds  = Household::count();

        // --- Document Stats ---
        $totalDocuments   = Document::count();
        $pendingDocuments = Document::where('status', 'Pending')->count();

        // --- Blotter Stats ---
        $totalBlotter     = BlotterCase::count();
        $activeBlotter    = BlotterCase::where('status', 'Active')->count();

        // --- Business Stats ---
        $totalBusinesses  = Business::count();
        $activeBusinesses = Business::where('status', 'Active')->count();

        // --- Recent Records ---
        $recentResidents  = Resident::with('purok')
                                ->latest()
                                ->take(5)
                                ->get();

        $recentDocuments  = Document::with('resident')
                                ->latest()
                                ->take(5)
                                ->get();

        $recentBlotter    = BlotterCase::latest()
                                ->take(5)
                                ->get();

        // --- Monthly Documents (current year) ---
        $monthlyDocuments = Document::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
                                ->whereYear('created_at', date('Y'))
                                ->groupBy('month')
                                ->orderBy('month')
                                ->pluck('total', 'month')
                                ->toArray();

        // Fill missing months with 0
        $monthlyData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlyData[$i] = $monthlyDocuments[$i] ?? 0;
        }

        // --- Officials ---
        $officials = Official::active()->orderBy('position')->get();

        return view('dashboard.dashboard', compact(
            'totalResidents',
            'totalMale',
            'totalFemale',
            'totalVoters',
            'totalSeniors',
            'totalPwd',
            'totalHouseholds',
            'totalDocuments',
            'pendingDocuments',
            'totalBlotter',
            'activeBlotter',
            'totalBusinesses',
            'activeBusinesses',
            'recentResidents',
            'recentDocuments',
            'recentBlotter',
            'monthlyData',
            'officials'
        ));
    }
}