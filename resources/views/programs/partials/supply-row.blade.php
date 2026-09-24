{{-- One "each claim uses" row. $i is the row index ("__I__" in the JS template). --}}
<div class="supply-row" style="display:grid;grid-template-columns:minmax(0,1fr) 130px auto;gap:10px;align-items:start;margin-bottom:10px">
    <div class="form-group" style="margin:0">
        <select name="supplies[{{ $i }}][relief_supply_id]" aria-label="Supply item"
                class="form-control @error("supplies.$i.relief_supply_id") is-invalid @enderror">
            <option value="">Choose a supply item…</option>
            @foreach($supplies as $s)
                <option value="{{ $s->id }}" @selected((string) ($row['relief_supply_id'] ?? '') === (string) $s->id)>
                    {{ $s->label }}{{ $s->source ? ' · '.$s->source : '' }}{{ $s->date_received ? ' · '.$s->date_received->format('m/d/Y') : '' }}
                </option>
            @endforeach
        </select>
        @error("supplies.$i.relief_supply_id")<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
    </div>
    <div class="form-group" style="margin:0">
        <input type="number" name="supplies[{{ $i }}][quantity_per_claim]" min="1" max="10000" placeholder="Per claim" aria-label="Quantity per claim"
               class="form-control @error("supplies.$i.quantity_per_claim") is-invalid @enderror"
               value="{{ $row['quantity_per_claim'] ?? 1 }}">
        @error("supplies.$i.quantity_per_claim")<span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
    </div>
    <button type="button" class="btn btn-secondary btn-icon remove-supply-row" title="Remove"><i class="fas fa-xmark"></i></button>
</div>
