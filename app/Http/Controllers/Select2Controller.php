<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Resident;

class Select2Controller extends Controller
{
    public function residents(Request $request)
    {
        $q = trim($request->get('q', ''));
        $selected = $request->get('id');

        // If fetching a specific selected value
        if ($selected && !$q) {
            $resident = Resident::find($selected);
            if ($resident) {
                return response()->json([
                    'results' => [[
                        'id'   => $resident->id,
                        'text' => $resident->last_name . ', ' . $resident->first_name . ($resident->middle_name ? ' ' . substr($resident->middle_name, 0, 1) . '.' : '') . ' — ' . $resident->address,
                    ]]
                ]);
            }
        }

        $residents = Resident::where('residency_status', 'Active')
            ->where(function($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                      ->orWhere('last_name', 'like', "%{$q}%")
                      ->orWhereRaw("CONCAT(last_name, ', ', first_name) like ?", ["%{$q}%"])
                      ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$q}%"])
                      ->orWhere('contact_number', 'like', "%{$q}%");
            })
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->limit(30)
            ->get()
            ->map(fn($r) => [
                'id'   => $r->id,
                'text' => $r->last_name . ', ' . $r->first_name
                        . ($r->middle_name ? ' ' . substr($r->middle_name, 0, 1) . '.' : '')
                        . ' — ' . $r->address,
            ]);

        return response()->json(['results' => $residents]);
    }
}