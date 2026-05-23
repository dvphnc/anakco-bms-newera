@extends('layouts.app')

@section('title', 'Edit Official')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit Official</h1>
        <p class="page-subtitle">{{ $official->full_name }} — {{ $official->position }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('officials.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('officials.update', $official) }}" enctype="multipart/form-data">
@csrf @method('PUT')

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
                @if($official->resident_id && $official->resident)
                    <option value="{{ $official->resident_id }}" selected>
                        {{ $official->resident->last_name }}, {{ $official->resident->first_name }}{{ $official->resident->middle_name ? ' '.substr($official->resident->middle_name,0,1).'.' : '' }} — {{ $official->resident->address }}
                    </option>
                @else
                    <option value="">Search registered residents…</option>
                @endif
            </select>
        </div>

        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="full_name" id="officialFullName" class="form-control @error('full_name') is-invalid @enderror"
                       value="{{ old('full_name', $official->full_name) }}"
                       @if($official->resident_id) readonly style="background:var(--surface-alt,#f5f5f5)" @endif required>
                @error('full_name')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Contact Number</label>
                <input type="text" name="contact_number" id="officialContact" class="form-control @error('contact_number') is-invalid @enderror"
                       value="{{ old('contact_number', $official->contact_number) }}">
                @error('contact_number')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-section-title">Position & Committee</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Position <span style="color:var(--crimson)">*</span></label>
                <select name="position" id="s2Position" class="form-control @error('position') is-invalid @enderror" required>
                    @foreach($positions as $p)
                        <option value="{{ $p }}" {{ old('position', $official->position) === $p ? 'selected' : '' }}>{{ $p }}</option>
                    @endforeach
                </select>
                @error('position')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Committee</label>
                <select name="committee" id="s2Committee" class="form-control @error('committee') is-invalid @enderror">
                    <option value="">None / N/A</option>
                    @foreach($committees as $c)
                        <option value="{{ $c }}" {{ old('committee', $official->committee) === $c ? 'selected' : '' }}>{{ $c }}</option>
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
                       value="{{ old('term_start', $official->term_start ? \Carbon\Carbon::parse($official->term_start)->format('Y-m-d') : '') }}" required>
                @error('term_start')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Term End <span style="color:var(--crimson)">*</span></label>
                <input type="date" name="term_end" class="form-control @error('term_end') is-invalid @enderror"
                       value="{{ old('term_end', $official->term_end ? \Carbon\Carbon::parse($official->term_end)->format('Y-m-d') : '') }}" required>
                @error('term_end')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="justify-content:flex-end;padding-bottom:4px">
                <label class="form-label">&nbsp;</label>
                <label class="form-check">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $official->is_active) ? 'checked' : '' }}>
                    Currently Active
                </label>
            </div>
        </div>

        <div class="form-section-title">Photo</div>
        <div class="form-group">
            @if($official->photo_path)
            <div style="margin-bottom:12px">
                <div style="display:flex;align-items:center;gap:14px;flex-wrap:wrap">
                    <img src="{{ asset('storage/'.$official->photo_path) }}" alt="Current Photo"
                         id="current-photo-preview"
                         style="width:80px;height:80px;border-radius:50%;object-fit:cover;border:2px solid var(--border)">
                    <div>
                        <div style="font-size:13px;color:var(--text-muted);margin-bottom:8px">Current photo</div>
                        <label style="display:inline-flex;align-items:center;gap:7px;cursor:pointer;font-size:13px;color:var(--danger,#c0392b);user-select:none"
                               id="remove-photo-label">
                            <input type="checkbox" name="remove_photo" value="1" id="remove-photo-cb"
                                   style="accent-color:var(--danger,#c0392b);width:15px;height:15px;cursor:pointer"
                                   {{ old('remove_photo') ? 'checked' : '' }}>
                            Remove current photo
                        </label>
                    </div>
                </div>
            </div>
            @endif
            <label class="form-label">Upload New Photo</label>
            <input type="file" name="photo_path" id="photo-upload" class="form-control" accept="image/*">
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
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

    // Auto-fill when a resident is selected
    $('#s2ResidentLink').on('select2:select', function (e) {
        var data = e.params.data;
        $('#officialFullName').val(data.full_name || '').trigger('change')
            .prop('readonly', true).css('background', 'var(--surface-alt, #f5f5f5)');
        if (data.contact_number) {
            $('#officialContact').val(data.contact_number).trigger('change');
        }
    });

    // Unlock name field if resident link cleared
    $('#s2ResidentLink').on('select2:clear', function () {
        $('#officialFullName').prop('readonly', false).css('background', '');
    });

    // Remove-photo checkbox: dim the preview and disable the file input
    var $cb      = $('#remove-photo-cb');
    var $preview = $('#current-photo-preview');
    var $upload  = $('#photo-upload');

    function syncRemoveState() {
        if ($cb.is(':checked')) {
            $preview.css({ opacity: '0.3', filter: 'grayscale(100%)' });
            $upload.prop('disabled', true).val('');
        } else {
            $preview.css({ opacity: '1', filter: 'none' });
            $upload.prop('disabled', false);
        }
    }

    $cb.on('change', syncRemoveState);
    syncRemoveState(); // run on page load in case old() is checked

    // If user picks a new file, auto-uncheck remove
    $upload.on('change', function () {
        if (this.files.length) {
            $cb.prop('checked', false);
            syncRemoveState();
        }
    });
});
</script>
@endpush