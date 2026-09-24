<?php

namespace App\Http\Controllers;

use App\Enums\ResidencyStatus;
use App\Models\Household;
use App\Models\Purok;
use App\Models\Resident;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ResidentController extends Controller
{
    use LogsActivity;

    // -------------------------------------------------------
    // INDEX — List all residents
    // -------------------------------------------------------
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Resident::with(['purok'])
                ->when($request->gender, fn ($q) => $q->where('gender', $request->gender))
                // 'all' = every status; the list itself defaults to Alive client-side
                ->when($request->status && $request->status !== 'all', fn ($q) => $q->where('residency_status', $request->status))
                ->when($request->purok_id, fn ($q) => $q->where('purok_id', $request->purok_id))
                ->when($request->civil_status, fn ($q) => $q->where('civil_status', $request->civil_status))
                ->when($request->age_min, fn ($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) >= ?', [(int) $request->age_min]))
                ->when($request->age_max, fn ($q) => $q->whereRaw('TIMESTAMPDIFF(YEAR, birthdate, CURDATE()) <= ?', [(int) $request->age_max]))
                ->when($request->tags, function ($q) use ($request) {
                    $tags = (array) $request->tags;
                    $q->where(function ($sub) use ($tags) {
                        foreach ($tags as $tag) {
                            match ($tag) {
                                'voter'       => $sub->orWhere('is_voter', true),
                                'senior'      => $sub->orWhere('is_senior', true),
                                'pwd'         => $sub->orWhere('is_pwd', true),
                                'solo_parent' => $sub->orWhere('is_solo_parent', true),
                                '4ps'         => $sub->orWhere('is_4ps', true),
                                default       => null,
                            };
                        }
                    });
                })
                ->select('residents.*');

            return DataTables::of($query)
                ->addColumn('avatar', function ($r) {
                    $initial = strtoupper(substr($r->first_name, 0, 1));
                    if ($r->photo_path) {
                        $img = '<img src="'.asset('storage/'.$r->photo_path).'" style="width:100%;height:100%;object-fit:cover">';
                    } else {
                        $img = $initial;
                    }

                    return '<div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px">'.$img.'</div>';
                })
                ->addColumn('name_col', function ($r) {
                    $name = '<div style="font-weight:600;font-size:13.5px">'.e($r->last_name.', '.$r->first_name.($r->middle_name ? ' '.$r->middle_name : '').($r->suffix ? ' '.$r->suffix : '')).'</div>';
                    $contact = $r->contact_number ? '<div class="td-muted">'.e($r->contact_number).'</div>' : '';

                    return '<div style="display:flex;align-items:center;gap:10px">'.
                        $this->avatarHtml($r).
                        '<div>'.$name.$contact.'</div></div>';
                })
                ->addColumn('purok_col', fn ($r) => '<span class="td-muted">'.e($r->purok->name ?? '—').'</span>')
                ->addColumn('gender_col', function ($r) {
                    $cls = $r->gender === 'Male' ? 'badge-blue' : 'badge-orange';

                    return '<span class="badge '.$cls.'">'.$r->gender.'</span>';
                })
                ->addColumn('age_col', fn ($r) => $r->age ?? '—')
                ->addColumn('civil_col', fn ($r) => '<span class="td-muted">'.e($r->civil_status ?? '—').'</span>')
                ->addColumn('tags_col', function ($r) {
                    $tags = '';
                    if ($r->is_voter) {
                        $tags .= '<span class="badge badge-green" style="font-size:10px">Voter</span> ';
                    }
                    if ($r->is_senior) {
                        $tags .= '<span class="badge badge-yellow" style="font-size:10px">Senior</span> ';
                    }
                    if ($r->is_pwd) {
                        $tags .= '<span class="badge badge-blue" style="font-size:10px">PWD</span> ';
                    }
                    if ($r->is_solo_parent) {
                        $tags .= '<span class="badge badge-orange" style="font-size:10px">Solo Parent</span> ';
                    }
                    if ($r->is_4ps) {
                        $tags .= '<span class="badge badge-gold" style="font-size:10px">4Ps</span> ';
                    }

                    return '<div style="display:flex;flex-wrap:wrap;gap:4px">'.$tags.'</div>';
                })
                // Read-only: status changes go through the dated, logged dialog on the profile page
                ->addColumn('status_col', fn ($r) => '<span class="badge '.$r->residency_badge.'">'.e($r->residency_label).'</span>')
                ->addColumn('actions', function ($r) {
                    $show      = route('residents.show', $r);
                    $edit      = route('residents.edit', $r);
                    $delete    = route('residents.destroy', $r);
                    $clearance = route('documents.create').'?resident_id='.$r->id;
                    $qv        = route('residents.quick-view', $r);

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px;flex-wrap:nowrap">
                            <button onclick="residentQuickView('.$r->id.',\''.$qv.'\')"
                                    class="btn btn-secondary btn-sm btn-icon" title="Quick View">
                                <i class="fas fa-id-card"></i>
                            </button>
                            <a href="'.$clearance.'" class="btn btn-gold btn-sm" title="Issue Barangay Clearance" style="white-space:nowrap">
                                <i class="fas fa-file-circle-check"></i> Clearance
                            </a>
                            <a href="'.$show.'" class="btn btn-secondary btn-sm btn-icon" title="View Profile"><i class="fas fa-eye"></i></a>
                            <a href="'.$edit.'" class="btn btn-secondary btn-sm btn-icon" title="Edit"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="'.$delete.'"
                                  data-confirm="Permanently delete '.e($r->full_name).'? All linked records will remain but the resident profile will be removed."
                                  data-confirm-title="Delete Resident"
                                  data-confirm-ok="Delete">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon" title="Delete"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $s = $request->search['value'];
                        $query->where(function ($q) use ($s) {
                            $q->where('first_name', 'like', "%$s%")
                                ->orWhere('last_name', 'like', "%$s%")
                                ->orWhere('address', 'like', "%$s%")
                                ->orWhere('contact_number', 'like', "%$s%");
                        });
                    }
                })
                ->rawColumns(['name_col', 'avatar', 'purok_col', 'gender_col', 'civil_col', 'tags_col', 'status_col', 'actions'])
                ->make(true);
        }

        $puroks = Purok::orderBy('name')->get();

        // Counts for the Alive / Moved Out / Deceased / All chips above the table
        $statusCounts = Resident::selectRaw('residency_status, COUNT(*) AS total')
            ->groupBy('residency_status')
            ->pluck('total', 'residency_status');

        return view('residents.residents-index', compact('puroks', 'statusCounts'));
    }

    // -------------------------------------------------------
    // QUICK VIEW — JSON summary for the inline preview panel
    // -------------------------------------------------------
    public function quickView(Resident $resident)
    {
        $resident->load('purok', 'household');

        return response()->json([
            'id'               => $resident->id,
            'full_name'        => $resident->full_name,
            'photo_url'        => $resident->photo_path ? asset('storage/' . $resident->photo_path) : null,
            'initials'         => strtoupper(substr($resident->first_name, 0, 1) . substr($resident->last_name, 0, 1)),
            'age'              => $resident->age ?? '—',
            'gender'           => $resident->gender ?? '—',
            'civil_status'     => $resident->civil_status ?? '—',
            'birthdate'        => $resident->birthdate ? \Carbon\Carbon::parse($resident->birthdate)->format('m/d/Y') : '—',
            'address'          => $resident->address ?? '—',
            'contact_number'   => $resident->contact_number ?? '—',
            'email_address'    => $resident->email_address ?? '—',
            'occupation'       => $resident->occupation ?? '—',
            'residency_status' => $resident->residency_status,
            'residency_label'  => $resident->residency_label,
            'residency_badge'  => $resident->residency_badge,
            'purok'            => $resident->purok?->name ?? '—',
            'household'        => $resident->household?->household_number ?? '—',
            'is_voter'         => (bool) $resident->is_voter,
            'is_senior'        => (bool) $resident->is_senior,
            'is_pwd'           => (bool) $resident->is_pwd,
            'is_solo_parent'   => (bool) $resident->is_solo_parent,
            'is_4ps'           => (bool) $resident->is_4ps,
            'profile_url'      => route('residents.show', $resident),
            'edit_url'         => route('residents.edit', $resident),
            'clearance_url'    => route('documents.create') . '?resident_id=' . $resident->id,
        ]);
    }

    private function residentRules(): array
    {
        return [
            'last_name'        => 'required|string|max:100',
            'first_name'       => 'required|string|max:100',
            'middle_name'      => 'nullable|string|max:100',
            'suffix'           => 'nullable|string|max:10',
            'birthdate'        => 'required|date|before:today',
            'gender'           => 'required|in:Male,Female',
            'civil_status'     => 'nullable|in:Single,Married,Widowed,Separated,Annulled',
            'birthplace'       => 'nullable|string|max:255',
            'nationality'      => 'nullable|string|max:100',
            'religion'         => 'nullable|string|max:100',
            'occupation'       => 'nullable|string|max:100',
            'contact_number'   => 'nullable|string|max:20',
            'email_address'    => 'nullable|email|max:255',
            'address'          => 'required|string|max:255',
            'purok_id'         => 'required|exists:puroks,id',
            'household_id'     => 'nullable|exists:households,id',
            'is_voter'         => 'boolean',
            'is_pwd'           => 'boolean',
            'is_senior'        => 'boolean',
            'is_solo_parent'   => 'boolean',
            'is_4ps'           => 'boolean',
            // residency_status is intentionally absent: new residents start as Alive,
            // and later changes go through ResidentStatusController (dated + logged).
            'photo_path'       => 'nullable|image|max:2048',
        ];
    }

    private function residentMessages(): array
    {
        return [
            'last_name.required'        => 'Please enter the resident\'s last name.',
            'last_name.max'             => 'Last name must not exceed 100 characters.',
            'first_name.required'       => 'Please enter the resident\'s first name.',
            'first_name.max'            => 'First name must not exceed 100 characters.',
            'birthdate.required'        => 'Please enter the resident\'s date of birth.',
            'birthdate.date'            => 'Please enter a valid date of birth.',
            'birthdate.before'          => 'Date of birth must be a date in the past.',
            'gender.required'           => 'Please select the resident\'s gender.',
            'address.required'          => 'Please enter the resident\'s full address.',
            'address.max'               => 'Address must not exceed 255 characters.',
            'purok_id.required'         => 'Please select a Purok.',
            'purok_id.exists'           => 'The selected Purok is not valid. Please choose from the list.',
            'email_address.email'       => 'Please enter a valid email address (e.g. juan@gmail.com).',
            'contact_number.max'        => 'Contact number must not exceed 20 characters.',
            'photo_path.image'          => 'The photo must be an image file (JPG, PNG, GIF, etc.).',
            'photo_path.max'            => 'Photo is too large. Maximum allowed size is 2MB.',
        ];
    }

    private function avatarHtml($r): string
    {
        $initial = strtoupper(substr($r->first_name, 0, 1));
        $inner = $r->photo_path
            ? '<img src="'.asset('storage/'.$r->photo_path).'" style="width:100%;height:100%;object-fit:cover">'
            : $initial;

        return '<div style="width:36px;height:36px;border-radius:50%;flex-shrink:0;overflow:hidden;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px">'.$inner.'</div>';
    }

    // -------------------------------------------------------
    // CREATE — Show add form
    // -------------------------------------------------------
    public function create()
    {
        $puroks = Purok::orderBy('name')->get();
        $households = Household::orderBy('household_number')->get();

        return view('residents.residents-create', compact('puroks', 'households'));
    }

    // -------------------------------------------------------
    // STORE — Save new resident
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate($this->residentRules(), $this->residentMessages());

        if ($request->hasFile('photo_path')) {
            $validated['photo_path'] = $request->file('photo_path')->store('residents', 'public');
        }

        $validated['is_voter'] = $request->boolean('is_voter');
        $validated['is_pwd'] = $request->boolean('is_pwd');
        $validated['is_senior'] = $request->boolean('is_senior');
        $validated['is_solo_parent'] = $request->boolean('is_solo_parent');
        $validated['is_4ps'] = $request->boolean('is_4ps');
        $validated['residency_status'] = ResidencyStatus::Alive->value;

        $record = Resident::create($validated);
        $this->logActivity('created', $record);

        return redirect()->route('residents.index')->with('success', 'Resident registered successfully.');
    }

    // -------------------------------------------------------
    // SHOW — View resident profile
    // -------------------------------------------------------
    public function show(Resident $resident)
    {
        $resident->load(['purok', 'household', 'documents', 'blotterCases', 'statusLogs.changedBy']);

        return view('residents.residents-show', compact('resident'));
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(Resident $resident)
    {
        $puroks = Purok::orderBy('name')->get();
        $households = Household::orderBy('household_number')->get();

        return view('residents.residents-edit', compact('resident', 'puroks', 'households'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited resident
    // -------------------------------------------------------
    public function update(Request $request, Resident $resident)
    {
        $validated = $request->validate($this->residentRules(), $this->residentMessages());

        if ($request->hasFile('photo_path')) {
            // Delete old photo before storing the new one
            if ($resident->photo_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($resident->photo_path);
            }
            $validated['photo_path'] = $request->file('photo_path')->store('residents', 'public');
        } elseif ($request->boolean('remove_photo') && $resident->photo_path) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($resident->photo_path);
            $validated['photo_path'] = null;
        }

        $validated['is_voter'] = $request->boolean('is_voter');
        $validated['is_pwd'] = $request->boolean('is_pwd');
        $validated['is_senior'] = $request->boolean('is_senior');
        $validated['is_solo_parent'] = $request->boolean('is_solo_parent');
        $validated['is_4ps'] = $request->boolean('is_4ps');

        $oldData = $resident->getOriginal();
        $resident->update($validated);
        $this->logActivity('updated', $resident, $oldData, $resident->fresh()->toArray());

        return redirect()->route('residents.index')->with('success', 'Resident updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete resident
    // -------------------------------------------------------
    public function destroy(Request $request, Resident $resident)
    {
        $name = $resident->full_name;
        $this->logActivity('deleted', $resident);
        $resident->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "{$name} has been removed."]);
        }

        return redirect()->route('residents.index')->with('success', 'Resident removed successfully.');
    }
}
