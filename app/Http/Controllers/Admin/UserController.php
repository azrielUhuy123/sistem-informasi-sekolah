<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::query();

        return view('admin.users.index', [
            'title'   => 'Manajemen User',
            'layout'  => 'layouts.admin',
            'rows'    => $users->paginate(10),
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'sortable' => true],
                ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                ['key' => 'role', 'label' => 'Role', 'sortable' => true],
            ],
            'actions' => [
                'edit'   => 'admin.users.edit',
                'delete' => 'admin.users.destroy',
            ],
            'routeCreate' => route('admin.users.create'),
        ]);
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function edit(User $user)
    {
        // Admin tidak boleh edit superadmin
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh mengedit Super Admin');
        }

        return view('admin.users.edit', compact('user'));
    }


    public function destroy(User $user)
    {
        // Admin tidak boleh hapus diri sendiri
        if ($user->id === auth()->id()) {
            abort(403, 'Tidak boleh menghapus akun sendiri');
        }

        // Admin tidak boleh hapus superadmin
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh menghapus Super Admin');
        }

        $user->delete();

        return back()->with('success', 'User berhasil dihapus');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,tu,guru',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success','User berhasil ditambahkan');
    }

    public function update(Request $request, User $user)
    {
        // Proteksi superadmin (opsional tapi disarankan)
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh mengubah Super Admin');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $data = $request->only('name', 'email', 'role');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }


}
