@props([
    'name',
    'label',
    'type'     => 'text',
    'required' => false,
    'hint'     => null,
])

<div class="form-group">
    <label class="form-label">
        {{ $label }}
        @if($required)<span style="color:var(--crimson)">*</span>@endif
    </label>

    {{ $slot }}

    @if($hint)
        <div style="font-size:11px;color:var(--text-subtle);margin-top:3px">{{ $hint }}</div>
    @endif

    @error($name)
        <span class="invalid-feedback" style="display:block">
            <i class="fas fa-circle-exclamation"></i> {{ $message }}
        </span>
    @enderror
</div>
