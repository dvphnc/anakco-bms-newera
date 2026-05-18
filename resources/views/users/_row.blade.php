@php
$roleCls = match($user->role) {
    'Admin'     => 'badge-red',
    'Secretary' => 'badge-yellow',
    'Committee' => 'badge-navy',
    default     => 'badge-gray'
};
@endphp
<tr data-id="{{ $user->id }}">
    <td>
        <div style="display:flex;align-items:center;gap:10px">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--navy),var(--navy-mid));display:flex;align-items:center;justify-content:center;font-weight:700;color:#fff;font-size:13px;flex-shrink:0">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div style="font-weight:600;font-size:14px">{{ $user->name }}</div>
        </div>
    </td>
    <td class="td-muted">{{ $user->email }}</td>
    <td><span class="badge {{ $roleCls }}">{{ $user->role }}</span></td>
    <td><span class="badge badge-gray">Unverified</span></td>
    <td class="td-muted">{{ $user->created_at->format('M d, Y') }}</td>
    <td>
        <div style="display:flex;justify-content:flex-end;gap:6px">
            <button data-edit-btn
                    onclick="openEditModal({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->role }}')"
                    class="btn btn-secondary btn-sm btn-icon" title="Edit">
                <i class="fas fa-pen"></i>
            </button>
            <button data-verify-btn onclick="toggleVerify({{ $user->id }}, false)"
                    class="btn btn-secondary btn-sm btn-icon" title="Verify">
                <i class="fas fa-user-check"></i>
            </button>
            <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                    class="btn btn-danger btn-sm btn-icon" title="Delete">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </td>
</tr>
