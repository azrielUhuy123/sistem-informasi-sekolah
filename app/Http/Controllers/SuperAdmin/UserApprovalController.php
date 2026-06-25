<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    /**
     * Tampilkan user yang menunggu approval
     */
    public function pending()
    {
        $users = User::with([
            'pegawai',
            'guru',
            'siswa'
        ])
        ->where('approval_status','pending')
        ->latest()
        ->paginate(10);

        return view(
            'superadmin.users.pending',
            compact('users')
        );
    }

    public function approved()
    {
        $users = User::where(
            'approval_status',
            'approved'
        )
        ->orderBy('approved_at', 'desc')
        ->paginate(10);

        return view(
            'superadmin.users.approved',
            compact('users')
        );
    }
/**
 * Tampilkan user yang ditolak
 */
    public function rejected()
    {
        $users = User::where(
            'approval_status',
            'rejected'
        )
        ->orderBy('approved_at', 'desc')
        ->paginate(10);

        return view(
            'superadmin.users.rejected',
            compact('users')
        );
    }

    public function approve(User $user)
    {
        $user->update([
            'approval_status' => 'approved',
            'approved_at'      => now(),
            'approved_by'      => auth()->id(),
            'is_active'        => true,
        ]);

        if (
            $user->role === 'guru' &&
            $user->pegawai &&
            !$user->guru
        ) {

            Guru::create([
                'user_id' => $user->id,
                'data_kepegawaian_id' => $user->pegawai->id,
            ]);

        }

        return redirect()
            ->route('superadmin.users.pending')
            ->with('success', 'Akun berhasil diapprove.');
    }
    
    public function reject(User $user)
    {
        $user->update([
            'approval_status' => 'rejected',
            'is_active'       => false,
        ]);

        return back()
            ->with('success', 'Akun berhasil ditolak.');
    }

    public function block(User $user)
    {
        $user->update([
            'is_active' => false
        ]);

        return back()
            ->with('success', 'User berhasil diblokir.');
    }

    public function unblock(User $user)
    {
        $user->update([
            'is_active' => true
        ]);

        return back()
            ->with('success', 'User berhasil diaktifkan kembali.');
    }

}
