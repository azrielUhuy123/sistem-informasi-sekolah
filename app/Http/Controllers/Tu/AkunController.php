<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\PegawaiChangeWorkflow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with([
            'pegawai',
            'siswa',
        ]);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->status !== null && $request->status !== '') {
            $query->where('is_active', $request->status);
        }

        $data = $query
            ->latest()
            ->paginate(20);

        return view(
            'tu.akun.index',
            compact('data')
        );
    }

    public function show(User $user)
    {
        $user->load([
            'pegawai',
            'siswa',
        ]);

        return view(
            'tu.akun.show',
            compact('user')
        );
    }

    public function resetPassword(Request $request, User $user, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertActiveOperator($request->user(), 'operator.accounts');

        abort_unless(in_array($user->role, ['guru', 'siswa'], true), 403);
        abort_unless($user->approval_status === 'approved', 409, 'Akun harus disetujui sebelum reset password.');

        $token = Password::createToken($user);
        $resetUrl = route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);

        return response()
            ->view('tu.akun.password-reset-link', compact('resetUrl', 'user'))
            ->header('Cache-Control', 'no-store, private')
            ->header('Pragma', 'no-cache')
            ->header('Referrer-Policy', 'no-referrer')
            ->header('X-Robots-Tag', 'noindex, nofollow');
    }

    public function toggleStatus(Request $request, User $user, PegawaiChangeWorkflow $workflow)
    {
        $workflow->assertActiveOperator($request->user(), 'operator.accounts');

        abort_unless(in_array($user->role, ['guru', 'siswa'], true), 403);

        if (! $user->is_active) {
            abort_unless(
                $user->approval_status === 'approved' && ! $user->must_change_password,
                409,
                'Akun harus disetujui dan menyelesaikan aktivasi sebelum diaktifkan.'
            );
        }

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', 'Status akun berhasil diubah');
    }
}
