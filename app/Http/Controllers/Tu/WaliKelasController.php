<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\WaliKelas;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class WaliKelasController extends Controller
{
    public function index()
    {
        $wali = WaliKelas::with(['guru','kelas','tahun'])
            ->orderBy('tahunajaran_id', 'desc')
            ->paginate(20);

        return view('tu.walikelas.index', compact('wali'));
    }

    public function create()
    {
        $guru = Guru::orderBy('nama')->get();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $tahun = TahunAjaran::orderBy('tahun_mulai', 'desc')->get();

        return view('tu.walikelas.create', compact('guru','kelas','tahun'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:guru,id',
            'kelas_id' => 'required|exists:kelas,id',
            'tahunajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        // Validasi: kelas sudah punya wali kelas di tahun ajaran yang sama?
        $cek = WaliKelas::where('kelas_id', $request->kelas_id)
                ->where('tahunajaran_id', $request->tahunajaran_id)
                ->first();

        if ($cek) {
            return back()->withErrors(['kelas_id' => 'Kelas ini sudah memiliki wali kelas pada tahun ajaran tersebut!'])
                         ->withInput();
        }

        WaliKelas::create($request->all());

        return redirect()->route('tu.walikelas.index')
                         ->with('success', 'Wali kelas berhasil ditambahkan');
    }

    public function destroy($id)
    {
        $wali = WaliKelas::findOrFail($id);
        $wali->delete();

        return back()->with('success', 'Wali kelas berhasil dihapus');
    }
}
