@extends('layouts.app')

@section('title', 'Add Official')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Add Official</h1>
        <p class="page-subtitle">Register a barangay official or staff member</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('officials.store') }}" enctype="multipart/form-data">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-tie"></i> Official Information</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Personal Details</div>

        {{-- Resident Link (optional — auto-fills name & contact) --}}
        <div class="form-group mb-6">
            <label class="form-label">
                Link to Resident Profile
                <span style="font-size:12px;font-weight:400;color:var(--text-subtle);margin-left:6px">(optional — auto-fills name &amp; contact)</span>
            </label>
            <select name="resident_id" id="s2ResidentLink" class="form-control" style="width:100%">
                <option value="">Search registered residents…</option>
            </select>
            <input type="hidden" id="residentLinkedFlag" value="0">
        </div>

        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="full_name" id="officialFullName" class="form-control @error('full_name') is-invalid @enderror"
                       value="{{ old('full_name') }}" placeholder="e.g. Juan dela Cruz" required>
                @error('full_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" id="officialContact" class="form-control @error('contact_number') is-invalid @enderror"
                       value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX">
                @error('contact_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Position & Committee</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Position <span style="color:var(--crimson)">*</span></label>
                <select name="position" id="s2Position" class="form-control @error('position') is-invalid @enderror" required>
                    <option value="">Select Position</option>
                    @foreach($positions as $p)
                        <option value="{{ $p }}" {{ old('position') === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
                @error('position')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Committee</label>
                <select name="committee" id="s2Committee" class="form-control @error('committee') is-invalid @enderror">
                    <option value="">None / N/A</option>
                    @foreach($committees as $c)
                        <option value="{{ $c }}" {{ old('committee') === $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
                @error('committee')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Term & Status</div>
        <div class="form-grid-3 mb-6">
            <div class="form-group">
                <label class="form-label">Term Start <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_start" class="form-control @error('term_start') is-invalid @enderror"
                       value="{{ old('term_start') }}" required>
                @error('term_start')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Term End <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_end" class="form-control @error('term_end') is-invalid @enderror"
                       value="{{ old('term_end') }}" required>
                @error('term_end')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="justify-content:flex-end;padding-bottom:4px">
                <label class="form-label">&nbsp;</label>
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                    Currently Active
                </label>
            </div>
        </div>

        <div class="form-section-title">Photo</div>
        <div class="form-group" style="max-width:320px">
            <label class="form-label">Official Photo <span style="color:#9ca3af;font-weight:400">(optional)</span></label>
            <label for="photo-upload" id="photoDropZone" style="display:block;border:2px dashed var(--border);border-radius:var(--radius);padding:20px;text-align:center;cursor:pointer;transition:border-color .2s,border-style .2s,background .2s">
                <i class="fas fa-camera" id="photoDropIcon" style="font-size:26px;color:var(--text-muted);margin-bottom:6px;display:block"></i>
                <img id="photoDropPreview" src="" alt="" style="display:none;width:80px;height:80px;border-radius:50%;object-fit:cover;margin:0 auto 8px;border:2px solid var(--border)">
                <div id="photoDropHint" style="font-size:13px;color:var(--text-muted);margin-bottom:3px">Drag & drop or <span style="color:var(--navy);font-weight:600">browse</span></div>
                <div id="photoDropSub" style="font-size:12px;color:var(--text-subtle)">JPG, PNG, WEBP — max 2MB</div>
                <div id="photoDropName" style="display:none;margin-top:4px;font-size:13px;font-weight:600;color:var(--navy)"></div>
            </label>
            <input type="file" id="photo-upload" name="photo_path" accept="image/*" style="display:none">
            <button type="button" id="photoClearBtn" onclick="clearOfficialPhoto()" style="display:none;margin-top:8px;width:100%;background:none;border:1px solid var(--danger,#e53e3e);border-radius:var(--radius-sm,6px);color:var(--danger,#c0392b);font-size:12px;cursor:pointer;padding:5px 10px"><i class="fas fa-times"></i> Remove photo</button>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Official
    </button>
    <a href="{{ route('officials.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection

@push('scripts')
<script>
$(function () {
    const s2 = { dropdownParent: $('body'), width: '100%', allowClear: false };
    $('#s2Position').select2($.extend({}, s2, { placeholder: 'Select Position' }));
    $('#s2Committee').select2($.extend({}, s2, { placeholder: 'None / N/A', allowClear: true }));

    // Resident link Select2 — AJAX sourced
    $('#s2ResidentLink').select2({
        dropdownParent: $('body'),
        width: '100%',
        allowClear: true,
        placeholder: 'Search by name or contact number…',
        minimumInputLength: 1,
        ajax: {
            url: '{{ route("select2.residents") }}',
            dataType: 'json',
            delay: 250,
            data: function (params) { return { q: params.term }; },
            processResults: function (data) { return { results: data.results }; },
        },
        templateResult: function (r) {
            if (r.loading) return 'Searching…';
            return r.text;
        },
    });

    // Auto-fill name & contact when a resident is selected
    $('#s2ResidentLink').on('select2:select', function (e) {
        var data = e.params.data;
        $('#officialFullName').val(data.full_name || '').trigger('change');
        if (data.contact_number) {
            $('#officialContact').val(data.contact_number).trigger('change');
        }
        $('#residentLinkedFlag').val('1');
        // Lock the name field to prevent accidental edits
        $('#officialFullName').prop('readonly', true).css('background', 'var(--surface-alt, #f5f5f5)');
    });

    // Unlock if resident link is cleared
    $('#s2ResidentLink').on('select2:clear', function () {
        $('#officialFullName').prop('readonly', false).css('background', '');
        $('#residentLinkedFlag').val('0');
    });
});

/* ── Photo drag-and-drop ── */
(function () {
    var zone  = document.getElementById('photoDropZone');
    var input = document.getElementById('photo-upload');
    if (!zone || !input) return;

    /* click-to-browse handled natively by <label for="photo-upload"> */

    zone.addEventListener('dragover', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = 'var(--navy)';
        zone.style.backgroundColor = 'rgba(13,33,68,.04)';
    });
    zone.addEventListener('dragleave', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
    });
    zone.addEventListener('drop', function (e) {
        e.preventDefault(); e.stopPropagation();
        zone.style.borderColor     = '';
        zone.style.backgroundColor = '';
        var files = e.dataTransfer.files;
        if (files.length) { var dt = new DataTransfer(); dt.items.add(files[0]); input.files = dt.files; showOfficialPhoto(files[0]); }
    });
    input.addEventListener('change', function () { if (this.files[0]) showOfficialPhoto(this.files[0]); });
}());

function showOfficialPhoto(file) {
    var reader = new FileReader();
    reader.onload = function (ev) {
        document.getElementById('photoDropPreview').src = ev.target.result;
        document.getElementById('photoDropPreview').style.display = 'block';
        document.getElementById('photoDropIcon').style.display = 'none';
        document.getElementById('photoDropHint').style.display = 'none';
        document.getElementById('photoDropSub').style.display  = 'none';
        document.getElementById('photoDropName').textContent = '✔ ' + file.name;
        document.getElementById('photoDropName').style.display = 'block';
        document.getElementById('photoClearBtn').style.display = 'block';
        var z = document.getElementById('photoDropZone');
        z.style.borderStyle = 'solid'; z.style.borderColor = 'var(--navy)';
    };
    reader.readAsDataURL(file);
}
function clearOfficialPhoto() {
    document.getElementById('photo-upload').value = '';
    document.getElementById('photoDropPreview').style.display = 'none';
    document.getElementById('photoDropPreview').src = '';
    document.getElementById('photoDropIcon').style.display = 'block';
    document.getElementById('photoDropHint').style.display = 'block';
    document.getElementById('photoDropSub').style.display  = 'block';
    document.getElementById('photoDropName').style.display = 'none';
    document.getElementById('photoClearBtn').style.display = 'none';
    var z = document.getElementById('photoDropZone');
    z.style.borderStyle = ''; z.style.borderColor = '';
}
</script>
@endpush