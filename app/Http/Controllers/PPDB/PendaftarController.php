<?php

namespace App\Http\Controllers\PPDB;

use App\Http\Controllers\Controller;
use App\Models\PpdbPendaftar;
use App\Models\User;
use App\Models\Siswa;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\PenempatanSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use App\Exports\RekapPpdbExport;
use App\Services\PpdbReportService;
use App\Mail\PpdbPendaftaranMail;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

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

    public function terima(PpdbPendaftar $pendaftar)
    {
        if ($pendaftar->status != 'verifikasi') {
            return back()->with(
                'error',
                'Pendaftar harus diverifikasi terlebih dahulu'
            );
        }

        $pendaftar->update([
            'status' => 'diterima',
        ]);

        return back()->with(
            'success',
            'Pendaftar berhasil diterima.'
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
                => null
        ]);

        return redirect()
            ->route(
                'tu.ppdb.pendaftar.show',
                $pendaftar
            )
            ->with(
                'success',
                'Berkas beralih ke fase verifikasi'
            );
    }

    public function tolakBerkas(
        Request $request,
        PpdbPendaftar $pendaftar
    )
    {
        $request->validate([
            'catatan_verifikasi' => 'required|string'
        ]);

        $pendaftar->update([
            'status' => 'pending',
            'catatan_verifikasi' => $request->catatan_verifikasi
        ]);

        if (!empty($pendaftar->email)) {
            $repairUrl = URL::temporarySignedRoute(
                'ppdb.perbaikan.form',
                now()->addHours(48),
                ['pendaftar' => $pendaftar->id]
            );

            Mail::to($pendaftar->email)
                ->send(new PpdbPendaftaranMail($pendaftar, $repairUrl));
        }

        return redirect()
            ->route(
                'tu.ppdb.pendaftar.show',
                $pendaftar
            )
            ->with(
                'success',
                'Berkas ditandai untuk diperbaiki. Pendaftar dapat mengajukan ulang berkas.'
            );
    }
    private function generateNis()
    {
        // Ambil tahun ajaran aktif
        $tahunAjaran = TahunAjaran::aktif();

        if (!$tahunAjaran) {
            throw new \Exception('Tahun ajaran aktif belum tersedia.');
        }

        /*
        |-------------------------------------------------------------
        | Format tahun
        | 2025/2026 -> 2526
        | 2026/2027 -> 2627
        |-------------------------------------------------------------
        */
        preg_match('/(\d{4})\/(\d{4})/', $tahunAjaran->tahun, $match);

        if (!$match) {
            throw new \Exception('Format tahun ajaran tidak valid.');
        }

        $prefix =
            substr($match[1], -2) .
            substr($match[2], -2);

        // SD selalu masuk kelas 1
        $tingkat = 1;

        $prefixLengkap = $prefix . '.' . $tingkat . '.';

        // Cari NIS terakhir dengan prefix yang sama
        $last = Siswa::where('nis', 'like', $prefixLengkap . '%')
            ->orderByDesc('nis')
            ->first();

        if ($last) {

            $lastUrut = (int) substr($last->nis, -3);

            $urut = $lastUrut + 1;

        } else {

            $urut = 1;

        }

        return $prefixLengkap .
            str_pad($urut, 3, '0', STR_PAD_LEFT);
    }
    public function daftarUlang()
    {
        $data = PpdbPendaftar::where(
            'status',
            'diterima'
        )->paginate(20);

        return view(
            'ppdb.pendaftar.daftar-ulang',
            compact('data')
        );
    }

    public function konfirmasiDaftarUlang(PpdbPendaftar $pendaftar)
    {
        if ($pendaftar->status != 'diterima') {
            return back()->with(
                'error',
                'Pendaftar belum diterima.'
            );
        }
    
        if ($pendaftar->status_daftar_ulang == 'sudah') {
            return back()->with(
                'error',
                'Daftar ulang sudah dikonfirmasi.'
            );
        }

        if ($pendaftar->siswa_id) {
            return back()->with(
                'error',
                'Data siswa sudah dibuat.'
            );
        }

        DB::transaction(function () use ($pendaftar) {

            $tahunAjaran = TahunAjaran::aktif();

            $angkatan = Angkatan::firstOrCreate(
                [
                    'tahun' => $tahunAjaran->tahun
                ],
                [
                    'keterangan' => 'Angkatan ' . $tahunAjaran->tahun
                ]
            );

            $nis = $this->generateNis();

            $password = \Carbon\Carbon::parse(
                $pendaftar->tanggal_lahir
            )->format('dmY');

            /*
            |--------------------------------------------------------------------------
            | Buat Akun User
            |--------------------------------------------------------------------------
            */

            $user = User::create([

                'name' => $pendaftar->nama,

                'username' => $nis,

                'password' => Hash::make($password),

                'role' => UserRole::MURID,

                'is_active' => true,

                'approval_status' => 'approved',

                'approved_at' => now(),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Buat Data Siswa
            |--------------------------------------------------------------------------
            */

            $siswa = Siswa::create([

                'user_id' => $user->id,

                'angkatan_id' => $angkatan->id,

                'nama' => $pendaftar->nama,

                'nis' => $nis,

                'nisn' => null,

                'jenis_kelamin' => $pendaftar->jenis_kelamin,

                'tanggal_lahir' => $pendaftar->tanggal_lahir,

                'alamat' => $pendaftar->alamat,

                'nama_wali' => optional($pendaftar->ayah)->nama,

                'telepon_wali' => $pendaftar->hp_ayah,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Hubungkan User <-> Siswa
            |--------------------------------------------------------------------------
            */

            $user->update([
                'siswa_id' => $siswa->id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update PPDB
            |--------------------------------------------------------------------------
            */

            $pendaftar->update([

                'status_daftar_ulang' => 'sudah',
                'siswa_id' => $siswa->id,
            ]);
        });

        return back()->with(
            'success',
            'Daftar ulang berhasil dikonfirmasi.'
        );
    }

    public function penempatanKelas()
    {
        $tahunAktif = TahunAjaran::aktif();

        $data = PpdbPendaftar::with([
                'siswa.penempatanAktif.kelas'
            ])
            ->whereNotNull('siswa_id')
            ->whereHas('siswa')
            ->whereDoesntHave('siswa.penempatan', function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                ->whereNull('tanggal_keluar');
            })
            ->paginate(20);

        $kelas = Kelas::withCount([
            'penempatan as jumlah_siswa' => function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                ->whereNull('tanggal_keluar');
            }
        ])
        ->tingkat('I')
        ->orderBy('nama_kelas')
        ->get();

        return view(
            'ppdb.pendaftar.penempatan-kelas',
            compact(
                'data',
                'kelas'
            )
        );
    }

    public function simpanPenempatanKelas(Request $request, PpdbPendaftar $pendaftar)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id'
        ]);

        if (!$pendaftar->siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan');
        }

        $tahunAktif = TahunAjaran::aktif();

        PenempatanSiswa::updateOrCreate(
            [
                'siswa_id' => $pendaftar->siswa->id,
                'tahun_ajaran_id' => $tahunAktif->id,
            ],
            [
                'kelas_id' => $request->kelas_id,
                'tanggal_masuk' => now(),
                'tanggal_keluar' => null,
            ]
        );

        return back()->with(
            'success',
            'Penempatan kelas berhasil'
        );
    }

    
}