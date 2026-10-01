{{-- Religion, Family of Ministers and Pabahay unit (Part 2). Admin only: for anyone else
     none of this is rendered, and the controller ignores these fields.
     Expects $religions and $pabahayUnits (from ResidentController); $resident is null on create. --}}
@can('manage-religion-data')
    @php
        $currentReligion = old('religion_id', $resident?->religion_id);
        $currentMinister = (bool) old('is_minister_family', $resident?->is_minister_family ?? false);
        $currentUnit     = old('pabahay_unit_id', $resident?->pabahay_unit_id);
    @endphp

    <div class="form-group">
        <label class="form-label" for="religionId">
            Religion
            <span class="help-icon" data-tippy-content="Only the Admin can see and change religion information. Manage the list under Religions in the menu.">?</span>
        </label>
        <select name="religion_id" id="religionId" class="form-control @error('religion_id') is-invalid @enderror">
            <option value="">Not recorded</option>
            @foreach($religions as $religion)
                <option value="{{ $religion->id }}" data-inc="{{ $religion->is_inc ? 1 : 0 }}" @selected((string) $currentReligion === (string) $religion->id)>
                    {{ $religion->name }}{{ $religion->is_active ? '' : ' (hidden)' }}
                </option>
            @endforeach
        </select>
        @error('religion_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
    </div>

    {{-- Shown only for INC members --}}
    <div class="form-group" id="ministerBlock" style="grid-column:1/-1;display:none;background:var(--gold-pale);border:1px solid var(--gold-border);border-radius:var(--radius-sm);padding:14px 16px">
        <label class="form-check">
            <input type="checkbox" name="is_minister_family" value="1" id="isMinisterFamily" @checked($currentMinister)>
            <span><strong>Family of Ministers</strong>. The resident is a minister or part of a minister's family.</span>
        </label>
        @error('is_minister_family')<span class="invalid-feedback" style="display:block"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror

        {{-- Shown only for Family of Ministers --}}
        <div id="pabahayBlock" style="display:none;margin-top:12px;max-width:420px">
            <label class="form-label" for="pabahayUnitId">Pabahay unit</label>
            <select name="pabahay_unit_id" id="pabahayUnitId" class="form-control @error('pabahay_unit_id') is-invalid @enderror">
                <option value="">Not assigned yet</option>
                @foreach($pabahayUnits->groupBy(fn ($u) => $u->pabahay->name) as $pabahayName => $units)
                    <optgroup label="{{ $pabahayName }}">
                        @foreach($units as $unit)
                            <option value="{{ $unit->id }}" @selected((string) $currentUnit === (string) $unit->id)>
                                Unit {{ $unit->unit_no }} · {{ $unit->living_count }} living{{ $unit->is_active && $unit->pabahay->is_active ? '' : ' (off)' }}
                            </option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
            @error('pabahay_unit_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            @if($pabahayUnits->isEmpty())
                <span class="td-muted" style="font-size:12px;display:block;margin-top:4px">No Pabahay units yet. Add them under Pabahay in the menu.</span>
            @endif
        </div>
    </div>

    <script>
    (function () {
        const religion = document.getElementById('religionId');
        const minister = document.getElementById('isMinisterFamily');
        const unit     = document.getElementById('pabahayUnitId');
        const block    = document.getElementById('ministerBlock');
        const pabahay  = document.getElementById('pabahayBlock');

        // INC → can be a Family of Ministers → can have a Pabahay unit.
        // Switching a level off clears the levels below it (the server does the same).
        function sync() {
            const isInc = religion.selectedOptions[0] && religion.selectedOptions[0].dataset.inc === '1';
            if (!isInc) minister.checked = false;
            block.style.display = isInc ? '' : 'none';
            const showUnit = isInc && minister.checked;
            if (!showUnit) unit.value = '';
            pabahay.style.display = showUnit ? '' : 'none';
        }
        religion.addEventListener('change', sync);
        minister.addEventListener('change', sync);
        sync();
    })();
    </script>
@endcan
