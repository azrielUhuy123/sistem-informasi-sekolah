<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserApprovalController extends Controller
{
    /**
     * Tampilkan user yang menunggu approval
     */
    public function pending()
    {
        $users = User::where(
            'approval_status',
            'pending'
        )
        ->orderBy('created_at', 'asc')
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
            'approved_at' => now()
        ]);

        return redirect()
            ->route('superadmin.users.pending')
            ->with('success', 'User approved successfully.');
    }
}
