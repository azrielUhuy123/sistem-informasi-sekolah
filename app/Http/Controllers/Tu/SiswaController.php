<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Angkatan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\KelasGrup;

class SiswaController extends Controller
{
    /**
     * INDEX
     * - WAJIB pilih angkatan
     * - Kelas = ROMBEL
     */
   public function index(Request $request)
{
    $angkatanId = $request->get('angkatan');
    $grupId     = $request->get('kelas_grup_id');
    $kelasId    = $request->get('kelas_id');
    $submit     = $request->has('submit');

    // ===============================
    // DATA MASTER
    // ===============================
    $listAngkatan = Angkatan::orderByDesc('tahun')->get();
    $listGrup     = collect();
    $listKelas    = collect();

    // ===============================
    // JIKA ANGKATAN DIPILIH → LOAD GRUP
    // ===============================
    if ($angkatanId) {
        $listGrup = KelasGrup::where('angkatan_id', $angkatanId)
            ->orderBy('tingkat')
            ->get();
    }

    // ===============================
    // JIKA GRUP DIPILIH → LOAD ROMBEL
    // ===============================
    if ($grupId) {
        $listKelas = Kelas::where('kelas_grup_id', $grupId)
            ->orderBy('nama_kelas')
            ->get();
    }

    // ===============================
    // QUERY SISWA (HANYA JIKA SUBMIT)
    // ===============================
    $siswa = collect();

    if ($submit && $kelasId) {
        $siswa = Siswa::with('kelas.kelasGrup.angkatan')
            ->where('kelas_id', $kelasId)
            ->paginate(15)
            ->withQueryString();
    }

    return view('tu.siswa.index', compact(
        'listAngkatan',
        'listGrup',
        'listKelas',
        'angkatanId',
        'grupId',
        'kelasId',
        'submit',
        'siswa'
    ));
}


    /**
     * CREATE
     * - WAJIB kelas_id (rombel)
     */
    public function create(Request $request)
    {
        $kelasId = $request->get('kelas_id');

        if (!$kelasId) {
            return redirect()
                ->route('tu.siswa.index')
                ->with('warning', 'Silakan pilih kelas (rombel) terlebih dahulu');
        }

        $kelas = Kelas::with('kelasGrup.angkatan')->findOrFail($kelasId);

        return view('tu.siswa.create', compact('kelas'));
    }

    /**
     * STORE
     * - SIMPAN KE ROMBEL
     */
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'nama'           => 'required|string|max:255',
            'nis'            => 'required|numeric|unique:siswa,nis',
            'nisn'           => 'nullable|numeric|unique:siswa,nisn',
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'nama_wali'      => 'nullable|string|max:255',
            'telepon_wali'   => 'nullable|string|max:20',
        ]);

        Siswa::create($request->only([
            'kelas_id',
            'nama',
            'nis',
            'nisn',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'nama_wali',
            'telepon_wali',
        ]));

        return redirect()
            ->route('tu.siswa.index', [
                'angkatan' => Kelas::find($request->kelas_id)->kelasGrup->angkatan_id,
                'kelas_id' => $request->kelas_id
            ])
            ->with('success', 'Siswa berhasil ditambahkan');
    }

    /**
 * AJAX: Ambil Grup Kelas berdasarkan Angkatan
 */
public function ajaxGrup(Request $request)
{
    return KelasGrup::with('kelasMaster')
        ->where('angkatan_id', $request->angkatan_id)
        ->get()
        ->map(function ($g) {
            return [
                'id' => $g->id,
                'label' => 'Kelas ' . $g->kelasMaster->tingkat
            ];
        });
}

public function ajaxKelas(Request $request)
{
    return Kelas::where('kelas_grup_id', $request->kelas_grup_id)
        ->orderBy('nama_kelas')
        ->get(['id', 'nama_kelas']);
}


    /**
     * DETAIL
     */
    public function detail($id)
    {
        $siswa = Siswa::withTrashed()
            ->with(['kelas.kelasGrup.angkatan'])
            ->findOrFail($id);

        return view('tu.siswa.detail', compact('siswa'));
    }

    /**
     * EDIT
     */
    public function edit($id)
    {
        $siswa = Siswa::withTrashed()
            ->with('kelas.kelasGrup.angkatan')
            ->findOrFail($id);

        return view('tu.siswa.edit', compact('siswa'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $siswa = Siswa::withTrashed()->findOrFail($id);

        $request->validate([
            'kelas_id'       => 'required|exists:kelas,id',
            'nama'           => 'required|string|max:255',
            'nis'            => 'required|numeric|unique:siswa,nis,' . $siswa->id,
            'nisn'           => 'nullable|numeric|unique:siswa,nisn,' . $siswa->id,
            'jenis_kelamin'  => 'required|in:L,P',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable|string',
            'nama_wali'      => 'nullable|string|max:255',
            'telepon_wali'   => 'nullable|string|max:20',
        ]);

        $siswa->update($request->only([
            'kelas_id',
            'nama',
            'nis',
            'nisn',
            'jenis_kelamin',
            'tanggal_lahir',
            'alamat',
            'nama_wali',
            'telepon_wali',
        ]));

        return back()->with('success', 'Data siswa berhasil diperbarui');
    }

    /**
     * SOFT DELETE / RESTORE / FORCE DELETE
     * (TIDAK DIUBAH)
     */
    public function destroy($id)
    {
        Siswa::findOrFail($id)->delete();
        return back()->with('success', 'Siswa dipindahkan ke trash');
    }

    public function restore($id)
    {
        Siswa::withTrashed()->findOrFail($id)->restore();
        return back()->with('success', 'Data siswa berhasil direstore');
    }

    public function forceDelete($id)
    {
        Siswa::withTrashed()->findOrFail($id)->forceDelete();
        return back()->with('success', 'Data siswa dihapus permanen');
    }

    /**
     * IMPORT EXCEL (SUDAH BENAR)
     */
    public function importExcel(Request $request)
    {
        $request->validate([
            'file'     => 'required|mimes:xlsx,xls,csv|max:2048',
            'kelas_id' => 'required|exists:kelas,id',
        ]);

        Excel::import(
            new SiswaImport(kelasId: $request->kelas_id),
            $request->file('file')
        );

        return back()->with('success', 'Data siswa berhasil di-import');
    }
}
