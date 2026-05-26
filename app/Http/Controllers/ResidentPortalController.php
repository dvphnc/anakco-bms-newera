<?php

namespace App\Http\Controllers;

use App\Models\AppointmentStatusLog;
use App\Models\BlotterCase;
use App\Models\Business;
use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Services\DocumentQueueService;
use Illuminate\Http\Request;

class ResidentPortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function about()
    {
        return view('portal.about');
    }

    public function create()
    {
        return view('portal.request', [
            'documentTypes' => DocumentAppointment::$documentTypes,
        ]);
    }

    public function store(Request $request)
    {
        $isRep = $request->boolean('is_representative');

        $validated = $request->validate([
            'resident_name'          => 'required|string|max:255',
            'contact_number'         => 'required|string|max:20',
            'email'                  => 'nullable|email|max:255',
            'document_type'          => 'required|string',
            'purpose'                => 'nullable|string|max:500',
            'preferred_date'         => 'required|date|after:today',
            'requestor_name'         => $isRep ? 'required|string|max:255' : 'nullable|string|max:255',
            'requestor_relationship' => $isRep ? 'required|string|max:100' : 'nullable|string|max:100',
            'requestor_contact'      => 'nullable|string|max:255',
        ]);

        $validated['appointment_number'] = DocumentAppointment::generateNumber();
        $validated['status']             = 'Pending';
        $validated['source']             = 'portal';

        // Clear requestor fields if not a representative submission
        if (! $isRep) {
            $validated['requestor_name']         = null;
            $validated['requestor_relationship'] = null;
            $validated['requestor_contact']      = null;
        }

        $appointment = DocumentAppointment::create($validated);

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status'    => null,
            'to_status'      => 'Pending',
            'changed_by'     => 'Resident',
            'note'           => 'Request submitted via Resident Portal.',
        ]);

        // Fire submission confirmation email (Step 1 of the 4-email lifecycle)
        app(DocumentQueueService::class)->sendSubmissionConfirmation($appointment);

        // Create the mirrored Document record so this submission is visible
        // in the Document Issuance module from day one. The service keeps
        // it in sync as the appointment status advances.
        Document::create([
            'appointment_id'       => $appointment->id,
            'source'               => 'portal',
            'resident_id'          => null,
            'resident_name_portal' => $appointment->resident_name,
            'document_type'        => $appointment->document_type,
            'purpose'              => $appointment->purpose ?? 'Portal Request',
            'status'               => 'Pending',
            'doc_number'           => Document::generateDocNumber(),
        ]);

        $redirectUrl = route('portal.confirmation', $appointment->appointment_number);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    public function confirmation(string $number)
    {
        $appointment = DocumentAppointment::where('appointment_number', $number)->firstOrFail();

        return view('portal.confirmation', compact('appointment'));
    }

    public function trackForm()
    {
        return view('portal.track');
    }

    public function track(Request $request)
    {
        $request->validate([
            'appointment_number' => 'required|string',
        ]);

        $number = strtoupper(trim($request->appointment_number));

        [$type, $record] = $this->resolveTrackRecord($number);

        return view('portal.track', compact('type', 'record', 'number'));
    }

    /* Resolve a reference number to its record type + model */
    private function resolveTrackRecord(string $number): array
    {
        $doc = DocumentAppointment::with('statusLogs')
            ->where('appointment_number', $number)->first();
        if ($doc) return ['document', $doc];

        $biz = Business::where('permit_number', $number)->where('source', 'portal')->first();
        if ($biz) return ['business', $biz];

        $blotter = BlotterCase::where('case_number', $number)->where('source', 'portal')->first();
        if ($blotter) return ['blotter', $blotter];

        return [null, null];
    }

    /* ─────────────────────────────────────────────────────
     |  BLOTTER REPORT — portal form
     |──────────────────────────────────────────────────── */
    public function blotterForm()
    {
        $incidentTypes = ['Noise Complaint', 'Physical Assault', 'Verbal Abuse', 'Theft',
                          'Trespassing', 'Domestic Dispute', 'Property Damage', 'Threat', 'Other'];
        return view('portal.blotter-request', compact('incidentTypes'));
    }

    public function storeBlotter(Request $request)
    {
        $validated = $request->validate([
            'complainant_name'     => 'required|string|max:255',
            'contact_number'       => 'required|string|max:20',
            'email'                => 'nullable|email|max:255',
            'address'              => 'required|string|max:500',
            'incident_type'        => 'required|string',
            'incident_date'        => 'required|date|before_or_equal:today',
            'incident_location'    => 'required|string|max:500',
            'incident_description' => 'required|string|max:2000',
            'respondent_name'      => 'nullable|string|max:255',
            'attachment'           => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:5120',
        ]);

        // Handle optional file attachment
        $filePath         = null;
        $fileOriginalName = null;
        if ($request->hasFile('attachment') && $request->file('attachment')->isValid()) {
            $file             = $request->file('attachment');
            $filePath         = $file->store('blotter_attachments', 'public');
            $fileOriginalName = $file->getClientOriginalName();
        }

        $case = BlotterCase::create([
            'case_number'          => BlotterCase::generateCaseNumber(),
            'source'               => 'portal',
            'incident_type'        => $validated['incident_type'],
            'incident_date'        => $validated['incident_date'],
            'incident_location'    => $validated['incident_location'],
            'incident_details'     => $validated['incident_description'],
            'complainant_name'     => $validated['complainant_name'],
            'complainant_address'  => $validated['address'],
            'complainant_contact'  => $validated['contact_number'],
            'email'                => $validated['email'] ?? null,
            'respondent_name'      => $validated['respondent_name'] ?? null,
            'status'               => 'Pending',
            'file_path'            => $filePath,
            'file_original_name'   => $fileOriginalName,
        ]);

        $redirectUrl = route('portal.submitted', ['type' => 'blotter', 'number' => $case->case_number]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    /* ─────────────────────────────────────────────────────
     |  BUSINESS PERMIT REQUEST — portal form
     |──────────────────────────────────────────────────── */
    public function businessForm()
    {
        $businessTypes = ['Sari-Sari Store', 'Restaurant / Carinderia', 'Salon / Barbershop',
                          'Repair Shop', 'Pharmacy / Drugstore', 'Laundry', 'Printing / Photocopy',
                          'Retail Store', 'Online Selling / E-commerce', 'Other'];
        return view('portal.business-request', compact('businessTypes'));
    }

    public function storeBusiness(Request $request)
    {
        $validated = $request->validate([
            'owner_name'       => 'required|string|max:255',
            'contact_number'   => 'required|string|max:20',
            'email'            => 'nullable|email|max:255',
            'business_name'    => 'required|string|max:255',
            'business_type'    => 'required|string',
            'business_address' => 'required|string|max:500',
            'purpose'          => 'nullable|string|max:500',
            'preferred_date'   => 'required|date|after:today',
        ]);

        $business = Business::create([
            'permit_number'    => Business::generatePermitNumber(),
            'source'           => 'portal',
            'owner_name'       => $validated['owner_name'],
            'owner_contact'    => $validated['contact_number'],
            'email'            => $validated['email'] ?? null,
            'business_name'    => $validated['business_name'],
            'business_type'    => $validated['business_type'],
            'business_address' => $validated['business_address'],
            'preferred_date'   => $validated['preferred_date'],
            'status'           => 'Pending',
        ]);

        $redirectUrl = route('portal.submitted', ['type' => 'business', 'number' => $business->permit_number]);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['redirect' => $redirectUrl]);
        }

        return redirect($redirectUrl);
    }

    /* ─────────────────────────────────────────────────────
     |  GENERIC SUBMITTED CONFIRMATION
     |──────────────────────────────────────────────────── */
    public function submitted(string $type, string $number)
    {
        $record = match($type) {
            'blotter'  => BlotterCase::where('case_number', $number)->firstOrFail(),
            'business' => Business::where('permit_number', $number)->firstOrFail(),
            default    => abort(404),
        };

        return view('portal.submitted', compact('type', 'number', 'record'));
    }

    /* ─────────────────────────────────────────────────────
     |  MY SUBMISSIONS — lookup by contact number
     |──────────────────────────────────────────────────── */
    public function submissions(Request $request)
    {
        $contact      = trim($request->input('contact', ''));
        $blotterCases = collect();
        $businesses   = collect();

        if ($contact) {
            $blotterCases = BlotterCase::where('source', 'portal')
                ->where('complainant_contact', $contact)
                ->orderByDesc('created_at')
                ->get();

            $businesses = Business::where('source', 'portal')
                ->where('owner_contact', $contact)
                ->orderByDesc('created_at')
                ->get();
        }

        return view('portal.submissions', compact('blotterCases', 'businesses', 'contact'));
    }

    public function trackLookup(Request $request)
    {
        $number = strtoupper(trim($request->input('number', '')));
        if (! $number) return response()->json(['found' => false]);

        [$type, $record] = $this->resolveTrackRecord($number);

        if (! $record) return response()->json(['found' => false]);

        return response()->json(match ($type) {
            'document' => $this->trackDocumentPayload($record),
            'business' => $this->trackBusinessPayload($record),
            'blotter'  => $this->trackBlotterPayload($record),
        });
    }

    private function trackDocumentPayload(DocumentAppointment $a): array
    {
        $steps = ['Pending', 'Processing', 'Ready', 'Released'];

        if ($a->status === 'Cancelled') {
            // Find which step the request was at when it was cancelled
            // by looking at the from_status of the most recent log entry
            $cancelLog  = $a->statusLogs->last();
            $fromStatus = $cancelLog?->from_status;
            $prevIdx    = $fromStatus ? array_search($fromStatus, $steps) : false;
            $stepIndex  = $prevIdx !== false ? (int) $prevIdx : 0;
        } else {
            $stepIndex = array_search($a->status, $steps);
        }

        $logs = $a->statusLogs->map(fn ($l) => [
            'from' => $l->from_status,
            'to'   => $l->to_status,
            'by'   => $l->changed_by,
            'note' => $l->note,
            'date' => $l->created_at->format('m/d/Y'),
            'time' => $l->created_at->format('g:i A'),
        ]);

        return [
            'found'              => true,
            'type'               => 'document',
            'reference_number'   => $a->appointment_number,
            'resident_name'      => $a->resident_name,
            'document_type'      => $a->document_type,
            'preferred_date'         => $a->preferred_date?->format('m/d/Y'),
            'pickup_date'            => $a->pickup_date?->format('m/d/Y'),
            'purpose'                => $a->purpose,
            'notes'                  => $a->notes,
            'processed_by'           => $a->processed_by,
            'released_at'            => $a->released_at?->format('m/d/Y g:i A'),
            'requestor_name'         => $a->requestor_name,
            'requestor_relationship' => $a->requestor_relationship,
            'requestor_contact'      => $a->requestor_contact,
            'created_at'         => $a->created_at->format('m/d/Y g:i A'),
            'updated_at'         => $a->updated_at->format('m/d/Y g:i A'),
            'status'             => $a->status,
            'cancelled'          => $a->status === 'Cancelled',
            'step_index'         => $stepIndex === false ? -1 : (int) $stepIndex,
            'steps'              => $steps,
            'logs'               => $logs,
        ];
    }

    private function trackBusinessPayload(Business $b): array
    {
        $steps     = ['Pending', 'For Review', 'Active'];
        $stepIndex = array_search($b->status, $steps);

        return [
            'found'            => true,
            'type'             => 'business',
            'reference_number' => $b->permit_number,
            'owner_name'       => $b->owner_name,
            'business_name'    => $b->business_name,
            'business_type'    => $b->business_type,
            'business_address' => $b->business_address,
            'appointment_date' => $b->preferred_date?->format('m/d/Y'),
            'permit_date'      => $b->permit_date?->format('m/d/Y'),
            'expiry_date'      => $b->expiry_date?->format('m/d/Y'),
            'created_at'       => $b->created_at->format('m/d/Y g:i A'),
            'updated_at'       => $b->updated_at->format('m/d/Y g:i A'),
            'status'           => $b->status,
            'cancelled'        => $b->status === 'Cancelled',
            'step_index'       => $stepIndex === false ? -1 : (int) $stepIndex,
            'steps'            => $steps,
            'logs'             => [],
        ];
    }

    private function trackBlotterPayload(BlotterCase $c): array
    {
        $steps     = ['Pending', 'Active', 'Settled'];
        $stepIndex = array_search($c->status, $steps);
        // Treat all closure-type statuses as past "Settled"
        if (in_array($c->status, ['Closed', 'Referred to Higher Authority', 'Mediated'])) {
            $stepIndex = 2;
        }

        return [
            'found'              => true,
            'type'               => 'blotter',
            'reference_number'   => $c->case_number,
            'complainant_name'   => $c->complainant_name,
            'incident_type'      => $c->incident_type,
            'incident_date'      => $c->incident_date?->format('m/d/Y'),
            'incident_location'  => $c->incident_location,
            'respondent_name'    => $c->respondent_name,
            'resolution_notes'   => $c->resolution_notes,
            'created_at'         => $c->created_at->format('m/d/Y g:i A'),
            'updated_at'         => $c->updated_at->format('m/d/Y g:i A'),
            'status'             => $c->status,
            'cancelled'          => $c->status === 'Closed',
            'step_index'         => $stepIndex === false ? 0 : (int) $stepIndex,
            'steps'              => $steps,
            'logs'               => [],
        ];
    }
}
