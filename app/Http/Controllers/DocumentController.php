<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Resident;
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
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->select('documents.*');

            return DataTables::of($query)
                ->addColumn('number_col', fn ($d) => '<span class="td-mono">'.e($d->doc_number).'</span>')
                ->addColumn('resident_col', function ($d) {
                    $name = $d->resident ? e($d->resident->last_name.', '.$d->resident->first_name) : '—';
                    $purok = $d->resident?->purok?->name ? '<div class="td-muted">'.e($d->resident->purok->name).'</div>' : '';

                    return '<div style="font-weight:600;font-size:13px">'.$name.'</div>'.$purok;
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
                    $cls = match ($d->status) {
                        'Released' => 'badge-green',
                        'Processing' => 'badge-blue',
                        'Pending' => 'badge-yellow',
                        'Cancelled' => 'badge-gray',
                        default => 'badge-gray'
                    };

                    return '<span class="badge '.$cls.'">'.$d->status.'</span>';
                })
                ->addColumn('actions', function ($d) {
                    $show = route('documents.show', $d);
                    $edit = route('documents.edit', $d);
                    $delete = route('documents.destroy', $d);

                    return '
                        <div style="display:flex;justify-content:flex-end;gap:6px">
                            <a href="'.$show.'" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-eye"></i></a>
                            <a href="'.$edit.'" class="btn btn-secondary btn-sm btn-icon"><i class="fas fa-pen"></i></a>
                            <form method="POST" action="'.$delete.'" onsubmit="return confirm(\'Delete this document?\')">
                                <input type="hidden" name="_token" value="'.csrf_token().'">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="btn btn-danger btn-sm btn-icon"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>';
                })
                ->filter(function ($query) use ($request) {
                    if ($request->has('search') && $request->search['value']) {
                        $s = $request->search['value'];
                        $query->where(fn ($q) => $q
                            ->where('doc_number', 'like', "%$s%")
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
        $residents = Resident::active()->orderBy('last_name')->orderBy('first_name')->get();
        $documentTypes = ['Barangay Clearance', 'Certificate of Residency', 'Certificate of Indigency', 'Good Moral Character', 'Business Clearance', 'Certificate of Live Birth', 'Other'];

        return view('documents.documents-create', compact('residents', 'documentTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id'   => 'required|exists:residents,id',
            'document_type' => 'required|string',
            'purpose'       => 'required|string|max:500',
            'fee_paid'      => 'nullable|numeric|min:0',
            'or_number'     => 'nullable|string|max:100',
            'released_at'   => 'nullable|date',
            'status'        => 'required|in:Pending,Processing,Released,Cancelled',
        ], [
            'resident_id.required'   => 'Please select a resident.',
            'resident_id.exists'     => 'The selected resident was not found. Please search again.',
            'document_type.required' => 'Please select a document type.',
            'purpose.required'       => 'Please describe the purpose of this document (e.g. Employment, Loan).',
            'purpose.max'            => 'Purpose must not exceed 500 characters.',
            'fee_paid.numeric'       => 'Fee must be a valid number.',
            'fee_paid.min'           => 'Fee cannot be a negative amount.',
            'or_number.max'          => 'OR Number must not exceed 100 characters.',
            'released_at.date'       => 'Please enter a valid release date.',
        ]);
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
        $document->load(['resident.purok', 'issuedBy']);

        return view('documents.documents-show', compact('document'));
    }

    public function edit(Document $document)
    {
        $residents = Resident::active()->orderBy('last_name')->get();
        $documentTypes = ['Barangay Clearance', 'Certificate of Residency', 'Certificate of Indigency', 'Good Moral Character', 'Business Clearance', 'Certificate of Live Birth', 'Other'];

        return view('documents.documents-edit', compact('document', 'residents', 'documentTypes'));
    }

    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'purpose' => 'required|string|max:500',
            'fee_paid' => 'nullable|numeric|min:0',
            'or_number' => 'nullable|string|max:100',
            'released_at' => 'nullable|date',
            'status' => 'required|in:Pending,Processing,Released,Cancelled',
        ], [
            'document_type.required' => 'Please select a document type.',
            'purpose.required'       => 'Please describe the purpose of this document (e.g. Employment, Loan).',
            'purpose.max'            => 'Purpose must not exceed 500 characters.',
            'fee_paid.numeric'       => 'Fee must be a valid number.',
            'fee_paid.min'           => 'Fee cannot be a negative amount.',
            'or_number.max'          => 'OR Number must not exceed 100 characters.',
            'released_at.date'       => 'Please enter a valid release date.',
        ]);
        if ($validated['status'] === 'Released' && $document->status !== 'Released' && empty($validated['released_at'])) {
            $validated['released_at'] = now();
        }
        $oldData = $document->getOriginal();
        $document->update($validated);
        $this->logActivity('updated', $document, $oldData, $document->fresh()->toArray());

        return redirect()->route('documents.index')->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        $this->logActivity('deleted', $document);
        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Document deleted successfully.');
    }
}
