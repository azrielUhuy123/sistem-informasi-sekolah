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
     * Update the user's profile information.
     */
    public function updateInformasi(
        Request $request
    )
    {
        $request->validate([

            'email' =>
                'nullable|email',

            'telepon' =>
                'nullable|string|max:30',

            'alamat' =>
                'nullable|string|max:1000',
        ]);

        $user = Auth::user();
        /** @var \App\Models\User $user */

        $user->update([

            'email' =>
                $request->email,

            'telepon' =>
                $request->telepon,

            'alamat' =>
                $request->alamat,
        ]);

        return back()->with(
            'success',
            'Profil berhasil diperbarui'
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

        $request->validate([
            'nama'                 => 'required|string|max:255',
            'email'                => 'nullable|email|max:255',

            'nip'                  => 'nullable|string|max:255',
            'nuptk'                => 'nullable|string|max:255',
            'tempat_lahir'         => 'nullable|string|max:255',
            'tanggal_lahir'        => 'nullable|date',

            'jabatan'              => 'nullable|string|max:255',
            'status_kepegawaian'   => 'nullable|string|max:255',
            'ijazah_terakhir'      => 'nullable|string|max:255',
            'mengajar_kelas'       => 'nullable|string|max:255',
            'pengalaman_tahun'     => 'nullable|integer',
            'gaji_pokok'           => 'nullable|numeric',

            'agama'                => 'nullable|string|max:100',
            'jenis_kelamin'        => 'nullable|in:L,P',
            'status_kawin'         => 'nullable|in:K,TK',

            'sk_terakhir_pangkat'  => 'nullable|string|max:255',
            'sk_terakhir_no'       => 'nullable|string|max:255',
            'sk_terakhir_tmt'      => 'nullable|date',

            'sk_berkala_pangkat'   => 'nullable|string|max:255',
            'sk_berkala_no'        => 'nullable|string|max:255',
        'sk_berkala_tmt'       => 'nullable|date',

        'mulai_kerja'          => 'nullable|date',

        'masa_kerja_gol_th'    => 'nullable|integer',
        'masa_kerja_gol_bln'   => 'nullable|integer',

        'masa_kerja_total_th'  => 'nullable|integer',
        'masa_kerja_total_bln' => 'nullable|integer',

        'jam_mengajar'         => 'nullable|integer',
        'tugas_tambahan'       => 'nullable|string|max:255',

        'nomor_sertifikasi'    => 'nullable|string|max:255',
        'tahun_sertifikasi'    => 'nullable|digits:4',

        'keterangan'           => 'nullable|string',
    ]);

    if (!$user->guru || !$user->guru->pegawai) {
        return back()->with(
            'error',
            'Data kepegawaian tidak ditemukan.'
        );
    }

    $pegawai = $user->guru->pegawai;

    $pegawai->update($request->only([
        'nama',
        'nip',
        'nuptk',
        'tempat_lahir',
        'tanggal_lahir',

        'jabatan',
        'status_kepegawaian',
        'ijazah_terakhir',
        'mengajar_kelas',
        'pengalaman_tahun',
        'gaji_pokok',

        'agama',
        'jenis_kelamin',
        'status_kawin',

        'sk_terakhir_pangkat',
        'sk_terakhir_no',
        'sk_terakhir_tmt',

        'sk_berkala_pangkat',
        'sk_berkala_no',
        'sk_berkala_tmt',

        'mulai_kerja',

        'masa_kerja_gol_th',
        'masa_kerja_gol_bln',
        'masa_kerja_total_th',
        'masa_kerja_total_bln',

        'jam_mengajar',
        'tugas_tambahan',

        'nomor_sertifikasi',
        'tahun_sertifikasi',

        'keterangan',
    ]));

    $user->update([
        'email' => $request->email,
    ]);

    return redirect()
        ->route('guru.profile.detail')
        ->with(
            'success',
            'Profil berhasil diperbarui.'
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