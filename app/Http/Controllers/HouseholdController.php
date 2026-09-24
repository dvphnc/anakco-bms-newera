<?php

namespace App\Http\Controllers;

use App\Enums\ResidencyStatus;
use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use App\Services\HouseholdGroupingService;
use App\Support\AddressNormalizer;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Yajra\DataTables\Facades\DataTables;

class HouseholdController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Household::with(['purok'])
                ->when($request->purok_id, fn ($q) => $q->where('purok_id', $request->purok_id))
                ->when($request->voter === 'yes', fn ($q) => $q->where('is_voter_household', true))
                ->when($request->voter === 'no',  fn ($q) => $q->where('is_voter_household', false))
                ->select('households.*');

            return DataTables::of($query)
                ->addColumn('number_col', fn ($h) => '<span class="td-mono">'.e($h->household_number).'</span>')
                ->addColumn('head_col', fn ($h) => '<div style="font-weight:600;font-size:13.5px">'.e($h->household_head ?? '—').'</div>')
                ->addColumn('purok_col', fn ($h) => '<span class="td-muted">'.e($h->purok->name ?? '—').'</span>')
                ->addColumn('address_col', fn ($h) => '<span class="td-muted" style="max-width:220px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">'.e($h->address).'</span>')
                ->addColumn('size_col', fn ($h) => '<span class="badge badge-navy"><i class="fas fa-user" style="font-size:9px;margin-right:4px"></i>'.($h->family_size ?? 0).'</span>')
                ->addColumn('voter_col', fn ($h) => $h->is_voter_household
                    ? '<span class="badge badge-green">Yes</span>'
                    : '<span class="badge badge-gray">No</span>')
                ->addColumn('actions', function ($h) {
                    $show = route('households.show', $h);
                    $edit = route('households.edit', $h);
                    $delete = route('households.destroy', $h);

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="'.$show.'" class="btn btn-secondary btn-sm btn-icon" title="View"><i class="fas fa-eye"></i></a>
                            <a href="'.$edit.'" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="'.$delete.'">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $s = $request->search['value'];
                        $query->where(fn ($q) => $q
                            ->where('household_number', 'like', "%$s%")
                            ->orWhere('household_head', 'like', "%$s%")
                            ->orWhere('address', 'like', "%$s%"));
                    }
                })
                ->rawColumns(['number_col', 'head_col', 'purok_col', 'address_col', 'size_col', 'voter_col', 'actions'])
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

    // Head, family size and voter-household are derived from the members
    // (HouseholdGroupingService), so the form only takes purok + address.
    public function store(Request $request)
    {
        $validated = $request->validate($this->householdRules(), $this->householdMessages());
        $this->ensureAddressIsFree($validated);

        $validated['household_number'] = Household::nextNumber();
        $validated['family_size'] = 0;
        $record = Household::create($validated);
        $this->logActivity('created', $record);

        return redirect()->route('households.show', $record)
            ->with('success', "Household {$record->household_number} added. Residents registered at this address will join it automatically.");
    }

    private function householdRules(): array
    {
        return [
            'purok_id' => 'required|exists:puroks,id',
            'address'  => 'required|string|max:255',
        ];
    }

    private function householdMessages(): array
    {
        return [
            'purok_id.required' => 'Please select a Purok.',
            'address.required'  => 'Please enter the household\'s address.',
        ];
    }

    // One household per address per purok (also enforced by a unique index)
    private function ensureAddressIsFree(array $validated, ?Household $except = null): void
    {
        $existing = Household::where('purok_id', $validated['purok_id'])
            ->where('address_key', AddressNormalizer::key($validated['address']))
            ->when($except, fn ($q) => $q->whereKeyNot($except->id))
            ->first();

        if ($existing) {
            throw ValidationException::withMessages([
                'address' => "Household {$existing->household_number} is already registered at this address.",
            ]);
        }
    }

    // -------------------------------------------------------
    // MATCH — which household would a resident at this address join?
    // Used by the live hint on the resident form (Task 1.2).
    // -------------------------------------------------------
    public function match(Request $request)
    {
        $data = $request->validate([
            'address'     => 'required|string|max:255',
            'purok_id'    => 'required|integer',
            'resident_id' => 'nullable|integer',
        ]);

        $key = AddressNormalizer::key($data['address']);
        $household = $key === '' ? null : Household::where('purok_id', $data['purok_id'])
            ->where('address_key', $key)
            ->first();

        $currentHouseholdId = ! empty($data['resident_id'])
            ? Resident::whereKey($data['resident_id'])->value('household_id')
            : null;

        return response()->json([
            'key'       => $key,
            'household' => $household ? [
                'number'     => $household->household_number,
                'url'        => route('households.show', $household),
                'members'    => (int) $household->family_size,
                'head'       => $household->household_head,
                'is_current' => $currentHouseholdId === $household->id,
            ] : null,
        ]);
    }

    // -------------------------------------------------------
    // SET HEAD — make a living member the household head
    // -------------------------------------------------------
    public function setHead(Request $request, Household $household, HouseholdGroupingService $grouping)
    {
        $data = $request->validate(['resident_id' => 'required|integer']);

        $resident = $household->residents()
            ->whereKey($data['resident_id'])
            ->where('residency_status', ResidencyStatus::Alive->value)
            ->first();

        if (! $resident) {
            throw ValidationException::withMessages([
                'resident_id' => 'Only a living member of this household can be its head.',
            ]);
        }

        $oldData = $household->getOriginal();
        $grouping->makeHead($household, $resident);
        $this->logActivity('updated', $household, $oldData, $household->fresh()->toArray());

        return back()->with('success', "{$resident->full_name} is now the head of household {$household->household_number}.");
    }

    public function edit(Household $household)
    {
        $puroks = Purok::orderBy('name')->get();

        return view('households.households-edit', compact('household', 'puroks'));
    }

    public function update(Request $request, Household $household)
    {
        $validated = $request->validate($this->householdRules(), $this->householdMessages());
        $this->ensureAddressIsFree($validated, $household);
        $oldData = $household->getOriginal();
        $household->update($validated);
        $this->logActivity('updated', $household, $oldData, $household->fresh()->toArray());

        return redirect()->route('households.index')->with('success', 'Household updated successfully.');
    }

    public function show(Household $household)
    {
        // Living members first, eldest first
        $household->load(['purok', 'residents' => fn ($q) => $q->orderByRaw("residency_status = 'Active' DESC")->orderBy('birthdate')]);

        // Quick-view JSON response for the slide panel
        if (request()->wantsJson()) {
            $members = $household->residents->map(function ($r) use ($household) {
                return [
                    'full_name'         => $r->full_name,
                    'age'               => $r->age ?? '—',
                    'gender'            => $r->gender ?? '—',
                    'is_household_head' => $r->id === $household->head_resident_id,
                    'residency_label'   => $r->residency_label,
                    'is_voter'          => (bool) $r->is_voter,
                    'photo_url'         => $r->photo_path ? asset('storage/' . $r->photo_path) : null,
                    'initials'          => strtoupper(substr($r->first_name, 0, 1) . substr($r->last_name, 0, 1)),
                ];
            });

            return response()->json([
                'household_number'   => $household->household_number,
                'address'            => $household->address,
                'purok'              => $household->purok?->name ?? '—',
                'family_size'        => $household->family_size ?? 0,
                'is_voter_household' => (bool) $household->is_voter_household,
                'members'            => $members,
                'show_url'           => route('households.show', $household),
                'edit_url'           => route('households.edit', $household),
            ]);
        }

        return view('households.households-show', compact('household'));
    }

    public function destroy(Request $request, Household $household)
    {
        $number = $household->household_number;
        $this->logActivity('deleted', $household);
        $household->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Household {$number} has been removed."]);
        }

        return redirect()->route('households.index')->with('success', 'Household deleted successfully.');
    }
}
