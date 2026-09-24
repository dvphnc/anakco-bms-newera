{{-- Possible duplicate resident (shown after a paused registration). Sits inside
     the registration <form> so "Save anyway" resubmits the same data. --}}
@if($dupes = session('possibleDuplicates'))
<div class="card mb-6" role="alert" style="border:1px solid #fde047;background:#fefce8">
    <div class="card-body">
        <div style="display:flex;gap:12px;align-items:flex-start">
            <i class="fas fa-user-group" style="color:#a16207;font-size:18px;margin-top:2px"></i>
            <div style="flex:1;min-width:0">
                <div style="font-size:15px;font-weight:700;color:#854d0e">
                    This may be someone who is already registered
                </div>
                <div style="font-size:13px;color:#854d0e;margin-top:3px">
                    Check the record{{ count($dupes) > 1 ? 's' : '' }} below. Registering the same person twice would let them
                    claim benefits twice. If it's the same person, open their record instead.
                </div>

                <div style="margin-top:12px;display:flex;flex-direction:column;gap:8px">
                    @foreach($dupes as $d)
                        <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;background:#fff;
                                    border:1px solid #fde68a;border-radius:var(--radius-sm);padding:10px 12px">
                            <div style="flex:1;min-width:200px">
                                <div style="font-weight:700;color:var(--text)">
                                    {{ $d['name'] }}
                                    <span class="badge {{ $d['badge'] }}" style="margin-left:4px">{{ $d['label'] }}</span>
                                </div>
                                <div class="td-muted">Born {{ $d['birthdate'] }} · {{ $d['purok'] }} · {{ $d['address'] }}</div>
                                @if($d['status'] === 'Transferred')
                                    <div style="font-size:12px;color:#854d0e;margin-top:3px">
                                        <i class="fas fa-circle-info"></i> Recorded as moved out. If they've come back, open their record and use Update Status instead.
                                    </div>
                                @endif
                            </div>
                            <a href="{{ $d['url'] }}" class="btn btn-secondary btn-sm" target="_blank">
                                <i class="fas fa-up-right-from-square"></i> Open record
                            </a>
                        </div>
                    @endforeach
                </div>

                @if(session('duplicatePhotoDropped'))
                    <div style="font-size:12px;color:#854d0e;margin-top:10px">
                        <i class="fas fa-camera"></i> The photo you attached wasn't kept — please select it again before saving.
                    </div>
                @endif

                <div style="margin-top:14px">
                    <button type="submit" name="confirm_not_duplicate" value="1" class="btn btn-secondary">
                        <i class="fas fa-user-plus"></i> This is a different person — register anyway
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
