<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentAppointment;
use App\Models\Resident;
use App\Services\DocumentQueueService;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DocumentController extends Controller
{
    use LogsActivity;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Document::with(['resident.purok'])
                ->when($request->document_type, fn ($q) => $q->whereIn('document_type', (array) $request->document_type))
                ->when($request->status,        fn ($q) => $q->where('status', $request->status))
                ->when($request->source,        fn ($q) => $q->where('source', $request->source))
                ->select('documents.*');

            return DataTables::of($query)
                ->addColumn('number_col', function ($d) {
                    $show  = route('documents.show', $d);
                    $badge = $d->source === 'portal'
                        ? ' <span class="badge badge-blue" style="font-size:10px;margin-left:4px;vertical-align:middle">Portal</span>'
                        : '';
                    return '<a href="'.$show.'" class="td-mono"
                               style="color:var(--navy);font-weight:600;text-decoration:none"
                               title="View document">'.e($d->doc_number).'</a>'.$badge;
                })
                ->addColumn('resident_col', function ($d) {
                    if ($d->source === 'portal') {
                        $name = e($d->resident_name_portal ?? '—');
                        $sub  = '<div class="td-muted" style="font-size:11px">Portal Submission</div>';
                    } else {
                        $name = $d->resident
                            ? e($d->resident->last_name.', '.$d->resident->first_name)
                            : '—';
                        $sub  = $d->resident?->purok?->name
                            ? '<div class="td-muted">'.e($d->resident->purok->name).'</div>'
                            : '';
                    }
                    return '<div style="font-weight:600;font-size:13px">'.$name.'</div>'.$sub;
                })
                ->addColumn('type_col', fn ($d) => '<span class="badge badge-navy" style="white-space:normal;line-height:1.4">'.e($d->document_type).'</span>')
                ->addColumn('purpose_col', fn ($d) => '<span class="td-muted" style="max-width:180px;display:block;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">'.e($d->purpose ?? '—').'</span>')
                ->addColumn('fee_col', function ($d) {
                    $fee = $d->fee_paid ?? 0;
                    return $fee > 0
                        ? '<span style="font-weight:600;color:var(--navy)">₱'.number_format($fee, 2).'</span>'
                        : '<span class="badge badge-green">Free</span>';
                })
                ->addColumn('date_col', fn ($d) => '<span class="td-muted">'.$d->created_at->format('M d, Y').'</span>')
                ->addColumn('status_col', function ($d) {
                    // Identical CSS classes to AppointmentController — single source of truth
                    $cls = match ($d->status) {
                        'Pending'    => 'badge-yellow',
                        'Processing' => 'badge-blue',
                        'Ready'      => 'badge-green',
                        'Released'   => 'badge-gray',
                        'Cancelled'  => 'badge-red',
                        default      => 'badge-gray',
                    };
                    return '<span class="badge '.$cls.'">'.$d->status.'</span>';
                })
                ->addColumn('actions', function ($d) {
                    $show      = route('documents.show', $d);
                    $delete    = route('documents.destroy', $d);
                    $statusUrl = route('documents.quickStatus', $d);

                    // Context-aware pipeline button: show only the single next valid step
                    $pipelineBtn = match ($d->status) {
                        'Pending'    => '<button class="btn doc-pipeline-btn btn-sm"
                                                 style="height:28px;padding:0 10px;font-size:12px;font-weight:600;
                                                        background:#f0f4ff;color:#1d4ed8;border:1px solid #bfdbfe;
                                                        border-radius:var(--radius-sm);cursor:pointer;white-space:nowrap"
                                                 title="Advance to Processing"
                                                 data-id="'.e($d->id).'" data-next="Processing" data-url="'.$statusUrl.'"
                                                 data-source="'.e($d->source).'">
                                                <i class="fas fa-gear" style="font-size:11px;margin-right:4px"></i>Process
                                        </button>',
                        'Processing' => '<button class="btn doc-pipeline-btn btn-sm"
                                                 style="height:28px;padding:0 10px;font-size:12px;font-weight:600;
                                                        background:#f0fdf4;color:#15803d;border:1px solid #86efac;
                                                        border-radius:var(--radius-sm);cursor:pointer;white-space:nowrap"
                                                 title="Mark as Ready for Pick-up"
                                                 data-id="'.e($d->id).'" data-next="Ready" data-url="'.$statusUrl.'"
                                                 data-source="'.e($d->source).'">
                                                <i class="fas fa-bell" style="font-size:11px;margin-right:4px"></i>Ready
                                        </button>',
                        'Ready'      => '<button class="btn doc-pipeline-btn btn-sm"
                                                 style="height:28px;padding:0 10px;font-size:12px;font-weight:600;
                                                        background:#ecfdf5;color:#166534;border:1px solid #6ee7b7;
                                                        border-radius:var(--radius-sm);cursor:pointer;white-space:nowrap"
                                                 title="Mark as Released"
                                                 data-id="'.e($d->id).'" data-next="Released" data-url="'.$statusUrl.'"
                                                 data-source="'.e($d->source).'">
                                                <i class="fas fa-flag-checkered" style="font-size:11px;margin-right:4px"></i>Release
                                        </button>',
                        default      => '',
                    };

                    // Print button — only for Released docs (opens in new tab, auto-triggers print dialog)
                    // Eye button for all other statuses
                    $printBtn = $d->status === 'Released'
                        ? '<a href="'.$show.'?print=1" target="_blank"
                              class="btn btn-primary btn-sm btn-icon"
                              title="Print Certificate"
                              style="background:var(--navy);border-color:var(--navy)">
                               <i class="fas fa-print"></i>
                           </a>'
                        : '<a href="'.$show.'" class="btn btn-secondary btn-sm btn-icon" title="View Document">
                               <i class="fas fa-eye"></i>
                           </a>';

                    return '
                        <div style="display:flex;justify-content:flex-end;align-items:center;gap:6px">
                            '.$pipelineBtn.'
                            '.$printBtn.'
                            <form method="POST" action="'.$delete.'"
                                  data-confirm="Delete document '.e($d->doc_number).'? This cannot be undone."
                                  data-confirm-title="Delete Document"
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
                        $query->where(fn ($q) => $q
                            ->where('doc_number', 'like', "%$s%")
                            ->orWhere('resident_name_portal', 'like', "%$s%")
                            ->orWhereHas('resident', fn ($r) => $r
                                ->where('first_name', 'like', "%$s%")
                                ->orWhere('last_name', 'like', "%$s%")));
                    }
                })
                ->rawColumns(['number_col', 'resident_col', 'type_col', 'purpose_col', 'fee_col', 'date_col', 'status_col', 'actions'])
                ->make(true);
        }

        $documentTypes = ['Barangay Clearance', 'Certificate of Residency', 'Certificate of Indigency', 'Good Moral Character', 'Business Clearance', 'Certificate of Live Birth', 'Other'];

        return view('documents.documents-index', compact('documentTypes'));
    }

    public function create()
    {
        $residents     = Resident::active()->orderBy('last_name')->orderBy('first_name')->get();
        $documentTypes = ['Barangay Clearance', 'Certificate of Residency', 'Certificate of Indigency', 'Good Moral Character', 'Business Clearance', 'Certificate of Live Birth', 'Other'];
        $relationships = ['Son', 'Daughter', 'Parent / Guardian', 'Spouse', 'Sibling', 'Cousin', 'Nephew / Niece', 'Legal Guardian', 'Attorney-in-Fact (SPA)', 'Other'];

        return view('documents.documents-create', compact('residents', 'documentTypes', 'relationships'));
    }

    public function store(Request $request)
    {
        $rules = [
            'resident_id'       => 'required|exists:residents,id',
            'document_type'     => 'required|string',
            'purpose'           => 'required|string|max:500',
            'fee_paid'          => 'nullable|numeric|min:0',
            'or_number'         => 'nullable|string|max:100',
            'released_at'       => 'nullable|date',
            'status'            => 'required|in:' . implode(',', Document::$statuses),
            'requestor_contact' => 'nullable|string|max:255',
        ];

        if ($request->boolean('is_representative')) {
            $rules['requestor_name']         = 'required|string|max:255';
            $rules['requestor_relationship'] = 'required|string|max:100';
        }

        $validated = $request->validate($rules, [
            'resident_id.required'            => 'Please select a resident.',
            'resident_id.exists'              => 'The selected resident was not found. Please search again.',
            'document_type.required'          => 'Please select a document type.',
            'purpose.required'                => 'Please describe the purpose of this document (e.g. Employment, Loan).',
            'purpose.max'                     => 'Purpose must not exceed 500 characters.',
            'fee_paid.numeric'                => 'Fee must be a valid number.',
            'fee_paid.min'                    => 'Fee cannot be a negative amount.',
            'or_number.max'                   => 'OR Number must not exceed 100 characters.',
            'released_at.date'                => 'Please enter a valid release date.',
            'requestor_name.required'         => 'Please enter the representative\'s name.',
            'requestor_relationship.required' => 'Please select the representative\'s relationship to the resident.',
        ]);

        // Auto-fill requestor from resident when no representative
        if (! $request->boolean('is_representative')) {
            $resident = Resident::find($validated['resident_id']);
            $validated['requestor_name']         = $resident?->full_name ?? '';
            $validated['requestor_relationship'] = null;
            $validated['requestor_contact']      = null;
        }

        $validated['doc_number'] = Document::generateDocNumber();
        $validated['issued_by']  = auth()->id();
        if ($validated['status'] === 'Released' && empty($validated['released_at'])) {
            $validated['released_at'] = now();
        }
        $record = Document::create($validated);
        $this->logActivity('created', $record);

        return redirect()->route('documents.index')->with('success', 'Document issued successfully.');
    }

    public function show(Document $document)
    {
        $document->load(['resident.purok', 'issuedBy', 'releasedBy']);

        return view('documents.documents-show', compact('document'));
    }

    public function edit(Document $document)
    {
        $document->load('resident');

        $documentTypes = ['Barangay Clearance', 'Certificate of Residency', 'Certificate of Indigency', 'Good Moral Character', 'Business Clearance', 'Certificate of Live Birth', 'Other'];

        // For portal documents with no linked resident_id,
        // try to find the resident by the portal-submitted name so the field pre-populates.
        $suggestedResident = null;
        if (! $document->resident && $document->resident_name_portal) {
            $name = trim($document->resident_name_portal);
            $suggestedResident = Resident::whereRaw(
                "LOWER(CONCAT(first_name, ' ', last_name)) = ? OR LOWER(CONCAT(last_name, ', ', first_name)) = ?",
                [strtolower($name), strtolower($name)]
            )->first();

            // Partial fallback — token match on last name
            if (! $suggestedResident) {
                $tokens = array_filter(explode(' ', strtolower($name)));
                foreach ($tokens as $token) {
                    if (strlen($token) < 3) continue;
                    $suggestedResident = Resident::whereRaw('LOWER(last_name) LIKE ?', ["%$token%"])->first();
                    if ($suggestedResident) break;
                }
            }
        }

        return view('documents.documents-edit', compact('document', 'documentTypes', 'suggestedResident'));
    }

    public function update(Request $request, Document $document)
    {
        $rules = [
            'document_type'     => 'required|string',
            'purpose'           => 'required|string|max:500',
            'fee_paid'          => 'nullable|numeric|min:0',
            'or_number'         => 'nullable|string|max:100',
            'released_at'       => 'nullable|date',
            'status'            => 'required|in:' . implode(',', Document::$statuses),
            'requestor_contact' => 'nullable|string|max:255',
        ];

        if ($request->boolean('is_representative')) {
            $rules['requestor_name']         = 'required|string|max:255';
            $rules['requestor_relationship'] = 'required|string|max:100';
        }

        $validated = $request->validate($rules, [
            'document_type.required'          => 'Please select a document type.',
            'purpose.required'                => 'Please describe the purpose of this document (e.g. Employment, Loan).',
            'purpose.max'                     => 'Purpose must not exceed 500 characters.',
            'fee_paid.numeric'                => 'Fee must be a valid number.',
            'fee_paid.min'                    => 'Fee cannot be a negative amount.',
            'or_number.max'                   => 'OR Number must not exceed 100 characters.',
            'released_at.date'                => 'Please enter a valid release date.',
            'requestor_name.required'         => 'Please enter the representative\'s name.',
            'requestor_relationship.required' => 'Please select the representative\'s relationship to the resident.',
        ]);

        // Auto-fill requestor from resident when no representative
        if (! $request->boolean('is_representative')) {
            $resident = $document->resident ?? Resident::find($request->input('resident_id'));
            $validated['requestor_name']         = $resident?->full_name ?? '';
            $validated['requestor_relationship'] = null;
            $validated['requestor_contact']      = null;
        }

        if ($validated['status'] === 'Released' && $document->status !== 'Released' && empty($validated['released_at'])) {
            $validated['released_at'] = now();
        }
        $oldData = $document->getOriginal();
        $document->update($validated);
        $this->logActivity('updated', $document, $oldData, $document->fresh()->toArray());

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function quickStatus(Request $request, Document $document)
    {
        $validated = $request->validate([
            'status'        => 'required|in:' . implode(',', Document::$statuses),
            'note'          => 'nullable|string|max:500',
            'resident_note' => 'nullable|string|max:500',
            'released_to'   => 'nullable|string|max:255',
            'pickup_date'   => 'nullable|date',
        ]);

        $old = $document->getOriginal();

        // All sync + reverse-mirror + email delegated to DocumentQueueService
        $document = app(DocumentQueueService::class)->reverseAdvance(
            document:      $document,
            newStatus:     $validated['status'],
            changedBy:     auth()->user()->name,
            note:          $validated['note']          ?? null,
            residentNote:  $validated['resident_note'] ?? null,
            pickupDate:    $validated['pickup_date']   ?? null,
            releasedTo:    $validated['released_to']   ?? null,
            releasedBy:    auth()->id(),
        );

        $this->logActivity('updated', $document, $old, $document->toArray());

        return response()->json([
            'success' => true,
            'message' => "Status updated to {$document->status}.",
            'status'  => $document->status,
        ]);
    }

    public function destroy(Request $request, Document $document)
    {
        $num = $document->doc_number;
        $this->logActivity('deleted', $document);
        $document->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => "Document {$num} deleted."]);
        }

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
