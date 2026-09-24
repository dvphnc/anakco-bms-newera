{{-- Relationship to the household head (Task 1.2). Choosing "Head" makes this
     resident the head of their household. --}}
<div class="form-group">
    <label class="form-label" for="s2Relationship">
        Relationship to Household Head
        <span class="help-icon" data-tippy-content="Choose 'Head' to make this resident the head of their household. If nobody is marked as head, the eldest living member is used.">?</span>
    </label>
    <select name="relationship_to_head" id="s2Relationship" class="form-control @error('relationship_to_head') is-invalid @enderror">
        <option value="">— Not set —</option>
        @foreach(\App\Models\Resident::RELATIONSHIPS as $rel)
            <option value="{{ $rel }}" @selected(old('relationship_to_head', $resident?->relationship_to_head) === $rel)>{{ $rel }}</option>
        @endforeach
    </select>
    @error('relationship_to_head')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
</div>

@push('scripts')
<script>
    $('#s2Relationship').select2({ dropdownParent: $('body'), width: '100%', minimumResultsForSearch: Infinity });
</script>
@endpush
