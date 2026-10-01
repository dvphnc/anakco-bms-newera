<?php

namespace App\Http\Controllers;

use App\Enums\ResidencyStatus;
use App\Models\Religion;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Part 2.1: the religion list. Admin only (route middleware: can:manage-religion-data).
 *
 * Changes here are not written to the activity log on purpose: the log is visible to
 * Secretaries, and religion data is Admin-only.
 */
class ReligionController extends Controller
{
    public function index()
    {
        $alive = ResidencyStatus::Alive->value;

        $religions = Religion::withCount(['residents as living_count' => fn ($q) => $q->where('residency_status', $alive)])
            ->orderByDesc('is_inc')->orderByDesc('is_active')->orderBy('name')
            ->get();

        // The three groups used by the sidebar filter (living residents only)
        $summary = [
            'inc'        => Resident::active()->religionGroup('inc')->count(),
            'non_inc'    => Resident::active()->religionGroup('non_inc')->count(),
            'unrecorded' => Resident::active()->religionGroup('unrecorded')->count(),
        ];

        return view('religions.religions-index', compact('religions', 'summary'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100', Rule::unique('religions', 'name')],
        ], [
            'name.required' => 'Please type the name of the religion.',
            'name.unique'   => 'That religion is already in the list.',
        ]);

        $religion = Religion::create(['name' => trim($data['name'])]);

        return redirect()->route('religions.index')->with('success', "“{$religion->name}” added to the list.");
    }

    // One endpoint for both actions on a row: rename it, or switch it on / off
    public function update(Request $request, Religion $religion)
    {
        $data = $request->validate([
            'name'      => ['sometimes', 'required', 'string', 'max:100', Rule::unique('religions', 'name')->ignore($religion->id)],
            'is_active' => ['sometimes', 'boolean'],
        ], [
            'name.required' => 'The name cannot be empty.',
            'name.unique'   => 'That religion is already in the list.',
        ]);

        // INC drives the INC / Non-INC filter, so it must always stay available
        if ($religion->is_inc && array_key_exists('is_active', $data) && ! $data['is_active']) {
            return back()->with('error', 'Iglesia ni Cristo cannot be turned off, because the INC / Non-INC filter depends on it.');
        }

        $religion->update(isset($data['name']) ? ['name' => trim($data['name'])] + $data : $data);

        $message = array_key_exists('is_active', $data)
            ? "“{$religion->name}” is now ".($religion->is_active ? 'available' : 'hidden from the form').'. Residents who already have it keep it.'
            : "Renamed to “{$religion->name}”.";

        return redirect()->route('religions.index')->with('success', $message);
    }
}
