@extends('layouts.app')

@section('title', 'Edit User')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle">{{ $user->name }} — {{ $user->role }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('users.update', $user) }}" id="edit-user-form">
@csrf @method('PUT')

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-pen"></i> Account Details</span>
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <span style="font-size:13px;color:var(--text-muted)">{{ $user->email }}</span>
        </div>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <span style="font-size:11px;color:var(--crimson)">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Email Address <span style="color:var(--crimson)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <span style="font-size:11px;color:var(--crimson)">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section-title">Access Role</div>
        <div class="form-group mb-6">
            <label class="form-label">Role <span style="color:var(--crimson)">*</span></label>
            <select name="role" class="form-control" required style="max-width:320px"
                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                @foreach($roles as $r)
                    <option value="{{ $r }}" {{ old('role', $user->role) === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
            @if($user->id === auth()->id())
                <input type="hidden" name="role" value="{{ $user->role }}">
                <span style="font-size:11px;color:var(--text-subtle);margin-top:4px;display:block">
                    <i class="fas fa-lock" style="font-size:10px"></i> You cannot change your own role.
                </span>
            @endif
            @error('role')
                <span style="font-size:11px;color:var(--crimson)">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-section-title">Change Password <span style="font-size:11px;color:var(--text-subtle);text-transform:none;font-weight:400;letter-spacing:0">(leave blank to keep current)</span></div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">New Password</label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" autocomplete="new-password">
                @error('password')
                    <span style="font-size:11px;color:var(--crimson)">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">Confirm New Password</label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat new password" autocomplete="new-password">
            </div>
        </div>

        {{-- Account info strip --}}
        <div style="margin-top:24px;padding-top:16px;border-top:1px solid var(--border);display:flex;gap:24px;flex-wrap:wrap">
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Verified</div>
                <div style="font-size:13px;color:var(--text);margin-top:2px">
                    @if($user->email_verified_at)
                        <span class="badge badge-green">Yes</span>
                    @else
                        <span class="badge badge-gray">No</span>
                    @endif
                </div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Member Since</div>
                <div style="font-size:13px;color:var(--text);margin-top:2px">{{ $user->created_at->format('F d, Y') }}</div>
            </div>
            <div>
                <div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-subtle)">Last Updated</div>
                <div style="font-size:13px;color:var(--text);margin-top:2px">{{ $user->updated_at->format('F d, Y') }}</div>
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-floppy-disk"></i> Save Changes
    </button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
    @if($user->id !== auth()->id())
    {{-- Delete button triggers separate form outside main form --}}
    <button type="button" class="btn btn-danger" style="margin-left:auto"
            onclick="document.getElementById('delete-user-form').submit()"
            onmousedown="return confirm('Permanently delete {{ $user->name }}? This cannot be undone.')">
        <i class="fas fa-trash"></i> Delete User
    </button>
    @endif
</div>

</form>

{{-- Delete form is OUTSIDE the edit form to prevent nesting --}}
@if($user->id !== auth()->id())
<form method="POST" action="{{ route('users.destroy', $user) }}" id="delete-user-form">
    @csrf @method('DELETE')
</form>
@endif

@endsection