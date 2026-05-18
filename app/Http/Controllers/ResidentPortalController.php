<?php

namespace App\Http\Controllers;

use App\Models\AppointmentStatusLog;
use App\Models\BlotterRequest;
use App\Models\BusinessPermitRequest;
use App\Models\DocumentAppointment;
use Illuminate\Http\Request;

class ResidentPortalController extends Controller
{
    public function index()
    {
        return view('portal.index');
    }

    public function create()
    {
        return view('portal.request', [
            'documentTypes' => DocumentAppointment::$documentTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_name'  => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email'          => 'nullable|email|max:255',
            'document_type'  => 'required|string',
            'purpose'        => 'nullable|string|max:500',
            'preferred_date' => 'required|date|after:today',
        ]);

        $validated['appointment_number'] = DocumentAppointment::generateNumber();
        $validated['status']             = 'Pending';

        $validated['source'] = 'portal';
        $appointment = DocumentAppointment::create($validated);

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status'    => null,
            'to_status'      => 'Pending',
            'changed_by'     => 'Resident',
            'note'           => 'Request submitted via Resident Portal.',
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

        $appointment = DocumentAppointment::with('statusLogs')
            ->where('appointment_number', strtoupper(trim($request->appointment_number)))
            ->first();

        return view('portal.track', compact('appointment'));
    }

    /* ─────────────────────────────────────────────────────
     |  BLOTTER REPORT — portal form
     |──────────────────────────────────────────────────── */
    public function blotterForm()
    {
        return view('portal.blotter-request', [
            'incidentTypes' => BlotterRequest::$incidentTypes,
        ]);
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
        ]);

        $validated['request_number'] = BlotterRequest::generateNumber();
        $validated['status']         = 'Pending';

        $blotter = BlotterRequest::create($validated);

        $redirectUrl = route('portal.submitted', ['type' => 'blotter', 'number' => $blotter->request_number]);

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
        return view('portal.business-request', [
            'businessTypes' => BusinessPermitRequest::$businessTypes,
        ]);
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
            'operation_year'   => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'purpose'          => 'nullable|string|max:500',
        ]);

        $validated['request_number'] = BusinessPermitRequest::generateNumber();
        $validated['status']         = 'Pending';

        $business = BusinessPermitRequest::create($validated);

        $redirectUrl = route('portal.submitted', ['type' => 'business', 'number' => $business->request_number]);

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
            'blotter'  => BlotterRequest::where('request_number', $number)->firstOrFail(),
            'business' => BusinessPermitRequest::where('request_number', $number)->firstOrFail(),
            default    => abort(404),
        };

        return view('portal.submitted', compact('type', 'number', 'record'));
    }

    public function trackLookup(Request $request)
    {
        $number      = strtoupper(trim($request->input('number', '')));
        $appointment = $number
            ? DocumentAppointment::where('appointment_number', $number)->first()
            : null;

        if (! $appointment) {
            return response()->json(['found' => false]);
        }

        $steps     = ['Pending', 'Confirmed', 'Processing', 'Ready', 'Released'];
        $stepIndex = array_search($appointment->status, $steps);

        $logs = $appointment->statusLogs->map(fn ($l) => [
            'from'       => $l->from_status,
            'to'         => $l->to_status,
            'by'         => $l->changed_by,
            'note'       => $l->note,
            'date'       => $l->created_at->format('M d, Y'),
            'time'       => $l->created_at->format('g:i A'),
        ]);

        return response()->json([
            'found'              => true,
            'appointment_number' => $appointment->appointment_number,
            'resident_name'      => $appointment->resident_name,
            'document_type'      => $appointment->document_type,
            'preferred_date'     => $appointment->preferred_date->format('F j, Y'),
            'purpose'            => $appointment->purpose,
            'notes'              => $appointment->notes,
            'processed_by'       => $appointment->processed_by,
            'released_at'        => $appointment->released_at?->format('F j, Y g:i A'),
            'created_at'         => $appointment->created_at->format('M d, Y g:i A'),
            'updated_at'         => $appointment->updated_at->format('M d, Y g:i A'),
            'status'             => $appointment->status,
            'cancelled'          => $appointment->status === 'Cancelled',
            'step_index'         => $stepIndex === false ? -1 : (int) $stepIndex,
            'steps'              => $steps,
            'logs'               => $logs,
        ]);
    }
}
