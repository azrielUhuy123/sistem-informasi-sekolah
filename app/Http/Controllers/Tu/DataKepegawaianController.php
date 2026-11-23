<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Models\User;
use App\Services\BusinessNotificationService;
use App\Models\Guru;
use App\Models\PembinaEkskul;
use App\Models\PengampuMapel;
use App\Models\WaliKelas;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DataKepegawaianController extends Controller
{
    // LIST DATA
    public function index()
    {
        $data = DataKepegawaian::orderBy('created_at', 'asc')->get();

        $belumApprove = DataKepegawaian::whereIn('approval_status', [
            'pending',
            'rejected'
        ])->count();

        return view('tu.datakepegawaian.index', compact(
            'data',
            'belumApprove'
        ));
    }

    // FORM TAMBAH
    public function create()
    {
        return view('tu.datakepegawaian.create');
    }

    // SIMPAN DATA BARU
    public function store(Request $request)
{
    $validated = $this->validateData($request);

    // NORMALISASI GAJI
    if ($request->filled('gaji_pokok')) {
        $validated['gaji_pokok'] = $this->normalizeNumber(
            $request->gaji_pokok
        );
    }

    // DEFAULT APPROVAL
    $validated['approval_status'] = 'pending';

    // SIMPAN DATA
    DataKepegawaian::create($validated);

    return redirect()
        ->route('tu.datakepegawaian.index')
        ->with('success', 'Data pegawai berhasil ditambahkan!');
}

    // DETAIL DATA
    public function show($id)
    {
        $pegawai = DataKepegawaian::findOrFail($id);
        return view('tu.datakepegawaian.detail', compact('pegawai'));
    }

    // FORM EDIT
    public function edit($id)
    {
        $pegawai = DataKepegawaian::findOrFail($id);
        return view('tu.datakepegawaian.edit', compact('pegawai'));
    }

    // UPDATE DATA
   public function update(Request $request, $id)
{
    $pegawai = DataKepegawaian::findOrFail($id);

    $validated = $this->validateData($request);

    // NORMALISASI GAJI
    if ($request->filled('gaji_pokok')) {

        $validated['gaji_pokok'] = $this->normalizeNumber(
            $request->gaji_pokok
        );

    }
    $validated['approval_status'] = 'pending';

    $validated['rejected_reason'] = null;

    $validated['rejected_at'] = null;

    $validated['is_message_read'] = false;

    // UPDATE DATA
    $pegawai->update($validated);

    return redirect()
        ->route('tu.datakepegawaian.index')
        ->with(
            'success',
            'Data pegawai berhasil diperbarui dan menunggu approval ulang!'
        );
}

    // HAPUS DATA
    public function destroy($id)
    {
        $pegawai = DataKepegawaian::with([
            'guru.waliKelas',
            'guru.pengampuMapel',
            'guru.mapels',
            'guru.pembinaEkskul',
            'user',
        ])->findOrFail($id);

        $guru = Guru::withTrashed()
            ->where('data_kepegawaian_id', $pegawai->id)
            ->first();
        $hasLegacyAssignmentHistory = $guru && (
            WaliKelas::where('guru_id', $guru->id)->exists()
            || PengampuMapel::where('guru_id', $guru->id)->exists()
            || PembinaEkskul::where('guru_id', $guru->id)->exists()
            || $guru->mapels()->exists()
        );

        if (
            $pegawai->penugasans()->where('status', '!=', 'draft')->exists()
            || $hasLegacyAssignmentHistory
        ) {
            return back()->with(
                'error',
                'Pegawai dengan riwayat penugasan tidak dapat dihapus.'
            );
        }

        DB::transaction(function () use ($pegawai) {
            $pegawai->penugasans()->where('status', 'draft')->delete();

            // =========================
            // GURU
            // =========================
            if ($pegawai->guru) {

                $guru = $pegawai->guru;

                // Wali kelas
                $guru->waliKelas?->delete();

                // Pengampu mapel
                $guru->pengampuMapel()->delete();

                // Relasi guru - mapel
                $guru->mapels()->detach();

                // Pembina ekskul
                $guru->pembinaEkskul()->delete();

                // Guru
                $guru->delete();
            }

            // =========================
            // USER / AKUN
            // =========================
            if ($pegawai->user) {
                $pegawai->user->delete();
            }

            // =========================
            // PEGAWAI
            // =========================
            $pegawai->delete();
        });

        return redirect()
            ->route('tu.datakepegawaian.index')
            ->with(
                'success',
                'Data pegawai beserta data terkait berhasil dihapus!'
            );
    }

    // =========================
    // VALIDATION CENTRALIZED
    // =========================
    private function validateData(Request $request)
    {
        return $request->validate([
            // IDENTITAS
            'nama' => 'required|string|max:255',
            'nip' => 'nullable|string|max:50',
            'nuptk' => 'nullable|string|max:50',
            'tempat_lahir' => 'nullable|string|max:100',
            'tanggal_lahir' => 'nullable|date',
            'agama' => 'nullable|string|max:50',
            'jenis_kelamin' => 'nullable|in:L,P',

            // STATUS
            'status_kepegawaian' => 'nullable|in:PNS,NON_PNS',
            'status_kawin' => 'nullable|in:K,TK',

            // IJAZAH
            'ijazah_terakhir' => 'nullable|string|max:100',

            // SK
            'sk_terakhir_no' => 'nullable|string|max:100',
            'sk_terakhir_tmt' => 'nullable|date',
            'sk_berkala_no' => 'nullable|string|max:100',
            'sk_berkala_tmt' => 'nullable|date',
            'sk_terakhir_pangkat' => 'nullable|string|max:50',
            'sk_berkala_pangkat' => 'nullable|string|max:50',

            // PEKERJAAN
            'jabatan' => 'nullable|string|max:100',
            'mulai_kerja' => 'nullable|date',
            'masa_kerja_gol_th' => 'nullable|integer|min:0',
            'masa_kerja_gol_bln' => 'nullable|integer|min:0|max:11',
            'masa_kerja_total_th' => 'nullable|integer|min:0',
            'masa_kerja_total_bln' => 'nullable|integer|min:0|max:11',

            // MENGAJAR
            'mengajar_kelas' => 'nullable|string|max:100',
            'jam_mengajar' => 'nullable|integer',

            // GAJI
            'gaji_pokok' => 'nullable|numeric',

            // TAMBAHAN
            'tugas_tambahan' => 'nullable|string|max:255',
            'nomor_sertifikasi' => 'nullable|string|max:100',
            'tahun_sertifikasi' => 'nullable|digits:4',
            'keterangan' => 'nullable|string',
        ]);
    }

    public function approval()
    {
        $data = DataKepegawaian::where('approval_status', 'pending')
            ->orderBy('id', 'asc')
            ->get();

        return view('kepsek.datakepegawaian.approval', compact('data'));
    }

    private function normalizeNumber($value)
    {
        // hapus semua non angka
        $clean = preg_replace('/[^0-9]/', '', $value);

        // ambil maksimal 10 digit pertama (hindari double input)
        return substr($clean, 0, 10);
    }

    public function readMessage($id)
    {
        $data = DataKepegawaian::findOrFail($id);

        $data->update([
            'is_message_read' => true
        ]);

        return response()->json([
            'success' => true
        ]);
    }
    public function createAccount(DataKepegawaian $pegawai)
    {
        if ($pegawai->approval_status !== 'approved') {

            return back()->with(
                'error',
                'Pegawai belum disetujui.'
            );

        }

        if ($pegawai->user) {

            return back()->with(
                'error',
                'Pegawai sudah memiliki akun.'
            );

        }

        $jabatan = strtolower((string) $pegawai->jabatan);

        $role = str_contains($jabatan, 'kepala sekolah')
            ? UserRole::SUPERADMIN
            : UserRole::GURU;

        $password = Carbon::parse(
            $pegawai->tanggal_lahir
        )->format('Ymd');

        $user = DB::transaction(function () use ($pegawai, $role, $password) {
            $lockedEmployee = DataKepegawaian::query()
                ->lockForUpdate()
                ->findOrFail($pegawai->id);

            if ($lockedEmployee->user) {
                return null;
            }

            $user = User::create([
                'pegawai_id' => $lockedEmployee->id,
                'name' => $lockedEmployee->nama,
                'email' => 'pegawai'.$lockedEmployee->id.'@internal.local',
                'username' => $lockedEmployee->nip,
                'password' => Hash::make($password),
                'role' => $role,
                'approval_status' => 'pending',
                'is_active' => false,
            ]);

            if ($role === UserRole::GURU) {
                Guru::firstOrCreate(['data_kepegawaian_id' => $lockedEmployee->id]);
            }

            return $user;
        });

        if (! $user) {
            return back()->with('error', 'Pegawai sudah memiliki akun.');
        }

        if ($user->approval_status === 'pending') {
            app(BusinessNotificationService::class)->notifyPendingAccount($user);
        }

        return back()->with(
            'success',
            'Permintaan akun berhasil dikirim ke Kepala Sekolah.'
        );
    }
}