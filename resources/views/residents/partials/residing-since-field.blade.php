{{-- "Living in Barangay New Era since" — years of residency are calculated from it
     (profile + Certificate of Residency). Replaces the old Years of Residency number,
     which had no column and was never saved. Used by residents-create and -edit. --}}
<div class="form-group">
    <label class="form-label" for="residingSince">
        Residing in New Era Since
        <span class="help-icon" data-tippy-content="When this person started living in Barangay New Era. If only the year is known, use January 1 of that year. Years of residency are calculated from this and printed on the Certificate of Residency.">?</span>
    </label>
    <div style="display:flex;gap:6px">
        <input type="date" name="residing_since" id="residingSince" style="flex:1"
               class="form-control @error('residing_since') is-invalid @enderror"
               value="{{ old('residing_since', $resident?->residing_since?->format('Y-m-d')) }}"
               max="{{ now()->toDateString() }}">
        <button type="button" class="btn btn-secondary btn-sm" id="residingSinceBirth"
                title="Use the date of birth — for residents born and raised in New Era">Born here</button>
    </div>
    @error('residing_since')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
</div>

@push('scripts')
<script>
    document.getElementById('residingSinceBirth').addEventListener('click', function () {
        const birth = document.querySelector('input[name="birthdate"]').value;
        if (!birth) { bmsToast('Enter the date of birth first.', 'error'); return; }
        document.getElementById('residingSince').value = birth;
    });
</script>
@endpush
