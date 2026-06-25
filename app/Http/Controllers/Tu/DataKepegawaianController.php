<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Enums\UserRole;
use Illuminate\Http\Request;

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
        $pegawai = DataKepegawaian::findOrFail($id);
        $pegawai->delete();

        return redirect()->route('tu.datakepegawaian.index')
            ->with('success', 'Data pegawai berhasil dihapus!');
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

        $jabatan = strtolower($pegawai->jabatan);

        $role = match (true) {

            str_contains($jabatan, 'kepala sekolah')
                => UserRole::SUPERADMIN,

            str_contains($jabatan, 'operator')
                => UserRole::TU,

            str_contains($jabatan, 'guru')
                => UserRole::GURU,

            default => null,

        };

        if (!$role) {

            return back()->with(
                'error',
                'Role tidak dikenali.'
            );

        }

        $password = Carbon::parse(
            $pegawai->tanggal_lahir
        )->format('Ymd');

        User::create([

            'pegawai_id' => $pegawai->id,

            'name' => $pegawai->nama,

            'email' => 'pegawai'.$pegawai->id.'@internal.local',

            'username' => $pegawai->nip,

            'password' => Hash::make($password),

            'role' => $role,

            'approval_status' => 'pending',

            'is_active' => false,

        ]);

        return back()->with(
            'success',
            'Permintaan akun berhasil dikirim ke Kepala Sekolah.'
        );
    }
}