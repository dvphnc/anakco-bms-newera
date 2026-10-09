<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $users = User::with('assignedRole')->orderBy('name')->paginate(15);
        $roles = $this->assignableRoles();

        return view('users.users-index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = $this->assignableRoles();

        return view('users.users-create', compact('roles'));
    }

    public function store(Request $request)
    {
        $this->rejectArchivedEmail($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => $this->roleRule(),
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Admin accounts are always auto-verified
        if ($this->isAdminRole($validated['role'])) {
            $validated['email_verified_at'] = now();
        }

        $record = User::create($validated);
        $this->logActivity('created', $record);

        if ($request->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'User account created successfully.',
                'row_html' => view('users._row', ['user' => $record])->render(),
                'total'    => User::count(),
                'admins'   => User::admins()->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User account created successfully.');
    }

    public function show(User $user)
    {
        return redirect()->route('users.edit', $user);
    }

    public function edit(User $user)
    {
        $this->guardAdminAccount($user);
        $roles = $this->assignableRoles();

        return view('users.users-edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $this->guardAdminAccount($user);
        $this->rejectArchivedEmail($request);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => $this->roleRule(),
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Nobody changes their own role (it could lock them out, or raise their own access)
        if ($user->id === auth()->id()) {
            $validated['role'] = $user->role;
        }

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // If role is set/changed to Admin, auto-verify
        if ($this->isAdminRole($validated['role']) && is_null($user->email_verified_at)) {
            $validated['email_verified_at'] = now();
        }

        $oldData = $user->getOriginal();
        $user->update($validated);
        $this->logActivity('updated', $user, $oldData, $user->fresh()->toArray());

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User account updated successfully.',
                'user'    => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'admins'   => User::admins()->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        $this->guardAdminAccount($user);

        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot archive your own account.');
        }

        $this->logActivity('deleted', $user);
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'User account archived. The person can no longer sign in. You can restore it from the Recycle Bin.',
                'total'    => User::count(),
                'admins'   => User::admins()->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User account archived. The person can no longer sign in. You can restore it from the Recycle Bin.');
    }

    public function verify(User $user)
    {
        $this->guardAdminAccount($user);
        \DB::table('users')->where('id', $user->id)->update([
            'email_verified_at' => now(),
        ]);

        return back()->with('success', $user->name.' has been verified successfully.');
    }

    public function unverify(User $user)
    {
        $this->guardAdminAccount($user);
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot unverify your own account.');
        }
        \DB::table('users')->where('id', $user->id)->update([
            'email_verified_at' => null,
        ]);

        return back()->with('success', $user->name.' verification has been removed.');
    }

    public function verifyToggle(User $user)
    {
        // Admin accounts are always verified — cannot be toggled
        if ($user->isAdmin()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Admin accounts are always verified and cannot be changed.'], 422);
            }
            return back()->with('error', 'Admin accounts are always verified.');
        }

        $willVerify = is_null($user->email_verified_at);

        if (!$willVerify && $user->id === auth()->id()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'You cannot unverify your own account.'], 422);
            }
            return back()->with('error', 'You cannot unverify your own account.');
        }

        \DB::table('users')->where('id', $user->id)->update([
            'email_verified_at' => $willVerify ? now() : null,
        ]);

        $message = $willVerify ? $user->name.' verified successfully.' : $user->name.' verification removed.';

        if (request()->expectsJson()) {
            return response()->json([
                'success'       => true,
                'message'       => $message,
                'verified'      => $willVerify,
                'verifiedCount' => User::whereNotNull('email_verified_at')->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /** Roles that can be picked in the user forms. Only an Admin can hand out the Admin role. */
    private function assignableRoles()
    {
        return Role::orderByDesc('is_system')->orderBy('name')
            ->when(! auth()->user()->isAdmin(), fn ($q) => $q->where('is_system', false))
            ->pluck('name');
    }

    private function roleRule(): array
    {
        return ['required', Rule::in($this->assignableRoles()->all())];
    }

    private function isAdminRole(string $name): bool
    {
        return (bool) Role::where('name', $name)->value('is_system');
    }

    /** Someone given "Manage user accounts" who is not an Admin cannot touch Admin accounts (Part 3.2) */
    private function guardAdminAccount(User $user): void
    {
        if ($user->isAdmin() && ! auth()->user()->isAdmin()) {
            abort(403, 'Only an Admin can change an Admin account.');
        }
    }

    /** An archived account keeps its email, so say where it is instead of "already taken" (Part 3.1) */
    private function rejectArchivedEmail(Request $request): void
    {
        if ($request->filled('email') && User::onlyTrashed()->where('email', $request->email)->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'email' => 'This email belongs to an archived account. Restore it from the Recycle Bin instead.',
            ]);
        }
    }
}
