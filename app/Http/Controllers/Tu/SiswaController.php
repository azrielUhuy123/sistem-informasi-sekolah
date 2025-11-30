<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiswaImport;
use App\Exports\SiswaExport;
use Barryvdh\DomPDF\Facade\Pdf;

class SiswaController extends Controller
{
    // IMPORT EXCEL
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return back()->with('success', 'Import berhasil');
    }

    // ============================
    // LIST SISWA + SORTING ONLY
    // ============================
    public function index(Request $request)
    {
        $query = Siswa::query()->with('kelas');

        // --- Sorting ---
        $sort  = $request->get('sort', 'nama'); // default sort by nama
        $order = $request->get('order', 'asc');

        // kolom yang diizinkan disort
        $allowed = ['nis', 'nisn', 'nama', 'kelas_id', 'jenis_kelamin', 'tanggal_lahir'];

        if (!in_array($sort, $allowed)) {
            $sort = 'nama';
        }

        $query->orderBy($sort, $order);

        // Pagination + keep sort parameters
        $siswa = $query->paginate(25)->withQueryString();

        // Data kelas untuk dropdown (jika nanti dibutuhkan)
        $kelas = Kelas::all();

        return view('tu.siswa.index', compact('siswa', 'kelas'));
    }

    // FORM TAMBAH
    public function create()
    {
        $kelas = Kelas::all();
        return view('tu.siswa.create', compact('kelas'));
    }

    // SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'nama'           => 'required',
            'nis'            => 'required|unique:siswa',
            'nisn'           => 'nullable',
            'kelas_id'       => 'required|exists:kelas,id',
            'jenis_kelamin'  => 'required',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable',
            'nama_wali'      => 'nullable',
            'telepon_wali'   => 'nullable',
        ]);

        Siswa::create($request->all());

        return redirect()->route('tu.siswa.index')
                         ->with('success', 'Siswa berhasil ditambahkan');
    }

    // FORM EDIT
    public function edit($id)
    {
        $siswa = Siswa::findOrFail($id);
        $kelas = Kelas::all();

        return view('tu.siswa.edit', compact('siswa', 'kelas'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $siswa = Siswa::findOrFail($id);

        $request->validate([
            'nama'           => 'required',
            'nis'            => 'required|unique:siswa,nis,' . $siswa->id,
            'nisn'           => 'nullable',
            'kelas_id'       => 'required|exists:kelas,id',
            'jenis_kelamin'  => 'required',
            'tanggal_lahir'  => 'nullable|date',
            'alamat'         => 'nullable',
            'nama_wali'      => 'nullable',
            'telepon_wali'   => 'nullable',
        ]);

        $siswa->update($request->all());

        return redirect()->route('tu.siswa.index')
                         ->with('success', 'Siswa berhasil diupdate');
    }

    // DELETE
    public function destroy($id)
    {
        $siswa = Siswa::findOrFail($id);
        $siswa->delete();

        return redirect()->route('tu.siswa.index')
                         ->with('success', 'Siswa berhasil dihapus');
    }

    // DETAIL
    public function detail($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);
        return view('tu.siswa.detail', compact('siswa'));
    }

    // EXPORT EXCEL
    public function exportExcel()
    {
        return Excel::download(new SiswaExport, 'data_siswa.xlsx');
    }

    // EXPORT PDF
    public function exportPDF()
    {
        $siswa = Siswa::all();
        $pdf = Pdf::loadView('tu.siswa.export-pdf', compact('siswa'));
        return $pdf->download('data_siswa.pdf');
    }
}
