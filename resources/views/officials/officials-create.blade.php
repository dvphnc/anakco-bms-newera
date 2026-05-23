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
        <div class="form-group">
            <label class="form-label">Official Photo</label>
            <input type="file" name="photo_path" class="form-control" accept="image/*">
            <span style="font-size:13px;color:var(--text-subtle);margin-top:3px"><i class="fas fa-circle-info" style="color:var(--navy);opacity:0.5"></i> JPG, PNG. Max 2MB.</span>
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
</script>
@endpush