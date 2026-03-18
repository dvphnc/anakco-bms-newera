<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Traits\LogsActivity;

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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|in:Admin,Secretary,Committee',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $record = User::create($validated);
        $this->logActivity('created', $record);

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
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'role'     => 'required|in:Admin,Secretary,Committee',
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $oldData = $user->getOriginal();
        $user->update($validated);
        $this->logActivity('updated', $user, $oldData, $user->fresh()->toArray());

        return redirect()->route('users.index')->with('success', 'User account updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')->with('error', 'You cannot delete your own account.');
        }

        $this->logActivity('deleted', $user);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User account deleted successfully.');
    }
}