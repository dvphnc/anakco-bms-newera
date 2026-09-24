{{-- Voter details — shown only while "Registered Voter" is ticked (Task 1.3).
     Used by residents-create ($resident = null) and residents-edit. --}}
@php
    $isVoter = (bool) old('is_voter', $resident?->is_voter);
@endphp
<div id="voterDetails" class="form-grid-2" style="margin-top:18px;{{ $isVoter ? '' : 'display:none' }}">
    <div class="form-group">
        <label class="form-label" for="precinctNo">
            Precinct No. <span style="font-weight:400;color:var(--text-subtle)">(optional)</span>
            <span class="help-icon" data-tippy-content="The COMELEC precinct where the resident votes, as printed on their voter's certification.">?</span>
        </label>
        <input type="text" name="precinct_no" id="precinctNo" maxlength="20"
               class="form-control @error('precinct_no') is-invalid @enderror"
               value="{{ old('precinct_no', $resident?->precinct_no) }}" placeholder="e.g. 0123A">
        @error('precinct_no')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
    </div>
    <div class="form-group">
        <label class="form-label" for="votersIdNo">
            Voter's ID No. <span style="font-weight:400;color:var(--text-subtle)">(optional)</span>
        </label>
        <input type="text" name="voters_id_no" id="votersIdNo" maxlength="30"
               class="form-control @error('voters_id_no') is-invalid @enderror"
               value="{{ old('voters_id_no', $resident?->voters_id_no) }}">
        @error('voters_id_no')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
    </div>
</div>

@push('scripts')
<script>
    (function () {
        const box = document.querySelector('input[name="is_voter"]');
        const details = document.getElementById('voterDetails');
        if (!box || !details) return;
        box.addEventListener('change', () => { details.style.display = box.checked ? '' : 'none'; });
    })();
</script>
@endpush
