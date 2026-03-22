<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\ResidentExport;
use App\Exports\HouseholdExport;
use App\Exports\DocumentExport;
use App\Exports\BlotterExport;
use App\Exports\BusinessExport;
use App\Models\Resident;
use App\Models\Household;
use App\Models\Document;
use App\Models\BlotterCase;
use App\Models\Business;

class ExportController extends Controller
{
    // -------------------------------------------------------
    // EXCEL EXPORTS
    // -------------------------------------------------------
    public function excel(Request $request, string $module)
    {
        $filters = $request->only(['status', 'gender', 'purok_id', 'document_type', 'incident_type', 'business_type']);
        $date    = now()->format('Y-m-d');

        return match($module) {
            'residents'  => Excel::download(new ResidentExport($filters), "residents-{$date}.xlsx"),
            'households' => Excel::download(new HouseholdExport(),         "households-{$date}.xlsx"),
            'documents'  => Excel::download(new DocumentExport($filters),  "documents-{$date}.xlsx"),
            'blotter'    => Excel::download(new BlotterExport($filters),   "blotter-cases-{$date}.xlsx"),
            'businesses' => Excel::download(new BusinessExport($filters),  "businesses-{$date}.xlsx"),
            default      => abort(404),
        };
    }

    // -------------------------------------------------------
    // PDF EXPORTS
    // -------------------------------------------------------
    public function pdf(Request $request, string $module)
    {
        $filters = $request->only(['status', 'gender', 'purok_id', 'document_type', 'incident_type', 'business_type']);
        $date    = now()->format('Y-m-d');
        $generatedAt = now()->format('F d, Y \a\t h:i A');
        $generatedBy = auth()->user()->name;
        $officialName = \App\Models\Official::where('position','Punong Barangay')
            ->where('is_active', true)->first()?->full_name ?? 'PUNONG BARANGAY';

        switch ($module) {
            case 'residents':
                $data = Resident::with(['purok'])
                    ->when($filters['gender'] ?? null, fn($q, $v) => $q->where('gender', $v))
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('residency_status', $v))
                    ->when($filters['purok_id'] ?? null, fn($q, $v) => $q->where('purok_id', $v))
                    ->orderBy('last_name')->get();
                $pdf = Pdf::loadView('exports.pdf.residents', compact('data', 'generatedAt', 'generatedBy', 'officialName', 'filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("residents-{$date}.pdf");

            case 'households':
                $data = Household::with(['purok', 'residents'])->orderBy('household_number')->get();
                $pdf = Pdf::loadView('exports.pdf.households', compact('data', 'generatedAt', 'generatedBy', 'officialName'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("households-{$date}.pdf");

            case 'documents':
                $data = Document::with(['resident', 'issuedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->when($filters['document_type'] ?? null, fn($q, $v) => $q->where('document_type', $v))
                    ->orderBy('created_at', 'desc')->get();
                $pdf = Pdf::loadView('exports.pdf.documents', compact('data', 'generatedAt', 'generatedBy', 'officialName', 'filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("documents-{$date}.pdf");

            case 'blotter':
                $data = BlotterCase::with(['filedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->when($filters['incident_type'] ?? null, fn($q, $v) => $q->where('incident_type', $v))
                    ->orderBy('incident_date', 'desc')->get();
                $pdf = Pdf::loadView('exports.pdf.blotter', compact('data', 'generatedAt', 'generatedBy', 'officialName', 'filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("blotter-cases-{$date}.pdf");

            case 'businesses':
                $data = Business::with(['issuedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->orderBy('business_name')->get();
                $pdf = Pdf::loadView('exports.pdf.businesses', compact('data', 'generatedAt', 'generatedBy', 'officialName', 'filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("businesses-{$date}.pdf");

            default:
                abort(404);
        }
    }
}