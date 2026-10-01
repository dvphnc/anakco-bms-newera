<?php

namespace App\Http\Controllers;

use App\Enums\ResidencyStatus;
use App\Models\Pabahay;
use App\Models\Purok;
use App\Models\Resident;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Part 2.2: Pabahay blocks (housing for ministers' families). Admin only
 * (route middleware: can:manage-religion-data). Not written to the activity log,
 * which Secretaries can read.
 */
class PabahayController extends Controller
{
    public function index()
    {
        $pabahays = Pabahay::with('purok')->withCount('units')->orderByDesc('is_active')->orderBy('name')->get();

        // Living people and occupied units per Pabahay, in one query
        $occupancy = Resident::active()
            ->join('pabahay_units', 'pabahay_units.id', '=', 'residents.pabahay_unit_id')
            ->groupBy('pabahay_units.pabahay_id')
            ->selectRaw('pabahay_units.pabahay_id AS pabahay_id, COUNT(*) AS people, COUNT(DISTINCT residents.pabahay_unit_id) AS occupied')
            ->get()->keyBy('pabahay_id');

        return view('pabahays.pabahays-index', compact('pabahays', 'occupancy'));
    }

    public function create()
    {
        return view('pabahays.pabahays-form', ['pabahay' => new Pabahay(['is_active' => true]), 'puroks' => Purok::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $pabahay = Pabahay::create($this->validated($request));

        return redirect()->route('pabahays.show', $pabahay)
            ->with('success', "“{$pabahay->name}” created. Now add its units below.");
    }

    public function show(Pabahay $pabahay)
    {
        $pabahay->load('purok');

        $units = $pabahay->units()
            ->with(['residents' => fn ($q) => $q->where('residency_status', ResidencyStatus::Alive->value)->orderBy('last_name')->orderBy('first_name')])
            ->get();

        return view('pabahays.pabahays-show', compact('pabahay', 'units'));
    }

    public function edit(Pabahay $pabahay)
    {
        return view('pabahays.pabahays-form', ['pabahay' => $pabahay, 'puroks' => Purok::orderBy('name')->get()]);
    }

    public function update(Request $request, Pabahay $pabahay)
    {
        $data = $this->validated($request, $pabahay);

        if (! $data['is_active'] && $pabahay->is_active && $this->livingCount($pabahay) > 0) {
            return back()->withInput()->with('error', 'People still live in this Pabahay. Move them to another unit first, then turn it off.');
        }

        $pabahay->update($data);

        return redirect()->route('pabahays.show', $pabahay)->with('success', "“{$pabahay->name}” updated.");
    }

    private function validated(Request $request, ?Pabahay $pabahay = null): array
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:100', Rule::unique('pabahays', 'name')->ignore($pabahay?->id)],
            'location' => 'nullable|string|max:255',
            'purok_id' => 'nullable|exists:puroks,id',
            'notes'    => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Please give the Pabahay a name, e.g. "Pabahay A".',
            'name.unique'   => 'There is already a Pabahay with that name.',
        ]);
        $data['is_active'] = $pabahay ? $request->boolean('is_active') : true;

        return $data;
    }

    private function livingCount(Pabahay $pabahay): int
    {
        return Resident::active()
            ->whereIn('pabahay_unit_id', $pabahay->units()->select('pabahay_units.id'))
            ->count();
    }
}
