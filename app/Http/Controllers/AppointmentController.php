<?php

namespace App\Http\Controllers;

use App\Models\DocumentAppointment;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        $query = DocumentAppointment::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('document_type')) {
            $query->where('document_type', $request->document_type);
        }
        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($sub) use ($q) {
                $sub->where('resident_name', 'like', "%{$q}%")
                    ->orWhere('appointment_number', 'like', "%{$q}%")
                    ->orWhere('contact_number', 'like', "%{$q}%");
            });
        }

        $appointments  = $query->orderByRaw("FIELD(status,'Pending','Confirmed','Processing','Ready','Released','Cancelled')")
                               ->orderBy('preferred_date')
                               ->paginate(20)
                               ->withQueryString();

        $statuses      = DocumentAppointment::$statuses;
        $documentTypes = DocumentAppointment::$documentTypes;

        return view('appointments.index', compact('appointments', 'statuses', 'documentTypes'));
    }

    public function updateStatus(Request $request, DocumentAppointment $appointment)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', DocumentAppointment::$statuses),
            'notes'  => 'nullable|string|max:500',
        ]);

        $validated['processed_by'] = auth()->user()->name;

        if ($validated['status'] === 'Released') {
            $validated['released_at'] = now();
        }

        $old = $appointment->toArray();
        $appointment->update($validated);

        $this->logActivity('updated', $appointment, $old, $appointment->fresh()->toArray());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => "Status updated to {$validated['status']}.",
                'status'  => $validated['status'],
            ]);
        }

        return back()->with('success', "Appointment status updated to {$validated['status']}.");
    }

    public function destroy(DocumentAppointment $appointment)
    {
        $num = $appointment->appointment_number;
        $appointment->delete();

        return back()->with('success', "Appointment {$num} deleted.");
    }
}
