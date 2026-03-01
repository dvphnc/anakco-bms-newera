<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // -------------------------------------------------------
    // INDEX — List all users
    // -------------------------------------------------------
    public function index()
    {
        $users = User::orderBy('name')->paginate(15);

        return view('users.users-index', compact('users'));
    }

    // -------------------------------------------------------
    // CREATE — Show add user form
    // -------------------------------------------------------
    public function create()
    {
        $roles = ['Admin', 'Secretary', 'Committee'];

        return view('users.users-create', compact('roles'));
    }

    // -------------------------------------------------------
    // STORE — Save new user
    // -------------------------------------------------------
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:Admin,Secretary,Committee',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User account created successfully.');
    }

    // -------------------------------------------------------
    // SHOW — not used, redirect to edit
    // -------------------------------------------------------
    public function show(User $user)
    {
        return redirect()->route('users.edit', $user);
    }

    // -------------------------------------------------------
    // EDIT — Show edit form
    // -------------------------------------------------------
    public function edit(User $user)
    {
        $roles = ['Admin', 'Secretary', 'Committee'];

        return view('users.users-edit', compact('user', 'roles'));
    }

    // -------------------------------------------------------
    // UPDATE — Save edited user
    // -------------------------------------------------------
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:Admin,Secretary,Committee',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Only update password if a new one was provided
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User account updated successfully.');
    }

    // -------------------------------------------------------
    // DESTROY — Delete user
    // -------------------------------------------------------
    public function destroy(User $user)
    {
        // Prevent deleting your own account
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User account deleted successfully.');
    }
}