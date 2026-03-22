<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Barryvdh\DomPDF\Facade\Pdf;
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

        switch ($module) {

            case 'residents':
                $filename = "residents-{$date}.xlsx";
                $data = Resident::with(['purok'])
                    ->when($filters['gender'] ?? null, fn($q, $v) => $q->where('gender', $v))
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('residency_status', $v))
                    ->when($filters['purok_id'] ?? null, fn($q, $v) => $q->where('purok_id', $v))
                    ->orderBy('last_name')->orderBy('first_name')->get();

                $writer = SimpleExcelWriter::streamDownload($filename);
                $writer->addHeader(['#','Last Name','First Name','Middle Name','Suffix','Gender','Birthdate','Age','Civil Status','Purok','Address','Contact','Email','Voter','Senior','PWD','Solo Parent','4Ps','Status','Date Registered']);
                foreach ($data as $i => $r) {
                    $writer->addRow([$i+1, $r->last_name, $r->first_name, $r->middle_name??'—', $r->suffix??'—', $r->gender, $r->birthdate?->format('M d, Y')??'—', $r->age??'—', $r->civil_status??'—', $r->purok->name??'—', $r->address, $r->contact_number??'—', $r->email_address??'—', $r->is_voter?'Yes':'No', $r->is_senior?'Yes':'No', $r->is_pwd?'Yes':'No', $r->is_solo_parent?'Yes':'No', $r->is_4ps?'Yes':'No', $r->residency_status, $r->created_at->format('M d, Y')]);
                }
                return $writer->toBrowser();

            case 'households':
                $filename = "households-{$date}.xlsx";
                $data = Household::with(['purok','residents'])->orderBy('household_number')->get();

                $writer = SimpleExcelWriter::streamDownload($filename);
                $writer->addHeader(['#','Household No.','Household Head','Purok','Address','Family Size','Members','Voter HH','Date Registered']);
                foreach ($data as $i => $h) {
                    $writer->addRow([$i+1, $h->household_number, $h->household_head??'—', $h->purok->name??'—', $h->address, $h->family_size??'—', $h->residents->count(), $h->is_voter_household?'Yes':'No', $h->created_at->format('M d, Y')]);
                }
                return $writer->toBrowser();

            case 'documents':
                $filename = "documents-{$date}.xlsx";
                $data = Document::with(['resident','issuedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->when($filters['document_type'] ?? null, fn($q, $v) => $q->where('document_type', $v))
                    ->orderBy('created_at', 'desc')->get();

                $writer = SimpleExcelWriter::streamDownload($filename);
                $writer->addHeader(['#','Doc Number','Document Type','Resident','Purpose','Status','Fee Paid','OR Number','Issued By','Date Requested','Date Released']);
                foreach ($data as $i => $d) {
                    $writer->addRow([$i+1, $d->doc_number, $d->document_type, $d->resident->full_name??'—', $d->purpose??'—', $d->status, $d->fee_paid>0?'₱'.number_format($d->fee_paid,2):'Free', $d->or_number??'—', $d->issuedBy->name??'—', $d->created_at->format('M d, Y'), $d->released_at?->format('M d, Y')??'—']);
                }
                return $writer->toBrowser();

            case 'blotter':
                $filename = "blotter-cases-{$date}.xlsx";
                $data = BlotterCase::with(['filedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->when($filters['incident_type'] ?? null, fn($q, $v) => $q->where('incident_type', $v))
                    ->orderBy('incident_date', 'desc')->get();

                $writer = SimpleExcelWriter::streamDownload($filename);
                $writer->addHeader(['#','Case Number','Incident Type','Incident Date','Location','Complainant','Respondent','Status','Filed By','Date Filed','Settled On']);
                foreach ($data as $i => $b) {
                    $writer->addRow([$i+1, $b->case_number, $b->incident_type, $b->incident_date?\Carbon\Carbon::parse($b->incident_date)->format('M d, Y'):'—', $b->incident_location??'—', $b->complainant_name??'—', $b->respondent_name??'—', $b->status, $b->filedBy->name??'—', $b->created_at->format('M d, Y'), $b->settled_at?->format('M d, Y')??'—']);
                }
                return $writer->toBrowser();

            case 'businesses':
                $filename = "businesses-{$date}.xlsx";
                $data = Business::with(['issuedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->orderBy('business_name')->get();

                $writer = SimpleExcelWriter::streamDownload($filename);
                $writer->addHeader(['#','Permit No.','Business Name','Type','Owner','Contact','Address','Permit Date','Expiry Date','Status']);
                foreach ($data as $i => $b) {
                    $writer->addRow([$i+1, $b->permit_number, $b->business_name, $b->business_type, $b->owner_name, $b->owner_contact??'—', $b->business_address, $b->permit_date?\Carbon\Carbon::parse($b->permit_date)->format('M d, Y'):'—', $b->expiry_date?\Carbon\Carbon::parse($b->expiry_date)->format('M d, Y'):'—', $b->status]);
                }
                return $writer->toBrowser();

            default:
                abort(404);
        }
    }

    // -------------------------------------------------------
    // PDF EXPORTS
    // -------------------------------------------------------
    public function pdf(Request $request, string $module)
    {
        $filters     = $request->only(['status', 'gender', 'purok_id', 'document_type', 'incident_type', 'business_type']);
        $date        = now()->format('Y-m-d');
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
                $pdf = Pdf::loadView('exports.pdf.residents', compact('data','generatedAt','generatedBy','officialName','filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("residents-{$date}.pdf");

            case 'households':
                $data = Household::with(['purok','residents'])->orderBy('household_number')->get();
                $pdf = Pdf::loadView('exports.pdf.households', compact('data','generatedAt','generatedBy','officialName'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("households-{$date}.pdf");

            case 'documents':
                $data = Document::with(['resident','issuedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->when($filters['document_type'] ?? null, fn($q, $v) => $q->where('document_type', $v))
                    ->orderBy('created_at','desc')->get();
                $pdf = Pdf::loadView('exports.pdf.documents', compact('data','generatedAt','generatedBy','officialName','filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("documents-{$date}.pdf");

            case 'blotter':
                $data = BlotterCase::with(['filedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->when($filters['incident_type'] ?? null, fn($q, $v) => $q->where('incident_type', $v))
                    ->orderBy('incident_date','desc')->get();
                $pdf = Pdf::loadView('exports.pdf.blotter', compact('data','generatedAt','generatedBy','officialName','filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("blotter-cases-{$date}.pdf");

            case 'businesses':
                $data = Business::with(['issuedBy'])
                    ->when($filters['status'] ?? null, fn($q, $v) => $q->where('status', $v))
                    ->orderBy('business_name')->get();
                $pdf = Pdf::loadView('exports.pdf.businesses', compact('data','generatedAt','generatedBy','officialName','filters'))
                    ->setPaper('a4', 'landscape');
                return $pdf->download("businesses-{$date}.pdf");

            default:
                abort(404);
        }
    }
}