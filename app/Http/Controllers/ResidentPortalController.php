<?php

namespace App\Http\Controllers;

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
            'preferred_date' => 'required|date|after_or_equal:today',
        ]);

        $validated['appointment_number'] = DocumentAppointment::generateNumber();
        $validated['status']             = 'Pending';

        DocumentAppointment::create($validated);

        return redirect()->route('portal.confirmation', $validated['appointment_number']);
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
}
