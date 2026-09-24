{{-- Household assignment (Task 1.2). Automatic groups residents by address;
     Manual keeps a household staff chose (e.g. separate families sharing a
     compound). Used by residents-create ($resident = null) and residents-edit. --}}
@php
    $mode = old('household_mode', $resident?->household_assignment ?? 'auto');
@endphp
<div class="form-group" style="grid-column:span 2">
    <label class="form-label">
        Household
        <span class="help-icon" data-tippy-content="Automatic: everyone at the same address and purok is grouped into one household. Choose manually when people share an address but are separate households, such as a compound or boarders.">?</span>
    </label>
    <div style="display:flex;gap:22px;flex-wrap:wrap;margin-bottom:8px">
        <label class="form-check">
            <input type="radio" name="household_mode" value="auto" @checked($mode !== 'manual')>
            <span><strong>Automatic</strong> — group by address</span>
        </label>
        <label class="form-check">
            <input type="radio" name="household_mode" value="manual" @checked($mode === 'manual')>
            <span><strong>Choose manually</strong></span>
        </label>
    </div>

    <div id="hhAutoHint" class="hh-hint" aria-live="polite"
         data-match-url="{{ route('households.match') }}"
         data-resident-id="{{ $resident?->id }}"></div>

    <div id="hhManualPick" style="{{ $mode === 'manual' ? '' : 'display:none' }}">
        <select name="household_id" id="s2Household" class="form-control @error('household_id') is-invalid @enderror">
            <option value=""></option>
            @foreach($households as $hh)
                <option value="{{ $hh->id }}" @selected(old('household_id', $resident?->household_id) == $hh->id)>
                    {{ $hh->household_number }} — {{ $hh->household_head ?? 'no members yet' }} · {{ $hh->address }}
                </option>
            @endforeach
        </select>
    </div>
    @error('household_id')<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
</div>

@push('styles')
<style>
    .hh-hint { font-size:13px; color:var(--text-muted); padding:9px 12px; border-radius:var(--radius-sm);
               background:var(--surface2); border:1px solid var(--border); display:flex; align-items:center; gap:8px; }
    .hh-hint:empty { display:none; }
    .hh-hint.is-match  { background:rgba(22,163,74,.07); border-color:rgba(22,163,74,.3); color:#166534; }
    .hh-hint.is-new    { background:var(--gold-pale); border-color:var(--gold-border); color:#92600A; }
    .hh-hint a { color:inherit; font-weight:700; text-decoration:underline; }
</style>
@endpush

@push('scripts')
<script>
$(function () {
    const $hint = $('#hhAutoHint');
    const url = $hint.data('match-url');
    const residentId = $hint.data('resident-id') || null;
    const esc = s => $('<div>').text(s == null ? '' : String(s)).html();

    $('#s2Household').select2({
        dropdownParent: $('body'), width: '100%', allowClear: true,
        placeholder: 'Search by household number, head, or address…',
    });

    const mode = () => $('input[name="household_mode"]:checked').val();

    function show(cls, html) {
        $hint.removeClass('is-match is-new').addClass(cls || '').html(html);
    }

    let timer, seq = 0;
    function lookup() {
        const address = ($('input[name="address"]').val() || '').trim();
        const purok = $('#s2Purok').val();
        if (!address || !purok) {
            show('', '<i class="fas fa-circle-info"></i> Enter the purok and full address to see which household this resident will join.');
            return;
        }
        clearTimeout(timer);
        timer = setTimeout(function () {
            const mine = ++seq;
            axios.get(url, { params: { address: address, purok_id: purok, resident_id: residentId } })
                .then(function ({ data }) {
                    if (mine !== seq) return;           // a newer lookup is on its way
                    const h = data.household;
                    if (!h) {
                        show('is-new', '<i class="fas fa-house-circle-exclamation"></i> No household at this address yet — a new one will be created.');
                    } else if (h.is_current) {
                        show('is-match', '<i class="fas fa-house-circle-check"></i> Stays in <a href="' + esc(h.url) + '" target="_blank">' + esc(h.number) + '</a> · ' + h.members + ' living member(s)');
                    } else {
                        show('is-match', '<i class="fas fa-house-circle-check"></i> Will join <a href="' + esc(h.url) + '" target="_blank">' + esc(h.number) + '</a> · '
                            + h.members + ' living member(s)' + (h.head ? ' · Head: ' + esc(h.head) : ''));
                    }
                })
                .catch(function () { if (mine === seq) show('', ''); });
        }, 400);
    }

    function applyMode() {
        const manual = mode() === 'manual';
        $('#hhManualPick').toggle(manual);
        $hint.toggle(!manual);
        if (!manual) lookup();
    }

    $('input[name="household_mode"]').on('change', applyMode);
    $('input[name="address"]').on('input', function () { if (mode() === 'auto') lookup(); });
    $('#s2Purok').on('change', function () { if (mode() === 'auto') lookup(); });
    applyMode();
});
</script>
@endpush
