<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\User;
use illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->role) {

            $query->where(
                'role',
                $request->role
            );
        }

        if ($request->status != '') {

            $query->where(
                'is_active',
                $request->status
            );
        }

        $data = $query
            ->latest()
            ->paginate(20);

        return view(
            'tu.akun.index',
            compact('data')
        );
    }

    public function show(
        User $user
    )
    {
        return view(
            'tu.akun.show',
            compact('user')
        );
    }

    public function resetPassword(
        User $user
    )
    {
        $tanggalLahir = null;

        if (
            $user->role == 'siswa'
            && $user->siswa
        ) {
            $tanggalLahir =
                $user->siswa->tanggal_lahir;
        }

        if (
            $user->role == 'guru'
            && $user->guru
        ) {
            $tanggalLahir =
                $user->guru->tanggal_lahir;
        }

        if (!$tanggalLahir) {

            return back()->with(
                'error',
                'Tanggal lahir tidak ditemukan'
            );
        }

        $password = \Carbon\Carbon::parse(
            $tanggalLahir
        )->format('dmY');

        $user->update([

            'password'
                => Hash::make(
                    $password
                )

        ]);

        return back()->with(
            'success',
            'Password berhasil direset'
        );
    }

    public function toggleStatus(
        User $user
    )
    {
        $user->update([

            'is_active'
                => !$user->is_active

        ]);

        return back()->with(
            'success',
            'Status akun berhasil diubah'
        );
    }
}
