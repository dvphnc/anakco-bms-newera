<?php

namespace App\Http\Controllers;

use App\Models\Purok;
use App\Models\Resident;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;

class PurokController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $puroks = Purok::with(['leader', 'residents'])
            ->withCount('residents')
            ->orderBy('name')
            ->get();

        return view('puroks.puroks-index', compact('puroks'));
    }

    public function edit(Purok $purok)
    {
        $purok->load(['leader', 'residents']);
        $residents = Resident::where('purok_id', $purok->id)
            ->where('residency_status', 'Active')
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        return view('puroks.puroks-edit', compact('purok', 'residents'));
    }

    public function update(Request $request, Purok $purok)
    {
        $validated = $request->validate([
            'description' => 'nullable|string|max:500',
            'leader_id' => 'nullable|exists:residents,id',
        ]);

        $oldData = $purok->getOriginal();
        $purok->update($validated);
        $this->logActivity('updated', $purok, $oldData, $purok->fresh()->toArray());

        return redirect()
            ->route('puroks.index')
            ->with('success', "Purok {$purok->name} updated successfully.");
    }
}
