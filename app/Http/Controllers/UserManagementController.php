<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    /**
     * Display list of users
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('users.usersPage', compact('users'));
    }

    /**
     * Show form to create new user (redirect to index - modal opens there)
     */
    public function create()
    {
        return redirect()->route('users.index');
    }

    /**
     * Store new user
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::log('create', "Created new user: {$user->name} (ID: {$user->id})", 'User', $user->id);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Show edit user form (redirect to index - modal opens there)
     */
    public function edit($id)
    {
        return redirect()->route('users.index');
    }

    /**
     * Update user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        
        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();

        ActivityLog::log('update', "Updated user: {$user->name} (ID: {$user->id})", 'User', $user->id);

        return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Show change password form
     */
    public function changePassword()
    {
        return view('users.change_password');
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        ActivityLog::log('update', "Changed password for user: {$user->name}", 'User', $user->id);

        return back()->with('success', 'Password changed successfully!');
    }

    /**
     * Delete user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account']);
        }

        $userName = $user->name;
        $user->delete();

        ActivityLog::log('delete', "Deleted user: {$userName} (ID: {$id})", 'User', $id);

        return back()->with('success', 'User deleted successfully!');
    }

    /**
     * Display activity logs
     */
    public function activityLogs(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        $users = User::orderBy('name')->get();

        return view('users.activity_logs', compact('logs', 'users'));
    }
}
