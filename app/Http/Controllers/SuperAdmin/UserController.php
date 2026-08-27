<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::whereIn('role', ['admin', 'tu'])
            ->paginate(10);

        return view('superadmin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        // Proteksi ekstra
        if ($user->role === 'superadmin') {
            abort(403);
        }

        return view('superadmin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403);
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'role' => 'required|in:admin,tu',
        ]);

        $user->update($request->only('name', 'email', 'role'));

        return redirect()
            ->route('superadmin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }
}
