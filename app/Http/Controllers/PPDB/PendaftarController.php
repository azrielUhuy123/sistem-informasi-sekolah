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
use App\Models\PpdbSetting;
use App\Exports\PpdbDiterimaExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;
use App\Exports\RekapPpdbExport;
use App\Services\PpdbReportService;
use App\Mail\PpdbPendaftaranMail;
use App\Mail\PpdbDaftarUlangCredentialsMail;
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
        ->whereIn('status', ['pending', 'verifikasi'])
        ->latest()
        ->paginate(20);

        return view(
            'ppdb.pendaftar.verifikasi',
            compact('data')
        );
    }

    public function diterima(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['nullable', 'integer', 'exists:tahun_ajaran,id'],
        ]);

        $tahunAjaranId = $validated['tahun_ajaran_id'] ?? null;
        $tahunAjaran = TahunAjaran::orderByDesc('id')->get();

        $data = PpdbPendaftar::query()
            ->with('siswa')
            ->where('status', 'diterima')
            ->when($tahunAjaranId, function ($query) use ($tahunAjaranId) {
                $query->where('tahun_ajaran_id', $tahunAjaranId);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view(
            'ppdb.pendaftar.diterima',
            compact('data', 'tahunAjaran', 'tahunAjaranId')
        );
    }

    public function exportDiterima(Request $request)
    {
        $validated = $request->validate([
            'tahun_ajaran_id' => ['required', 'integer', 'exists:tahun_ajaran,id'],
        ]);

        $tahunAjaran = TahunAjaran::findOrFail($validated['tahun_ajaran_id']);
        $tahunFile = str_replace('/', '-', $tahunAjaran->tahun);

        return Excel::download(
            new PpdbDiterimaExport($tahunAjaran->id),
            "data-diterima-{$tahunFile}.xlsx"
        );
    }

    public function resendEmailDiterima(Request $request, PpdbPendaftar $pendaftar)
    {
        if ($pendaftar->status !== 'diterima') {
            return response()->json([
                'success' => false,
                'message' => 'Email hanya dapat dikirim ulang untuk pendaftar berstatus diterima.',
            ], 409);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $pendaftar->update([
            'email' => $validated['email'],
        ]);

        if (!$this->sendPpdbEmail($pendaftar)) {
            return response()->json([
                'success' => false,
                'message' => 'Alamat email berhasil diperbarui, tetapi email pemberitahuan gagal dikirim.',
            ], 502);
        }

        return response()->json([
            'success' => true,
            'message' => 'Email pemberitahuan berhasil dikirim.',
        ]);
    }

    public function ditolak()
    {
        $data = PpdbPendaftar::latest()
            ->where('status', 'ditolak')
            ->paginate(20);

        return view(
            'ppdb.pendaftar.ditolak',
            compact('data')
        );
    }

    public function terima(PpdbPendaftar $pendaftar)
    {
        $result = DB::transaction(function () use ($pendaftar) {
            $pendaftar = PpdbPendaftar::whereKey($pendaftar->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($pendaftar->status, ['pending', 'verifikasi'], true)) {
                return ['error' => 'Pendaftar ini sudah memiliki keputusan.'];
            }

            if (
                !$pendaftar->kartu_keluarga ||
                !$pendaftar->ktp ||
                !$pendaftar->foto
            ) {
                return ['error' => 'Kartu keluarga, KTP orang tua, dan foto wajib tersedia sebelum pendaftar diterima.'];
            }

            $setting = PpdbSetting::where('tahun_ajaran_id', $pendaftar->tahun_ajaran_id)
                ->orderBy('id')
                ->lockForUpdate()
                ->first();

            if (!$setting) {
                return ['error' => 'Konfigurasi PPDB untuk Tahun Ajaran pendaftar tidak ditemukan.'];
            }

            $jumlahDiterima = PpdbPendaftar::where('tahun_ajaran_id', $pendaftar->tahun_ajaran_id)
                ->where('status', 'diterima')
                ->count();

            if ($jumlahDiterima >= (int) $setting->kuota) {
                return ['error' => 'Kuota PPDB untuk tahun ajaran ini telah penuh.'];
            }

            $pendaftar->update([
                'status' => 'diterima',
            ]);

            return ['pendaftar' => $pendaftar];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        $pendaftar = $result['pendaftar'];

        $emailSent = $this->sendPpdbEmail($pendaftar);

        $response = back()->with(
            'success',
            'Pendaftar berhasil diterima.'
        );

        if (!$emailSent) {
            $response->with(
                'warning',
                'Status berhasil diperbarui, tetapi email pemberitahuan tidak dapat dikirim.'
            );
        }

        return $response;
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
    public function tolak(PpdbPendaftar $pendaftar)
    {
        $result = DB::transaction(function () use ($pendaftar) {
            $pendaftar = PpdbPendaftar::whereKey($pendaftar->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if (!in_array($pendaftar->status, ['pending', 'verifikasi'], true)) {
                return ['error' => 'Pendaftar ini sudah memiliki keputusan.'];
            }

            $pendaftar->update([
                'status' => 'ditolak',
            ]);

            return ['pendaftar' => $pendaftar];
        });

        if (isset($result['error'])) {
            return back()->with(
                'error',
                $result['error']
            );
        }

        $pendaftar = $result['pendaftar'];

        $emailSent = $this->sendPpdbEmail($pendaftar);

        $response = redirect()
            ->route(
                'tu.ppdb.pendaftar.show',
                $pendaftar
            )
            ->with(
                'success',
                'Pendaftar berhasil ditolak.'
            );

        if (!$emailSent) {
            $response->with(
                'warning',
                'Status berhasil diperbarui, tetapi email pemberitahuan tidak dapat dikirim.'
            );
        }

        return $response;
    }

    private function sendPpdbEmail(
        PpdbPendaftar $pendaftar,
        ?string $actionUrl = null
    ): bool
    {
        if (empty($pendaftar->email)) {
            return false;
        }

        $statusUrl = $actionUrl ?? URL::temporarySignedRoute(
            'ppdb.status.link',
            now()->addDays(7),
            ['pendaftar' => $pendaftar->id]
        );

        try {
            Mail::to($pendaftar->email)
                ->send(new PpdbPendaftaranMail($pendaftar, $statusUrl));

            return true;
        } catch (\Throwable $exception) {
            Log::error('Email status PPDB gagal dikirim.', [
                'pendaftar_id' => $pendaftar->id,
                'email' => $pendaftar->email,
                'status' => $pendaftar->status,
                'error' => $exception->getMessage(),
            ]);

            return false;
        }
    }
    private function generateNis(PpdbPendaftar $pendaftar)
    {
        $tahunAjaran = TahunAjaran::findOrFail($pendaftar->tahun_ajaran_id);

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
        $result = DB::transaction(function () use ($pendaftar) {
            $pendaftar = PpdbPendaftar::whereKey($pendaftar->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            if ($pendaftar->status !== 'diterima') {
                return ['error' => 'Pendaftar belum diterima.'];
            }

            if ($pendaftar->status_daftar_ulang !== 'sudah') {
                return ['error' => 'Daftar ulang belum dilakukan oleh orang tua atau wali.'];
            }

            if ($pendaftar->siswa_id) {
                return ['already_completed' => true];
            }

            $tahunAjaran = TahunAjaran::findOrFail($pendaftar->tahun_ajaran_id);
            $tahunAwalAngkatan = (int) explode('/', $tahunAjaran->tahun, 2)[0];

            $angkatan = Angkatan::firstOrCreate(
                [
                    'tahun' => $tahunAwalAngkatan
                ],
                [
                    'keterangan' => 'Angkatan ' . $tahunAjaran->tahun
                ]
            );

            $nis = $this->generateNis($pendaftar);

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

                'email' => $pendaftar->email,

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

                'telepon_wali' => $pendaftar->nomor_hp ?: $pendaftar->hp_ayah,
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

            return [
                'siswa_id' => $siswa->id,
                'credentials' => [
                    'recipient' => $pendaftar->email,
                    'student_name' => $pendaftar->nama,
                    'nis' => $nis,
                    'initial_password' => $password,
                ],
            ];
        });

        if (isset($result['error'])) {
            return back()->with('error', $result['error']);
        }

        if (isset($result['already_completed'])) {
            return back()->with(
                'success',
                'Akun siswa untuk daftar ulang ini sudah pernah dibuat.'
            );
        }

        $mailFailed = false;

        try {
            Mail::to($result['credentials']['recipient'])->send(
                new PpdbDaftarUlangCredentialsMail(
                    $result['credentials']['student_name'],
                    $result['credentials']['nis'],
                    $result['credentials']['initial_password'],
                )
            );
        } catch (\Throwable $exception) {
            $mailFailed = true;

            Log::error('Email kredensial daftar ulang gagal dikirim.', [
                'pendaftar_id' => $pendaftar->getKey(),
                'exception' => get_class($exception),
            ]);
        } finally {
            unset($result['credentials']['initial_password']);
        }

        $response = back()->with(
            'success',
            'Daftar ulang berhasil dikonfirmasi.'
        );

        if ($mailFailed) {
            $response->with(
                'warning',
                'Akun siswa berhasil dibuat, tetapi email informasi akun gagal dikirim.'
            );
        }

        return $response;
    }

    public function penempatanKelas()
    {
        $tahunAktif = TahunAjaran::aktif();

        $data = PpdbPendaftar::with([
                'siswa',
                'siswa.penempatanAktif.kelas',
            ])
            ->whereNotNull('siswa_id')
            ->whereHas('siswa')
            ->whereDoesntHave('siswa.penempatan', function ($q) use ($tahunAktif) {
                $q->where('tahun_ajaran_id', $tahunAktif->id)
                ->whereNull('tanggal_keluar');
            })
            ->paginate(20);

        $kelas = Kelas::with([
                'kelasGrup.angkatan',
                'kelasGrup.kelasMaster',
            ])
            ->withCount([
                'penempatan as jumlah_siswa' => function ($q) use ($tahunAktif) {
                    $q->where('tahun_ajaran_id', $tahunAktif->id)
                        ->whereNull('tanggal_keluar');
                }
            ])
            ->whereHas('kelasGrup', function ($q) {
                $q->whereNotNull('angkatan_id');
            })
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

        $siswa = $pendaftar->siswa;

        if (!$siswa) {
            return back()->with('error', 'Data siswa tidak ditemukan');
        }

        $kelas = Kelas::with(['kelasGrup.kelasMaster'])
            ->findOrFail($request->kelas_id);

        if (
            !$kelas->kelasGrup ||
            !$kelas->kelasGrup->kelasMaster ||
            $kelas->kelasGrup->kelasMaster->tingkat !== 'I'
        ) {
            return back()->with('error', 'Siswa PPDB hanya dapat ditempatkan pada kelas tingkat I.');
        }

        if (
            !$kelas->kelasGrup->angkatan_id ||
            (int) $kelas->kelasGrup->angkatan_id !== (int) $siswa->angkatan_id
        ) {
            return back()->with('error', 'Angkatan kelas tidak sesuai dengan angkatan siswa.');
        }

        $tahunAktif = TahunAjaran::aktif();

        PenempatanSiswa::updateOrCreate(
            [
                'siswa_id' => $siswa->id,
                'tahun_ajaran_id' => $tahunAktif->id,
            ],
            [
                'kelas_id' => $kelas->id,
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