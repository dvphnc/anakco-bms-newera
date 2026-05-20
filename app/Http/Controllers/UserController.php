<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('users.users-index', compact('users'));
    }

    public function create()
    {
        $roles = ['Admin', 'Secretary', 'Committee'];

        return view('users.users-create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:Admin,Secretary,Committee',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Admin accounts are always auto-verified
        if ($validated['role'] === 'Admin') {
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
                'admins'   => User::where('role', 'Admin')->count(),
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
        $roles = ['Admin', 'Secretary', 'Committee'];

        return view('users.users-edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:Admin,Secretary,Committee',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // If role is set/changed to Admin, auto-verify
        if ($validated['role'] === 'Admin' && is_null($user->email_verified_at)) {
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
                'admins'   => User::where('role', 'Admin')->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $this->logActivity('deleted', $user);
        $user->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success'  => true,
                'message'  => 'User account deleted successfully.',
                'total'    => User::count(),
                'admins'   => User::where('role', 'Admin')->count(),
                'verified' => User::whereNotNull('email_verified_at')->count(),
            ]);
        }

        return redirect()->route('users.index')->with('success', 'User account deleted successfully.');
    }

    public function verify(User $user)
    {
        \DB::table('users')->where('id', $user->id)->update([
            'email_verified_at' => now(),
        ]);

        return back()->with('success', $user->name.' has been verified successfully.');
    }

    public function unverify(User $user)
    {
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
        if ($user->role === 'Admin') {
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
}
