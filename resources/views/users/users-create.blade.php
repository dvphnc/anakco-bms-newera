@extends('layouts.app')

@section('title', 'Add User')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Add User</h1>
        <p class="page-subtitle">Create a new system account</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
</div>

<form method="POST" action="{{ route('users.store') }}">
@csrf

<div class="card mb-6">
    <div class="card-header">
        <span class="card-title"><i class="fas fa-user-plus"></i> Account Details</span>
    </div>
    <div class="card-body">

        <div class="form-section-title">Basic Information</div>
        <div class="form-grid-2 mb-6">
            <div class="form-group">
                <label class="form-label">Full Name <span style="color:var(--crimson)">*</span></label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name') }}" placeholder="Full name" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email Address <span style="color:var(--crimson)">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="{{ old('email') }}" placeholder="user@bms.gov.ph" required>
                @error('email')
                    <span class="invalid-feedback"><i class="fas fa-circle-exclamation"></i> {{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section-title">Access Role</div>
        <div class="form-group mb-6">
            <label class="form-label">Role <span style="color:var(--crimson)">*</span></label>
            <select name="role" class="form-control" required style="max-width:320px">
                <option value="">Select Role</option>
                @foreach($roles as $r)
                    <option value="{{ $r }}" {{ old('role') === $r ? 'selected' : '' }}>{{ $r }}</option>
                @endforeach
            </select>
        </div>

        {{-- Role guide --}}
        <div style="background:var(--surface2);border:1px solid var(--border);border-radius:var(--radius);padding:16px 20px;margin-bottom:24px">
            <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:0.09em;color:var(--navy);margin-bottom:12px">
                <i class="fas fa-info-circle" style="color:var(--gold);margin-right:6px"></i> Role Permissions
            </div>
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:12px">
                @php
                    $roleGuide = [
                        ['role'=>'Admin',     'desc'=>'Full system access. Can manage all records, users, and settings.', 'color'=>'var(--crimson)'],
                        ['role'=>'Secretary', 'desc'=>'Can manage residents, documents, blotter, and businesses. Cannot manage users.', 'color'=>'var(--gold)'],
                        ['role'=>'Committee', 'desc'=>'Access limited to committee pages and related records only.', 'color'=>'var(--navy)'],
                    ];
                @endphp
                @foreach($roleGuide as $rg)
                <div style="background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-sm);padding:12px">
                    <div style="font-size:13px;font-weight:700;color:{{ $rg['color'] }};margin-bottom:5px">{{ $rg['role'] }}</div>
                    <div style="font-size:13px;color:var(--text-muted);line-height:1.5">{{ $rg['desc'] }}</div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="form-section-title">Password</div>
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Password <span style="color:var(--crimson)">*</span></label>
                <input type="password" name="password" class="form-control"
                       placeholder="Min. 8 characters" required autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">Confirm Password <span style="color:var(--crimson)">*</span></label>
                <input type="password" name="password_confirmation" class="form-control"
                       placeholder="Repeat password" required autocomplete="new-password">
            </div>
        </div>

    </div>
</div>

<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-user-plus"></i> Create Account
    </button>
    <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancel</a>
</div>

</form>

@endsection
