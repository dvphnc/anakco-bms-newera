<?php

namespace App\Http\Controllers;

use App\Models\Household;
use App\Traits\LogsActivity;
use App\Models\Purok;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Traits\LogsActivity;

class HouseholdController extends Controller
{
    use LogsActivity;
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Household::with(['purok'])
                ->when($request->purok_id, fn($q) => $q->where('purok_id', $request->purok_id))
                ->select('households.*');

            return DataTables::of($query)
                ->addColumn('number_col', fn($h) => '<span class="td-mono">' . e($h->household_number) . '</span>')
                ->addColumn('head_col', fn($h) => '<div style="font-weight:600;font-size:13.5px">' . e($h->household_head ?? '—') . '</div>')
                ->addColumn('purok_col', fn($h) => '<span class="td-muted">' . e($h->purok->name ?? '—') . '</span>')
                ->addColumn('address_col', fn($h) => '<span class="td-muted" style="max-width:220px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">' . e($h->address) . '</span>')
                ->addColumn('size_col', fn($h) => '<span class="badge badge-navy"><i class="fas fa-user" style="font-size:9px;margin-right:4px"></i>' . ($h->family_size ?? 0) . '</span>')
                ->addColumn('voter_col', fn($h) => $h->is_voter_household
                    ? '<span class="badge badge-green">Yes</span>'
                    : '<span class="badge badge-gray">No</span>')
                ->addColumn('actions', function ($h) {
                    $show   = route('households.show', $h);
                    $edit   = route('households.edit', $h);
                    $delete = route('households.destroy', $h);
                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="' . $show . '" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a>
                            <a href="' . $edit . '" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="' . $delete . '" onsubmit="return confirm(\'Delete this household?\')">
                                <input type="hidden" name="_token" value="' . csrf_token() . '">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $s = $request->search['value'];
                        $query->where(fn($q) => $q
                            ->where('household_number', 'like', "%$s%")
                            ->orWhere('household_head', 'like', "%$s%")
                            ->orWhere('address', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col','head_col','purok_col','address_col','size_col','voter_col','actions'])
                ->make(true);
        }

        $puroks = Purok::orderBy('name')->get();
        return view('households.households-index', compact('puroks'));
    }

    public function create()
    {
        $puroks = Purok::orderBy('name')->get();
        return view('households.households-create', compact('puroks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'purok_id'           => 'required|exists:puroks,id',
            'address'            => 'required|string|max:255',
            'household_head'     => 'nullable|string|max:255',
            'family_size'        => 'required|integer|min:1',
            'is_voter_household' => 'boolean',
        ]);
        $validated['household_number'] = $this->generateHouseholdNumber();
        $validated['is_voter_household'] = $request->boolean('is_voter_household');
        $record = Household::create($validated);
        $this->logActivity('created', $record);
        return redirect()->route('households.index')->with('success', 'Household added successfully.');
    }

    public function show(Household $household)
    {
        $household->load(['purok', 'residents']);
        return view('households.households-show', compact('household'));
    }

    public function edit(Household $household)
    {
        $puroks = Purok::orderBy('name')->get();
        return view('households.households-edit', compact('household', 'puroks'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate([
            'purok_id'           => 'required|exists:puroks,id',
            'address'            => 'required|string|max:255',
            'household_head'     => 'nullable|string|max:255',
            'family_size'        => 'required|integer|min:1',
            'is_voter_household' => 'boolean',
        ]);
        $validated['is_voter_household'] = $request->boolean('is_voter_household');
        $oldData = $household->getOriginal();
        $household->update($validated);
        $this->logActivity('updated', $household, $oldData, $household->fresh()->toArray());
        return redirect()->route('households.index')->with('success', 'Household updated successfully.');
    }

    public function destroy(Household $household)
    {
        $this->logActivity('deleted', $household);
        $household->delete();
        return redirect()->route('households.index')->with('success', 'Household deleted successfully.');
    }

    private function generateHouseholdNumber(): string
    {
        $year  = date('Y');
        $count = Household::whereYear('created_at', $year)->count() + 1;
        return 'HH-' . $year . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}