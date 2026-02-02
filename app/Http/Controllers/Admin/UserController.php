<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private function forbidIfSuperadmin(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh memodifikasi Kepala Sekolah');
        }
    }

    public function index(Request $request)
    {
        $query = User::query()
        ->where('approval_status', 'approved')
        ->where('role', '!=', 'superadmin');

        // 🔃 Sorting
        if ($request->filled('sort')) {
            $column = $request->get('sort');
            $order  = $request->get('order', 'asc');

            if (in_array($column, ['name', 'email', 'role'])) {
                $query->orderBy($column, $order);
            }
        } else {
            $query->orderByRaw("role = 'superadmin' DESC")
                ->orderBy('name');
        }

        return view('admin.users.index', [
            'title'   => 'Manajemen User',
            'layout'  => 'layouts.admin',
            'rows'    => $query->paginate(10)->withQueryString(),

            // ✅ WAJIB ADA
            'columns' => [
                ['key' => 'name', 'label' => 'Nama', 'sortable' => true],
                ['key' => 'email', 'label' => 'Email', 'sortable' => true],
                ['key' => 'role', 'label' => 'Role', 'sortable' => true],
            ],

            'actions' => [
                'edit'   => 'admin.users.edit',
                'toggle' => 'admin.users.toggle',
            ],
            'routeCreate' => route('admin.users.create'),
            'crudMode' => 'internal_user',
        ]);
    }



    public function create()
    {
        return view('admin.users.create');
    }



    public function destroy(User $user)
    {
        $this->forbidIfSuperadmin($user);

        if ($user->id === auth()->id()) {
            return back()->withErrors('Tidak boleh menghapus akun sendiri');
        }

        $user->delete();
        return back()->with('success', 'User dihapus');
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


    public function edit(User $user)
    {
        $this->forbidIfSuperadmin($user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $this->forbidIfSuperadmin($user);

        if ($user->id === auth()->id()) {
            abort(403, 'Tidak boleh mengubah akun sendiri');
        }

        $validated = $request->validate([
            'name'  => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role'  => 'required|in:admin,tu',
            'password' => 'nullable|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui');
    }

    public function toggleStatus(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh menonaktifkan Kepala Sekolah');
        }

        if ($user->id === auth()->id()) {
            return back()->withErrors('Tidak boleh menonaktifkan akun sendiri');
        }

        $user->update([
            'is_active' => ! $user->is_active
        ]);

        return back()->with('success', 'Status user diperbarui');
    }


}
