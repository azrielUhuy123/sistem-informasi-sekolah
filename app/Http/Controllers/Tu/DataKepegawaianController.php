<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\DataKepegawaian;
use Illuminate\Http\Request;

class DataKepegawaianController extends Controller
{
    // LIST DATA
    public function index()
    {
        $data = DataKepegawaian::orderBy('created_at', 'asc')->get();
        return view('tu.datakepegawaian.index', compact('data'));
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

        if ($request->filled('gaji_pokok')) {
            $validated['gaji_pokok'] = $this->normalizeNumber($request->gaji_pokok);
        }

        DataKepegawaian::create($validated);

        return redirect()->route('tu.datakepegawaian.index')
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

        if ($request->filled('gaji_pokok')) {
            $validated['gaji_pokok'] = $this->normalizeNumber($request->gaji_pokok);
        }

        $pegawai->update($validated);

        return redirect()->route('tu.datakepegawaian.index')
            ->with('success', 'Data pegawai berhasil diperbarui!');
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

    private function normalizeNumber($value)
{
    // hapus semua non angka
    $clean = preg_replace('/[^0-9]/', '', $value);

    // ambil maksimal 10 digit pertama (hindari double input)
    return substr($clean, 0, 10);
}
}