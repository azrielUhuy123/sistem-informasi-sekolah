<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    /**
     * Tampilkan user yang menunggu approval
     */public function pending()
{
    $users = User::where('approval_status', 'pending')
        ->orderBy('created_at', 'asc')
        ->paginate(10);

    return view('superadmin.users.pending', [
        'title' => 'Approval User',
        'layout' => 'layouts.superadmin',
        'rows' => $users,
        'columns' => [
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            [
                'key' => 'created_at',
                'label' => 'Dibuat',
                'format' => fn ($u) => $u->created_at->format('d-m-Y'),
            ],
        ],
        'actions' => [
            'approve' => 'superadmin.users.approve',
            'reject'  => 'superadmin.users.reject',
        ],
        'crudMode' => 'internal_user',
    ]);
}
    /**
 * Tampilkan user yang sudah disetujui
 */
public function approved()
{
    $users = User::where('approval_status', 'approved')
        ->orderBy('approved_at', 'desc')
        ->paginate(10);

    return view('superadmin.users.approved', [
        'title' => 'User Disetujui',
        'layout' => 'layouts.superadmin',
        'rows' => $users,
        'columns' => [
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            [
                'key' => 'approved_at',
                'label' => 'Disetujui',
                'format' => fn ($u) => optional($u->approved_at)?->format('d-m-Y'),
            ],
        ],
        'actions' => [], // READ ONLY
        'crudMode' => 'internal_user',
    ]);
}
/**
 * Tampilkan user yang ditolak
 */
public function rejected()
{
    $users = User::where('approval_status', 'rejected')
        ->orderBy('approved_at', 'desc')
        ->paginate(10);

    return view('superadmin.users.rejected', [
        'title' => 'User Ditolak',
        'layout' => 'layouts.superadmin',
        'rows' => $users,
        'columns' => [
            ['key' => 'name', 'label' => 'Nama'],
            ['key' => 'email', 'label' => 'Email'],
            ['key' => 'role', 'label' => 'Role'],
            [
                'key' => 'approved_at',
                'label' => 'Ditolak Pada',
                'format' => fn ($u) => optional($u->approved_at)?->format('d-m-Y'),
            ],
        ],
        'actions' => [], // READ ONLY
        'crudMode' => 'internal_user',
    ]);
}



    /**
     * Approve user
     */
    public function approve(User $user)
    {
        // pengaman: jangan approve superadmin
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh memproses superadmin');
        }

        $user->update([
            'approval_status' => 'approved',
            'is_active' => 1,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('superadmin.users.pending')
            ->with('success', 'User berhasil disetujui');

    }

    /**
     * Reject user
     */
    public function reject(User $user)
    {
        if ($user->role === 'superadmin') {
            abort(403, 'Tidak boleh memproses superadmin');
        }

        $user->update([
            'approval_status' => 'rejected',
            'is_active' => 0,
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        return redirect()
            ->route('superadmin.users.pending')
            ->with('success', 'User ditolak');

    }
}
