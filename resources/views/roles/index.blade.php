@extends('layouts.app')
@section('title', 'Roles & Permissions')
@section('content')

@php
    $editable = $roles->where('is_system', false);
    $me       = auth()->user();
@endphp

<div class="page-header">
    <div>
        <h1 class="page-title">Roles &amp; Permissions</h1>
        <p class="page-subtitle">Choose what each role can do. Changes apply on the person's next page load.</p>
    </div>
    <div class="page-actions">
        <button type="button" class="btn btn-primary" onclick="openRoleModal()">
            <i class="fas fa-plus"></i> New Role
        </button>
    </div>
</div>

{{-- Roles --}}
<div class="rp-roles mb-6">
    @foreach($roles as $role)
    <div class="rp-role {{ $role->is_system ? 'is-system' : '' }}">
        <div class="rp-role-top">
            <div class="rp-role-name">
                <i class="fas {{ $role->is_system ? 'fa-lock' : 'fa-user-tag' }}"></i>
                {{ $role->name }}
            </div>
            @if($role->id === $myRoleId)<span class="badge badge-yellow">Your role</span>@endif
        </div>
        <div class="rp-role-desc">{{ $role->description ?: 'No description.' }}</div>
        <div class="rp-role-meta">
            <span><i class="fas fa-users"></i> {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}</span>
            <span><i class="fas fa-key"></i>
                {{ $role->is_system ? 'All' : $role->permissions->count() }} {{ $role->is_system ? '' : 'of '.$groups->flatten()->count() }} permissions
            </span>
        </div>
        @unless($role->is_system)
        <div class="rp-role-actions">
            <button type="button" class="btn btn-secondary btn-sm"
                    onclick='openRoleModal(@json(['id' => $role->id, 'name' => $role->name, 'description' => $role->description]))'>
                <i class="fas fa-pen"></i> Rename
            </button>
            <form method="POST" action="{{ route('roles.destroy', $role) }}"
                  data-confirm="Archive the role &quot;{{ $role->name }}&quot;? You can restore it from the Recycle Bin."
                  data-confirm-title="Archive Role" data-confirm-ok="Archive">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-secondary btn-sm" style="color:var(--crimson)"
                        @if($role->users_count) disabled title="Move its users to another role first" @endif>
                    <i class="fas fa-box-archive"></i> Archive
                </button>
            </form>
        </div>
        @else
        <div class="rp-role-locked"><i class="fas fa-shield-halved"></i> Locked, so the system can never be locked out.</div>
        @endunless
    </div>
    @endforeach
</div>

{{-- Matrix --}}
<form method="POST" action="{{ route('roles.permissions') }}" id="matrixForm" class="card">
    @csrf @method('PUT')
    @foreach($editable as $role)
        <input type="hidden" name="roles[]" value="{{ $role->id }}">
    @endforeach

    <div class="card-header">
        <span class="card-title"><i class="fas fa-table-cells"></i> Permissions</span>
        <span style="font-size:13px;color:var(--text-muted)">{{ $groups->flatten()->count() }} actions</span>
    </div>

    <div class="rp-scroll">
        <table class="rp-table">
            <thead>
                <tr>
                    <th class="rp-first">Action</th>
                    @foreach($roles as $role)
                    <th class="rp-col">{{ $role->name }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
            @foreach($groups as $group => $permissions)
                <tr class="rp-group" data-group="{{ $loop->index }}">
                    <th class="rp-first" scope="rowgroup">{{ $group }}</th>
                    @foreach($roles as $role)
                    <td class="rp-col">
                        @unless($role->is_system)
                        <input type="checkbox" class="rp-check rp-all" data-role="{{ $role->id }}" data-group="{{ $loop->parent->index }}"
                               aria-label="All {{ $group }} actions for {{ $role->name }}" title="Tick all in {{ $group }}">
                        @endunless
                    </td>
                    @endforeach
                </tr>
                @foreach($permissions as $permission)
                <tr>
                    <td class="rp-first">
                        <div class="rp-label">
                            {{ $permission->label }}
                            @if(in_array($permission->key, $sensitive))
                            <span class="rp-sensitive" title="Gives access to private data or to access control itself">Sensitive</span>
                            @endif
                        </div>
                        <div class="rp-key">{{ $permission->key }}</div>
                    </td>
                    @foreach($roles as $role)
                    @php
                        $has    = $role->is_system || $role->permissions->contains('id', $permission->id);
                        $locked = $role->is_system
                            || ($role->id === $myRoleId && ! $me->isAdmin())
                            || ! $me->hasPermission($permission->key);
                        $why = $role->is_system ? 'The Admin role always has every permission'
                            : ($role->id === $myRoleId ? 'You cannot change your own role'
                            : 'You can only give permissions you have yourself');
                    @endphp
                    <td class="rp-col">
                        <input type="checkbox" class="rp-check {{ $locked ? '' : 'rp-perm' }}"
                               @unless($locked) name="perms[{{ $role->id }}][]" @endunless
                               value="{{ $permission->key }}"
                               data-role="{{ $role->id }}" data-group="{{ $loop->parent->parent->index }}"
                               data-sensitive="{{ in_array($permission->key, $sensitive) ? 1 : 0 }}"
                               aria-label="{{ $permission->label }} for {{ $role->name }}"
                               @checked($has) @disabled($locked) @if($locked) title="{{ $why }}" @endif>
                    </td>
                    @endforeach
                </tr>
                @endforeach
            @endforeach
            </tbody>
        </table>
    </div>

    <div class="rp-savebar" id="saveBar">
        <span id="saveMsg" style="font-size:13px;color:var(--text-muted)">No changes yet.</span>
        <div style="display:flex;gap:8px">
            <button type="button" class="btn btn-secondary" id="resetBtn" onclick="resetMatrix()" disabled>Undo changes</button>
            <button type="submit" class="btn btn-primary" id="saveBtn" disabled><i class="fas fa-floppy-disk"></i> Save Permissions</button>
        </div>
    </div>
</form>

@push('modals')
<div id="roleModal" class="rp-modal" onclick="if(event.target===this)closeRoleModal()">
    <div class="rp-modal-box" role="dialog" aria-modal="true" aria-labelledby="roleModalTitle">
        <div class="rp-modal-head">
            <span id="roleModalTitle"><i class="fas fa-user-tag" style="margin-right:8px;color:var(--gold)"></i><span>New Role</span></span>
            <button type="button" onclick="closeRoleModal()" class="rp-x" aria-label="Close"><i class="fas fa-xmark"></i></button>
        </div>
        <form method="POST" id="roleForm" action="{{ route('roles.store') }}" style="padding:24px">
            @csrf
            <input type="hidden" name="_method" id="roleMethod" value="POST">
            <input type="hidden" name="_role_id" id="roleId" value="{{ old('_role_id') }}">
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label" for="roleName">Role name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" id="roleName" class="form-control" maxlength="50" required
                       value="{{ old('name') }}" placeholder="e.g. Health Worker">
                @error('name')<span class="invalid-feedback" style="display:block"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>@enderror
            </div>
            <div class="form-group" style="margin-bottom:16px">
                <label class="form-label" for="roleDesc">What is this role for?</label>
                <input type="text" name="description" id="roleDesc" class="form-control" maxlength="255"
                       value="{{ old('description') }}" placeholder="Optional">
            </div>
            <div class="form-group" id="copyGroup" style="margin-bottom:24px">
                <label class="form-label" for="roleCopy">Start with the permissions of</label>
                <select name="copy_from" id="roleCopy" class="form-control">
                    <option value="">Nothing (tick them yourself)</option>
                    @foreach($roles as $r)
                    <option value="{{ $r->id }}" @selected(old('copy_from') == $r->id)>{{ $r->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="closeRoleModal()" class="btn btn-secondary">Cancel</button>
                <button type="submit" class="btn btn-primary" id="roleSubmit"><i class="fas fa-check"></i> Create Role</button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('styles')
<style>
    .rp-roles { display:grid; grid-template-columns:repeat(auto-fill, minmax(260px, 1fr)); gap:16px; }
    .rp-role { background:var(--surface); border:1px solid var(--border); border-radius:var(--radius); padding:18px 20px; display:flex; flex-direction:column; gap:10px; }
    .rp-role.is-system { border-color:var(--navy); box-shadow:inset 3px 0 0 var(--gold); }
    .rp-role-top { display:flex; align-items:center; justify-content:space-between; gap:8px; }
    .rp-role-name { font-size:16px; font-weight:700; color:var(--navy); display:flex; align-items:center; gap:8px; }
    .rp-role-name i { color:var(--gold); font-size:14px; }
    .rp-role-desc { font-size:13px; color:var(--text-muted); line-height:1.5; flex:1; }
    .rp-role-meta { display:flex; gap:16px; flex-wrap:wrap; font-size:13px; color:var(--text-muted); }
    .rp-role-meta i { color:var(--text-subtle); margin-right:4px; }
    .rp-role-actions { display:flex; gap:8px; }
    .rp-role-locked { font-size:12px; color:var(--text-subtle); line-height:1.5; }

    /* the card clips its children, which would stop the save bar from sticking */
    #matrixForm.card { overflow:visible; }
    #matrixForm > .card-header { border-radius:var(--radius) var(--radius) 0 0; }
    .rp-scroll { overflow-x:auto; -webkit-overflow-scrolling:touch; }
    .rp-table { width:100%; border-collapse:separate; border-spacing:0; }
    .rp-table th, .rp-table td { border-bottom:1px solid var(--border); }
    .rp-table thead th { position:sticky; top:0; background:var(--surface2); font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--navy); padding:12px 10px; z-index:2; }
    .rp-first { position:sticky; left:0; background:var(--surface); text-align:left; padding:10px 16px; min-width:260px; z-index:1; }
    .rp-table thead .rp-first { z-index:3; background:var(--surface2); }
    .rp-col { text-align:center; width:120px; min-width:104px; padding:10px; }
    .rp-group th, .rp-group td { background:var(--surface2); }
    .rp-group .rp-first { font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); }
    .rp-label { font-size:14px; color:var(--text); display:flex; align-items:center; gap:8px; flex-wrap:wrap; }
    .rp-key { font-family:ui-monospace, Consolas, monospace; font-size:11px; color:var(--text-subtle); margin-top:2px; }
    .rp-sensitive { font-size:11px; font-weight:700; padding:2px 8px; border-radius:99px; background:var(--crimson-pale); color:var(--crimson); }
    .rp-check { width:18px; height:18px; accent-color:var(--navy); cursor:pointer; vertical-align:middle; }
    .rp-check:disabled { cursor:not-allowed; opacity:0.55; }
    .rp-check.is-changed { outline:2px solid var(--gold); outline-offset:2px; border-radius:3px; }
    tbody tr:not(.rp-group):hover td { background:var(--surface2); }

    .rp-savebar { position:sticky; bottom:0; display:flex; align-items:center; justify-content:space-between; gap:12px; flex-wrap:wrap;
                  padding:14px 20px; background:var(--surface); border-top:1px solid var(--border); border-radius:0 0 var(--radius) var(--radius); z-index:4; }
    .rp-savebar.is-dirty { box-shadow:0 -6px 18px rgba(13,33,68,0.08); }
    .rp-savebar.is-dirty #saveMsg { color:var(--gold); font-weight:600; }

    .rp-modal { display:none; position:fixed; inset:0; background:rgba(13,33,68,0.5); z-index:1000; align-items:center; justify-content:center; }
    .rp-modal-box { background:var(--surface); border-radius:var(--radius-lg); width:100%; max-width:460px; box-shadow:0 20px 60px rgba(0,0,0,0.18); margin:16px; }
    .rp-modal-head { padding:18px 24px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; font-size:15px; font-weight:700; color:var(--navy); }
    .rp-x { background:none; border:none; font-size:18px; color:var(--text-muted); cursor:pointer; padding:4px; line-height:1; }

    @media (max-width: 640px) {
        .rp-first { min-width:190px; padding:10px 12px; }
        .rp-col { min-width:84px; }
        .rp-savebar { padding:12px 16px; }
        .rp-savebar > div { width:100%; }
        .rp-savebar .btn { flex:1; justify-content:center; }
    }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const form   = document.getElementById('matrixForm');
    const boxes  = [...form.querySelectorAll('.rp-perm')];
    const start  = new Map(boxes.map(b => [b, b.checked]));
    const bar    = document.getElementById('saveBar');
    const msg    = document.getElementById('saveMsg');
    const save   = document.getElementById('saveBtn');
    const reset  = document.getElementById('resetBtn');
    let dirty    = false;

    function groupBoxes(role, group) {
        return boxes.filter(b => b.dataset.role === role && b.dataset.group === group);
    }

    // The "tick all" box in each group row shows whether that group is all, some or none ticked
    function refreshGroupToggles() {
        form.querySelectorAll('.rp-all').forEach(t => {
            const list = groupBoxes(t.dataset.role, t.dataset.group);
            const on   = list.filter(b => b.checked).length;
            t.disabled      = list.length === 0;
            t.checked       = list.length > 0 && on === list.length;
            t.indeterminate = on > 0 && on < list.length;
        });
    }

    function refresh() {
        const changed = boxes.filter(b => b.checked !== start.get(b));
        boxes.forEach(b => b.classList.toggle('is-changed', b.checked !== start.get(b)));
        dirty = changed.length > 0;
        bar.classList.toggle('is-dirty', dirty);
        save.disabled = reset.disabled = !dirty;
        msg.textContent = dirty
            ? changed.length + (changed.length === 1 ? ' change' : ' changes') + ' not saved yet.'
            : 'No changes yet.';
        refreshGroupToggles();
    }

    form.addEventListener('change', e => {
        if (e.target.classList.contains('rp-all')) {
            groupBoxes(e.target.dataset.role, e.target.dataset.group).forEach(b => b.checked = e.target.checked);
        }
        refresh();
    });

    window.resetMatrix = function () {
        boxes.forEach(b => b.checked = start.get(b));
        refresh();
    };

    // Giving a sensitive permission asks once more
    form.addEventListener('submit', e => {
        if (form.dataset.confirmed === '1') { dirty = false; return; }
        const risky = boxes.filter(b => b.checked && !start.get(b) && b.dataset.sensitive === '1');
        if (!risky.length) { dirty = false; return; }
        e.preventDefault();
        const names = [...new Set(risky.map(b => b.getAttribute('aria-label')))].join('; ');
        bmsConfirm({
            title: 'Give sensitive permissions?',
            message: 'These give access to private data or to access control itself: ' + names + '.',
            ok: 'Yes, save',
            icon: 'fa-floppy-disk',
            type: 'danger',
        }, function () { form.dataset.confirmed = '1'; dirty = false; form.requestSubmit(); });
    });

    window.addEventListener('beforeunload', e => { if (dirty) { e.preventDefault(); e.returnValue = ''; } });

    refresh();
})();

function openRoleModal(role) {
    const f = document.getElementById('roleForm');
    const editing = !!role;
    document.querySelector('#roleModalTitle span').textContent = editing ? 'Rename Role' : 'New Role';
    document.getElementById('roleSubmit').innerHTML = '<i class="fas fa-check"></i> ' + (editing ? 'Save' : 'Create Role');
    document.getElementById('copyGroup').style.display = editing ? 'none' : '';
    document.getElementById('roleMethod').value = editing ? 'PUT' : 'POST';
    document.getElementById('roleId').value = editing ? role.id : '';
    f.action = editing ? '{{ url('roles') }}/' + role.id : '{{ route('roles.store') }}';
    if (role) {
        document.getElementById('roleName').value = role.name;
        document.getElementById('roleDesc').value = role.description || '';
    } else if (!f.dataset.keepOld) {
        f.reset();
    }
    delete f.dataset.keepOld;
    document.getElementById('roleModal').style.display = 'flex';
    setTimeout(() => document.getElementById('roleName').focus(), 50);
}
function closeRoleModal() { document.getElementById('roleModal').style.display = 'none'; }
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeRoleModal(); });

@if($errors->has('name') || $errors->has('description'))
    // Reopen the form that failed, with what was typed
    document.getElementById('roleForm').dataset.keepOld = '1';
    @if(old('_role_id'))
        openRoleModal({ id: {{ (int) old('_role_id') }}, name: @json(old('name')), description: @json(old('description')) });
    @else
        openRoleModal();
    @endif
@endif
</script>
@endpush

@endsection
