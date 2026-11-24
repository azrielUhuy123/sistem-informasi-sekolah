<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\BusinessNotificationService;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserApprovalController extends Controller
{
    private function assertActiveActor(): void
    {
        $actor = User::query()->find(Auth::id());

        abort_unless(
            $actor
                && $actor->role === 'superadmin'
                && $actor->approval_status === 'approved'
                && $actor->is_active
                && ! $actor->must_change_password,
            403,
            'Akun Kepala Sekolah tidak aktif atau belum disetujui.'
        );
    }

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
            $this->assertActiveActor();
            abort_if($user->getKey() === Auth::id(), 403, 'Tidak boleh memproses akun sendiri.');
            DB::transaction(function () use ($user) {
                $user = User::whereKey($user->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();
                $wasPendingOrRejected = in_array($user->approval_status, ['pending', 'rejected'], true);

                $user->update([
                    'approval_status' => 'approved',
                    'approved_at'      => now(),
                    'approved_by'      => Auth::id(),
                    'is_active'        => ! $user->must_change_password,
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

                if (
                    $wasPendingOrRejected &&
                    $user->approval_status === 'approved' &&
                    $user->is_active
                ) {
                    app(BusinessNotificationService::class)->notifyAccountApproved($user);
                }
            });

        return redirect()
            ->route('superadmin.users.pending')
            ->with('success', 'Akun berhasil diapprove.');
    }
    
    public function reject(User $user)
    {
        $this->assertActiveActor();
        abort_if($user->getKey() === Auth::id(), 403, 'Tidak boleh memproses akun sendiri.');
        $user->update([
            'approval_status' => 'rejected',
            'is_active'       => false,
        ]);

        return back()
            ->with('success', 'Akun berhasil ditolak.');
    }

    public function block(User $user)
    {
        $this->assertActiveActor();
        abort_if($user->getKey() === Auth::id(), 403, 'Tidak boleh memblokir akun sendiri.');
        $user->update([
            'is_active' => false
        ]);

        return back()
            ->with('success', 'User berhasil diblokir.');
    }

    public function unblock(User $user)
    {
        $this->assertActiveActor();
        abort_if($user->getKey() === Auth::id(), 403, 'Tidak boleh mengaktifkan akun sendiri.');
        abort_unless(
            $user->approval_status === 'approved' && ! $user->must_change_password,
            409,
            'Akun harus disetujui dan menyelesaikan aktivasi sebelum diaktifkan.'
        );

        $user->update([
            'is_active' => true
        ]);

        return back()
            ->with('success', 'User berhasil diaktifkan kembali.');
    }

}
