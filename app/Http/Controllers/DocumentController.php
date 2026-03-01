<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Resident;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all documents
    // -------------------------------------------------------
    public function index(Request $request)
    {
        $query = Document::with(['resident', 'issuedBy'])
            ->when($request->search, function ($q) use ($request) {
                $q->whereHas('resident', function ($r) use ($request) {
                    $r->where('first_name', 'like', "%{$request->search}%")
                      ->orWhere('last_name', 'like', "%{$request->search}%");
                })->orWhere('doc_number', 'like', "%{$request->search}%");
            })
            ->when($request->document_type, function ($q) use ($request) {
                $q->where('document_type', $request->document_type);
            })
            ->when($request->status, function ($q) use ($request) {
                $q->where('status', $request->status);
            })
            ->latest();

        $documents = $query->paginate(15)->withQueryString();

        $documentTypes = [
            'Barangay Clearance',
            'Certificate of Residency',
            'Certificate of Indigency',
            'Good Moral Character',
            'Business Clearance',
            'Certificate of Live Birth',
            'Other',
        ];

        return view('documents.documents-index', compact('documents', 'documentTypes'));
    }

    // -------------------------------------------------------
    // CREATE — Show issue form
    // -------------------------------------------------------
    public function create()
    {
        $residents = Resident::active()
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();

        $documentTypes = [
            'Barangay Clearance',
            'Certificate of Residency',
            'Certificate of Indigency',
            'Good Moral Character',
            'Business Clearance',
            'Certificate of Live Birth',
            'Other',
        ];

        return view('documents.documents-create', compact('residents', 'documentTypes'));
    }

    // -------------------------------------------------------
    // STORE — Save new document
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'resident_id'   => 'required|exists:residents,id',
            'document_type' => 'required|string',
            'purpose'       => 'required|string|max:500',
            'fee_paid'      => 'nullable|numeric|min:0',
            'status'        => 'required|in:Pending,Processing,Released,Cancelled',
        ]);

        $validated['doc_number'] = Document::generateDocNumber();
        $validated['issued_by']  = auth()->id();

        if ($validated['status'] === 'Released') {
            $validated['released_at'] = now();
        }

        Document::create($validated);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document issued successfully.');
    }

    // -------------------------------------------------------
    // SHOW — View / Print document
    // -------------------------------------------------------
    public function show(Document $document)
    {
        $document->load(['resident.purok', 'issuedBy']);

        return view('documents.documents-show', compact('document'));
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(Document $document)
    {
        $residents = Resident::active()
            ->orderBy('last_name')
            ->get();

        $documentTypes = [
            'Barangay Clearance',
            'Certificate of Residency',
            'Certificate of Indigency',
            'Good Moral Character',
            'Business Clearance',
            'Certificate of Live Birth',
            'Other',
        ];

        return view('documents.documents-edit', compact('document', 'residents', 'documentTypes'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited document
    // -------------------------------------------------------
    public function update(Request $request, Document $document)
    {
        $validated = $request->validate([
            'document_type' => 'required|string',
            'purpose'       => 'required|string|max:500',
            'fee_paid'      => 'nullable|numeric|min:0',
            'status'        => 'required|in:Pending,Processing,Released,Cancelled',
        ]);

        // Set released_at when status changes to Released
        if ($validated['status'] === 'Released' && $document->status !== 'Released') {
            $validated['released_at'] = now();
        }

        $document->update($validated);

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete document
    // -------------------------------------------------------
    public function destroy(Document $document)
    {
        $document->delete();

        return redirect()
            ->route('documents.index')
            ->with('success', 'Document deleted successfully.');
    }
}