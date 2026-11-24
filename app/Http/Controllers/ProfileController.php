<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\WaliKelas;
use App\Models\PengampuMapel;
use App\Models\DataKepegawaian;
use App\Models\PpdbPendaftar;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Display the user's profile form.
     */
    public function index()
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | PROFILE SISWA
        |--------------------------------------------------------------------------
        */
        if ($user->siswa) {

            $ppdb = PpdbPendaftar::where('siswa_id', $user->siswa->id)
                ->first();

            return view(
                'siswa.profile.index',
                [
                    'user'  => $user,
                    'siswa' => $user->siswa,
                    'ppdb'  => $ppdb,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | PROFILE GURU
        |--------------------------------------------------------------------------
        */

        $status = '-';
        $pegawai = null;

        if ($user->guru) {

            $pegawai = $user->guru->pegawai;
            $waliKelas = WaliKelas::with('kelas')
                ->where('guru_id', $user->guru->id)
                ->where('tahun_ajaran_id', current_tahun_id())
                ->first();

            if ($waliKelas) {

                $status = 'Wali Kelas ' . ucfirst($waliKelas->kelas->nama_kelas_label);

            } else {

                $pengampu = PengampuMapel::with('mapel')
                    ->where('guru_id', $user->guru->id)
                    ->where('tahun_ajaran_id', current_tahun_id())
                    ->first();

                if ($pengampu) {

                    $status = 'Guru ' . ucfirst($pengampu->mapel->nama_mapel);

                } else {

                    $status = '-';
                }
            }
        }

        return view(
            'profile.index',
            [
                'user'   => $user,
                'guru'    => $user->guru,    // Kirim juga variabel guru jika dibutuhkan di view
                'pegawai' => $pegawai,
                'status' => $status,
            ]
        );
    }

    /**
     * Display detail profile.
     */
    public function detail(): View
    {
        $user = Auth::user();

        /*
        |--------------------------------------------------------------------------
        | DETAIL PROFILE SISWA
        |--------------------------------------------------------------------------
        */
        if ($user->siswa) {

            $ppdb = PpdbPendaftar::where(
                'siswa_id',
                $user->siswa->id
            )->first();

            return view(
                'siswa.profile.detail',
                [
                    'user'  => $user,
                    'siswa' => $user->siswa,
                    'ppdb'  => $ppdb,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DETAIL PROFILE GURU
        |--------------------------------------------------------------------------
        */

        $status = '-';
        $pegawai = null;

        if ($user->guru) {
            $pegawai = $user->guru->pegawai;
        }

        return view(
            'profile.detail',
            [
                'user'    => $user,
                'pegawai' => $pegawai,
                'status'  => $status,
            ]
        );
    }

    /**
     * Update detail profile guru.
     */
    public function updateDetail(Request $request): RedirectResponse
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROFILE SISWA
        |--------------------------------------------------------------------------
        */
        if ($user->siswa) {

            $request->validate([
                'nama'            => 'required|string|max:255',
                'email'           => 'nullable|email|max:255',

                'agama'           => 'nullable|string|max:100',
                'tempat_lahir'    => 'nullable|string|max:255',
                'tanggal_lahir'   => 'nullable|date',
                'jenis_kelamin'   => 'nullable|in:L,P',

                'nik'             => 'nullable|string|max:30',
                'nomor_kk'        => 'nullable|string|max:30',
                'nomor_akta'      => 'nullable|string|max:50',

                'alamat'          => 'nullable|string',
                'rt'              => 'nullable|string|max:5',
                'rw'              => 'nullable|string|max:5',
                'desa'            => 'nullable|string|max:255',
                'kecamatan'       => 'nullable|string|max:255',
                'kabupaten'       => 'nullable|string|max:255',
                'provinsi'        => 'nullable|string|max:255',

                'telepon_rumah'   => 'nullable|string|max:30',
                'hp_ayah'         => 'nullable|string|max:30',
                'hp_ibu'          => 'nullable|string|max:30',
            ]);

            $ppdb = PpdbPendaftar::where(
                'siswa_id',
                $user->siswa->id
            )->first();

            if (!$ppdb) {
                return back()->with(
                    'error',
                    'Data PPDB tidak ditemukan.'
                );
            }

            $user->update([
                'email' => $request->email,
            ]);

            $ppdb->update($request->only([
                'nama',
                'agama',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',

                'nik',
                'nomor_kk',
                'nomor_akta',

                'alamat',
                'rt',
                'rw',
                'desa',
                'kecamatan',
                'kabupaten',
                'provinsi',

                'telepon_rumah',
                'hp_ayah',
                'hp_ibu',
            ]));

            return redirect()
                ->route('siswa.profile.detail')
                ->with(
                    'success',
                    'Profil berhasil diperbarui.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE PROFILE GURU
        |--------------------------------------------------------------------------
        */

        abort_unless($user->role === 'guru' && $user->guru && $user->pegawai, 403);

        $validated = $request->validate([
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        if (array_key_exists('email', $validated)) {
            $user->email = $validated['email'];
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();
        }

    return redirect()
        ->route('guru.profile.detail')
        ->with(
            'success',
            'Email profil berhasil diperbarui. Perubahan data kepegawaian diajukan melalui menu Perubahan Data Pegawai.'
        );
}

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}