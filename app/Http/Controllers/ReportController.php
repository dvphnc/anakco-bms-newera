<?php

namespace App\Http\Controllers;

use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\Household;
use App\Models\Official;
use App\Models\Purok;
use App\Models\Resident;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index()
    {
        $currentYear  = (int) date('Y');
        $currentMonth = (int) date('n');
        $snapshotAt   = now();

        // Monthly trend arrays (12 months of current year)
        $monthlyDocs    = [];
        $monthlyBlotter = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyDocs[]    = Document::whereYear('created_at', $currentYear)->whereMonth('created_at', $m)->count();
            $monthlyBlotter[] = BlotterCase::whereYear('created_at', $currentYear)->whereMonth('created_at', $m)->count();
        }

        // Aggregations by type/location
        $docsByType    = Document::whereYear('created_at', $currentYear)
                            ->selectRaw('document_type, count(*) as total')
                            ->groupBy('document_type')->pluck('total', 'document_type');
        $blotterByType = BlotterCase::whereYear('created_at', $currentYear)
                            ->selectRaw('incident_type, count(*) as total')
                            ->groupBy('incident_type')->pluck('total', 'incident_type');
        $puroks        = Purok::withCount(['residents' => fn ($q) => $q->where('residency_status', 'Active')])
                            ->orderByDesc('residents_count')->get();

        // Core KPIs
        $totalResidents  = Resident::count();
        $activeResidents = Resident::where('residency_status', 'Active')->count();
        $totalDocs       = Document::whereYear('created_at', $currentYear)->count();
        $totalBlotter    = BlotterCase::whereYear('created_at', $currentYear)->count();
        $totalBusinesses = Business::where('status', 'Active')->count();
        $totalHouseholds = Household::count();
        $totalMale       = Resident::where('gender', 'Male')->count();
        $totalFemale     = Resident::where('gender', 'Female')->count();
        $totalVoters     = Resident::residentVoters()->count();
        $totalSeniors    = Resident::active()->where('is_senior', true)->count();
        $totalPwd        = Resident::active()->where('is_pwd', true)->count();
        $totalSoloParent = Resident::active()->where('is_solo_parent', true)->count();
        $total4ps        = Resident::active()->where('is_4ps', true)->count();

        // Derived gender percentages
        $genderTotal = ($totalMale + $totalFemale) ?: 1;
        $malePct     = round(($totalMale / $genderTotal) * 100);
        $femalePct   = 100 - $malePct;

        // Snapshot counts: today & this month
        $todayDocs      = Document::whereDate('created_at', today())->count();
        $todayResidents = Resident::whereDate('created_at', today())->count();
        $todayBlotter   = BlotterCase::whereDate('created_at', today())->count();
        $thisMonthDocs  = Document::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();
        $thisMonthBlt   = BlotterCase::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();
        $thisMonthRes   = Resident::whereYear('created_at', $currentYear)->whereMonth('created_at', $currentMonth)->count();

        // Quick-generate shortcuts
        $quick = [
            ['label' => 'This Month · Summary',   'short' => 'Summary',   'icon' => 'fa-chart-pie',     'type' => 'monthly',   'module' => 'summary',   'month' => $currentMonth, 'year' => $currentYear],
            ['label' => 'This Month · Documents',  'short' => 'Documents', 'icon' => 'fa-file-alt',      'type' => 'monthly',   'module' => 'documents', 'month' => $currentMonth, 'year' => $currentYear],
            ['label' => 'This Month · Blotter',    'short' => 'Blotter',   'icon' => 'fa-gavel',         'type' => 'monthly',   'module' => 'blotter',   'month' => $currentMonth, 'year' => $currentYear],
            ['label' => 'This Quarter · Summary',  'short' => 'Quarterly', 'icon' => 'fa-calendar-week', 'type' => 'quarterly', 'module' => 'summary',   'quarter' => (int) ceil($currentMonth / 3), 'year' => $currentYear],
            ['label' => $currentYear.' Annual',    'short' => 'Annual',    'icon' => 'fa-calendar',      'type' => 'annual',    'module' => 'summary',   'year' => $currentYear],
        ];

        return view('reports.generate', compact(
            'currentYear', 'currentMonth', 'snapshotAt',
            'monthlyDocs', 'monthlyBlotter',
            'docsByType', 'blotterByType', 'puroks',
            'totalResidents', 'activeResidents', 'totalDocs', 'totalBlotter',
            'totalBusinesses', 'totalHouseholds', 'totalMale', 'totalFemale',
            'totalVoters', 'totalSeniors', 'totalPwd', 'totalSoloParent', 'total4ps',
            'genderTotal', 'malePct', 'femalePct',
            'todayDocs', 'todayResidents', 'todayBlotter',
            'thisMonthDocs', 'thisMonthBlt', 'thisMonthRes',
            'quick'
        ));
    }

    public function analytics()
    {
        $totalActive = Resident::where('residency_status', 'Active')->count();
        $totalDeceased = Resident::where('residency_status', 'Deceased')->count();
        $totalTransferred = Resident::where('residency_status', 'Transferred')->count();
        $totalMale = Resident::where('gender', 'Male')->count();
        $totalFemale = Resident::where('gender', 'Female')->count();
        $totalHouseholds = \App\Models\Household::count();
        $totalDocuments = \App\Models\Document::count();
        $totalBlotter = \App\Models\BlotterCase::count();
        $totalVoters = Resident::residentVoters()->count();
        $totalSeniors = Resident::active()->where('is_senior', true)->count();
        $totalPwd = Resident::active()->where('is_pwd', true)->count();
        $totalSoloParent = Resident::active()->where('is_solo_parent', true)->count();
        $total4ps = Resident::active()->where('is_4ps', true)->count();
        $totalBusinesses = \App\Models\Business::count();
        $activeBusinesses = \App\Models\Business::where('status', 'Active')->count();
        $expiredBusinesses = \App\Models\Business::where('status', 'Expired')->count();
        $pendingDocuments = \App\Models\Document::where('status', 'Pending')->count();
        $releasedDocuments = \App\Models\Document::where('status', 'Released')->count();
        $activeBlotter = \App\Models\BlotterCase::where('status', 'Active')->count();
        $settledBlotter = \App\Models\BlotterCase::whereIn('status', ['Settled', 'Closed'])->count();

        $residentsByPurok = \App\Models\Purok::withCount([
            'residents' => fn ($q) => $q->where('residency_status', 'Active'),
        ])->orderByDesc('residents_count')->get();

        $documentsByType = \App\Models\Document::selectRaw('document_type, count(*) as count')
            ->groupBy('document_type')->pluck('count', 'document_type');

        $blotterByType = \App\Models\BlotterCase::selectRaw('incident_type, count(*) as count')
            ->groupBy('incident_type')->pluck('count', 'incident_type');

        $monthlyData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyData[$m] = \App\Models\Document::whereYear('created_at', date('Y'))
                ->whereMonth('created_at', $m)->count();
        }

        $ageGroups = [
            'Children (0-12)' => Resident::whereBetween(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), [0, 12])->count(),
            'Teens (13-17)' => Resident::whereBetween(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), [13, 17])->count(),
            'Adults (18-59)' => Resident::whereBetween(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), [18, 59])->count(),
            'Seniors (60+)' => Resident::where(\DB::raw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE())'), '>=', 60)->count(),
        ];

        return view('reports.reports-index', compact(
            'totalActive', 'totalDeceased', 'totalTransferred',
            'totalMale', 'totalFemale', 'totalHouseholds',
            'totalDocuments', 'totalBlotter', 'totalVoters',
            'totalSeniors', 'totalPwd', 'totalSoloParent', 'total4ps',
            'totalBusinesses', 'activeBusinesses', 'expiredBusinesses',
            'pendingDocuments', 'releasedDocuments', 'activeBlotter', 'settledBlotter',
            'residentsByPurok', 'documentsByType', 'blotterByType',
            'monthlyData', 'ageGroups'
        ));
    }

    public function preview(Request $request)
    {
        $type    = in_array($request->input('report_type'), ['monthly','quarterly','annual']) ? $request->input('report_type') : 'monthly';
        $module  = in_array($request->input('report_module'), ['summary','residents','documents','blotter','businesses']) ? $request->input('report_module') : 'summary';
        $year    = (int) $request->input('year', date('Y'));
        $month   = (int) ($request->input('month') ?: date('n'));
        $quarter = (int) ($request->input('quarter') ?: 1);

        if ($year < 2020 || $year > 2030) $year = (int) date('Y');

        [$start, $end, $periodLabel] = $this->getDateRange($type, $year, $month, $quarter);

        $count = match ($module) {
            'residents'  => Resident::whereBetween('created_at', [$start, $end])->count(),
            'documents'  => Document::whereBetween('created_at', [$start, $end])->count(),
            'blotter'    => BlotterCase::whereBetween('created_at', [$start, $end])->count(),
            'businesses' => Business::whereBetween('created_at', [$start, $end])->count(),
            default      => Document::whereBetween('created_at', [$start, $end])->count()
                          + BlotterCase::whereBetween('created_at', [$start, $end])->count()
                          + Resident::whereBetween('created_at', [$start, $end])->count(),
        };

        $labels = ['summary'=>'records','residents'=>'residents','documents'=>'documents','blotter'=>'blotter cases','businesses'=>'businesses'];

        return response()->json([
            'count'  => $count,
            'module' => $labels[$module] ?? $module,
            'period' => $periodLabel,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:monthly,quarterly,annual',
            'report_module' => 'required|in:summary,residents,documents,blotter,businesses',
            'year' => 'required|integer|min:2020|max:2030',
            'month' => 'nullable|integer|min:1|max:12',
            'quarter' => 'nullable|integer|min:1|max:4',
        ]);

        $type = $request->report_type;
        $module = $request->report_module;
        $year = $request->year;
        $month = $request->month;
        $quarter = $request->quarter;

        // Determine date range
        [$startDate, $endDate, $periodLabel] = $this->getDateRange($type, $year, $month, $quarter);

        // Gather data
        $data = $this->gatherData($module, $startDate, $endDate);
        $generatedAt = now()->format('m/d/Y g:i A');
        $generatedBy = auth()->user()->name;
        $officialName = Official::where('position', 'Punong Barangay')->where('is_active', true)->first()?->full_name ?? 'ROBERT S. ROMANO';
        $secretaryName = Official::where('position', 'Barangay Secretary')->where('is_active', true)->first()?->full_name ?? 'JOSEPHINE A. FLORES';

        $pdf = Pdf::loadView('reports.pdf', compact(
            'type', 'module', 'year', 'periodLabel',
            'startDate', 'endDate', 'data',
            'generatedAt', 'generatedBy', 'officialName', 'secretaryName'
        ))->setPaper('a4', 'portrait');

        $filename = strtolower("{$type}_{$module}_report_{$year}").
            ($type === 'monthly' ? "_{$month}" : ($type === 'quarterly' ? "_q{$quarter}" : '')).
            '.pdf';

        return $pdf->stream($filename);
    }

    private function getDateRange(string $type, int $year, ?int $month, ?int $quarter): array
    {
        switch ($type) {
            case 'monthly':
                $start = Carbon::create($year, $month, 1)->startOfMonth();
                $end = $start->copy()->endOfMonth();
                $label = $start->format('F Y');
                break;
            case 'quarterly':
                $startMonth = (($quarter - 1) * 3) + 1;
                $start = Carbon::create($year, $startMonth, 1)->startOfMonth();
                $end = $start->copy()->addMonths(2)->endOfMonth();
                $label = "Q{$quarter} ".$start->format('M').'–'.$end->format('M Y');
                break;
            case 'annual':
            default:
                $start = Carbon::create($year, 1, 1)->startOfYear();
                $end = $start->copy()->endOfYear();
                $label = "Year {$year}";
                break;
        }

        return [$start, $end, $label];
    }

    private function gatherData(string $module, Carbon $start, Carbon $end): array
    {
        switch ($module) {
            case 'summary':
                return [
                    'total_residents' => Resident::count(),
                    'new_residents' => Resident::whereBetween('created_at', [$start, $end])->count(),
                    'active_residents' => Resident::where('residency_status', 'Active')->count(),
                    'total_households' => Household::count(),
                    'new_households' => Household::whereBetween('created_at', [$start, $end])->count(),
                    'documents_issued' => Document::whereBetween('created_at', [$start, $end])->count(),
                    'documents_released' => Document::whereBetween('released_at', [$start, $end])->count(),
                    'blotter_filed' => BlotterCase::whereBetween('created_at', [$start, $end])->count(),
                    'blotter_settled' => BlotterCase::whereBetween('settled_at', [$start, $end])->count(),
                    'businesses_active' => Business::where('status', 'Active')->count(),
                    'businesses_new' => Business::whereBetween('created_at', [$start, $end])->count(),
                    'voters' => Resident::residentVoters()->count(),
                    'seniors' => Resident::active()->where('is_senior', true)->count(),
                    'pwd' => Resident::active()->where('is_pwd', true)->count(),
                    'total_male' => Resident::where('gender', 'Male')->count(),
                    'total_female' => Resident::where('gender', 'Female')->count(),
                    'docs_by_type' => Document::whereBetween('created_at', [$start, $end])
                        ->selectRaw('document_type, count(*) as total')
                        ->groupBy('document_type')->pluck('total', 'document_type'),
                    'blotter_by_type' => BlotterCase::whereBetween('created_at', [$start, $end])
                        ->selectRaw('incident_type, count(*) as total')
                        ->groupBy('incident_type')->pluck('total', 'incident_type'),
                ];

            case 'residents':
                return [
                    'records' => Resident::with('purok')->whereBetween('created_at', [$start, $end])->orderBy('last_name')->get(),
                    'total' => Resident::whereBetween('created_at', [$start, $end])->count(),
                    'male' => Resident::whereBetween('created_at', [$start, $end])->where('gender', 'Male')->count(),
                    'female' => Resident::whereBetween('created_at', [$start, $end])->where('gender', 'Female')->count(),
                ];

            case 'documents':
                return [
                    'records' => Document::with(['resident', 'issuedBy'])->whereBetween('created_at', [$start, $end])->orderBy('created_at', 'desc')->get(),
                    'total' => Document::whereBetween('created_at', [$start, $end])->count(),
                    'released' => Document::whereBetween('created_at', [$start, $end])->where('status', 'Released')->count(),
                    'pending' => Document::whereBetween('created_at', [$start, $end])->where('status', 'Pending')->count(),
                    'by_type' => Document::whereBetween('created_at', [$start, $end])->selectRaw('document_type, count(*) as total')->groupBy('document_type')->pluck('total', 'document_type'),
                ];

            case 'blotter':
                return [
                    'records' => BlotterCase::with('filedBy')->whereBetween('created_at', [$start, $end])->orderBy('incident_date', 'desc')->get(),
                    'total' => BlotterCase::whereBetween('created_at', [$start, $end])->count(),
                    'active' => BlotterCase::whereBetween('created_at', [$start, $end])->where('status', 'Active')->count(),
                    'settled' => BlotterCase::whereBetween('created_at', [$start, $end])->whereIn('status', ['Settled', 'Closed'])->count(),
                    'by_type' => BlotterCase::whereBetween('created_at', [$start, $end])->selectRaw('incident_type, count(*) as total')->groupBy('incident_type')->pluck('total', 'incident_type'),
                ];

            case 'businesses':
                return [
                    'records' => Business::whereBetween('created_at', [$start, $end])->orderBy('business_name')->get(),
                    'total' => Business::whereBetween('created_at', [$start, $end])->count(),
                    'active' => Business::whereBetween('created_at', [$start, $end])->where('status', 'Active')->count(),
                    'expired' => Business::whereBetween('created_at', [$start, $end])->where('status','Expired')->count(),
                ];

            default:
                return [];
        }
    }
}
