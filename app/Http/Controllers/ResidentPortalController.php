<?php

namespace App\Http\Controllers;

use App\Models\AppointmentStatusLog;
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

        $appointment = DocumentAppointment::create($validated);

        AppointmentStatusLog::create([
            'appointment_id' => $appointment->id,
            'from_status'    => null,
            'to_status'      => 'Pending',
            'changed_by'     => 'Resident',
            'note'           => 'Request submitted via Resident Portal.',
        ]);

        return redirect()->route('portal.confirmation', $appointment->appointment_number);
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

        $appointment = DocumentAppointment::where(
            'appointment_number',
            strtoupper(trim($request->appointment_number))
        )->first();

        return view('portal.track', compact('appointment'));
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
