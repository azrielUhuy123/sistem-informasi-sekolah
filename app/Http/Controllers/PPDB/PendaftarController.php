<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Angkatan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

use App\Enums\UserRole;

class PendaftarController extends Controller
{
    public function index()
    {
        $data = PpdbPendaftar::latest()
            ->paginate(20);

        return view(
            'ppdb.pendaftar.index',
            compact('data')
        );
    }

    public function show(
        PpdbPendaftar $pendaftar
    )
    {
        return view(
            'ppdb.pendaftar.show',
            compact('pendaftar')
        );
    }

    public function verifikasi()
    {
        $data = PpdbPendaftar::with([
            'ayah',
            'ibu'
        ])
        ->where('status','pending')
        ->latest()
        ->paginate(20);

        return view(
            'ppdb.pendaftar.verifikasi',
            compact('data')
        );
    }

    public function diterima()
    {
        $data = PpdbPendaftar::with([
            'siswa'
        ])
        ->where('status','diterima')
        ->latest()
        ->paginate(20);

        return view(
            'ppdb.pendaftar.diterima',
            compact('data')
        );
    }

    public function ditolak()
    {
        $data = PpdbPendaftar::where(
            'status',
            'ditolak'
        )
        ->latest()
        ->paginate(20);

        return view(
            'ppdb.pendaftar.ditolak',
            compact('data')
        );
    }

    public function terima(
        PpdbPendaftar $pendaftar
    )
    {
        if (
            !in_array(
                $pendaftar->status,
                ['verifikasi']
            )
        )
            {
                return back()->with(
                    'error',
                    'Pendaftar harus diverifikasi terlebih dahulu'
                );
            }

        if ($pendaftar->siswa_id) {

            return back()->with(
                'error',
                'Pendaftar sudah menjadi siswa'
            );
        }

        DB::transaction(function () use ($pendaftar) {

            $angkatan = Angkatan::firstOrCreate(
                [
                    'tahun' => now()->year
                ],
                [
                    'keterangan' => 'Angkatan '.now()->year
                ]
            );

            $nis = $this->generateNis();

            $password = \Carbon\Carbon::parse(
                $pendaftar->tanggal_lahir
                )->format('dmY');

            $user = User::create([

                'name'
                    => $pendaftar->nama,

                'email' => $pendaftar->nik .
                        '.' .
                        $pendaftar->id .
                        '@siswa.local',

                'password' => Hash::make($password),

                'role' => UserRole::MURID,

                'is_active'
                    => true,

                'approval_status'
                    => 'approved',
            ]);

            $siswa = Siswa::create([

                'user_id'
                    => $user->id,

                'angkatan_id'
                    => $angkatan?->id,

                'nama'
                    => $pendaftar->nama,

                'nis'
                    => $nis,

                'nisn'
                    => null,

                'jenis_kelamin'
                    => $pendaftar->jenis_kelamin,

                'tanggal_lahir'
                    => $pendaftar->tanggal_lahir,

                'alamat'
                    => $pendaftar->alamat,

                'nama_wali'
                    => optional(
                        $pendaftar->ayah
                    )->nama,

                'telepon_wali'
                    => $pendaftar->hp_ayah,
            ]);

            $pendaftar->update([

                'status'
                    => 'diterima',

                'siswa_id'
                    => $siswa->id,
            ]);
        });


        return back()->with(
            'success',
            'Pendaftar berhasil diterima'
        );
    }

    public function tolak(
        PpdbPendaftar $pendaftar
    )
    {
        $pendaftar->update([
            'status' => 'ditolak'
        ]);

        return back()->with(
            'success',
            'Pendaftar ditolak'
        );
    }
    public function formVerifikasi(
        PpdbPendaftar $pendaftar
    )
    {
        return view(
            'ppdb.pendaftar.verifikasi-form',
            compact('pendaftar')
        );
    }
    public function simpanVerifikasi(
        Request $request,
        PpdbPendaftar $pendaftar
    )
    {
        if (
            !$pendaftar->kartu_keluarga ||
            !$pendaftar->akta_lahir ||
            !$pendaftar->foto
        )
        {
            return back()->with(
                'error',
                'Dokumen belum lengkap'
            );
        }
        $request->validate([
            'catatan_verifikasi'
                => 'nullable|string'
        ]);

        $pendaftar->update([

            'status'
                => 'verifikasi',

            'catatan_verifikasi'
                => $request->catatan_verifikasi
        ]);

        return redirect()
            ->route(
                'tu.ppdb.pendaftar.show',
                $pendaftar
            )
            ->with(
                'success',
                'Berkas berhasil diverifikasi'
            );
    }
    private function generateNis()
    {
        $tahun = now()->year;

        $last = Siswa::withTrashed()
            ->orderByDesc('id')
            ->first();

        $urut = $last
            ? ((int) substr($last->nis, -4)) + 1
            : 1;

        return $tahun .
            str_pad(
                $urut,
                4,
                '0',
                STR_PAD_LEFT
            );
    }
    public function daftarUlang()
    {
        $data = PpdbPendaftar::where(
            'status_daftar_ulang',
            'sudah'
        )
        ->paginate(20);

        return view(
            'ppdb.pendaftar.daftar-ulang',
            compact('data')
        );
    }

    public function penempatanKelas()
    {
        $data = PpdbPendaftar::with('siswa')
            ->whereNotNull('siswa_id')
            ->latest()
            ->paginate(20);

        $kelas = Kelas::orderBy('nama_kelas')
            ->get();

        return view(
            'ppdb.pendaftar.penempatan-kelas',
            compact(
                'data',
                'kelas'
            )
        );
    }

    public function simpanPenempatanKelas(
        Request $request,
        PpdbPendaftar $pendaftar
    )
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        if (!$pendaftar->siswa) {

            return back()->with(
                'error',
                'Data siswa tidak ditemukan'
            );
        }

        $pendaftar->siswa->update([

            'kelas_id'
                => $request->kelas_id

        ]);

        return back()->with(
            'success',
            'Penempatan kelas berhasil'
        );
    }

    
}