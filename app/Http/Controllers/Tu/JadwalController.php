<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;
use App\Models\Guru;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class JadwalController extends Controller
{
    // LIST
    public function index(Request $request)
    {
        $kelas_id = $request->kelas_id ?? null;

        $jadwal = Jadwal::with(['kelas', 'guru', 'mapel'])
            ->when($kelas_id, function($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id);
            })
            ->orderBy('id', 'desc')
            ->paginate(20);

        $kelas = Kelas::orderBy('nama_kelas')->get();

        return view('tu.jadwal.index', compact('jadwal', 'kelas', 'kelas_id'));
    }

    // CREATE
    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $guru  = Guru::orderBy('nama')->get();
        $mapel = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.jadwal.create', compact('kelas','guru','mapel'));
    }

    // EDIT
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $kelas  = Kelas::orderBy('nama_kelas')->get();
        $guru   = Guru::orderBy('nama')->get();
        $mapel  = MataPelajaran::orderBy('nama_mapel')->get();

        return view('tu.jadwal.edit', compact('jadwal', 'kelas', 'guru', 'mapel'));
    }

    // DETAIL
    public function detail($id)
    {
        $jadwal = Jadwal::with(['kelas', 'guru', 'mapel'])
            ->findOrFail($id);

        return view('tu.jadwal.detail', compact('jadwal'));
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        // VALIDASI BENTROK KELAS
        $bentrok = Jadwal::where('kelas_id', $request->kelas_id)
            ->where('hari', $request->hari)
            ->where(function($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors(['msg'=>'Jadwal bentrok dengan kelas lain'])->withInput();
        }

        // VALIDASI BENTROK GURU
        $guruBentrok = Jadwal::where('guru_id', $request->guru_id)
            ->where('hari', $request->hari)
            ->where(function($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($guruBentrok) {
            return back()->withErrors(['msg'=>'Guru sudah mengajar pada jam tersebut'])->withInput();
        }

        Jadwal::create($request->only([
            'kelas_id','guru_id','mapel_id','hari','jam_mulai','jam_selesai'
        ]));

        return redirect()->route('tu.jadwal.index')->with('success','Jadwal berhasil ditambahkan');
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'guru_id' => 'required|exists:guru,id',
            'mapel_id' => 'required|exists:mata_pelajaran,id',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required|after:jam_mulai',
        ]);

        // VALIDASI BENTROK KELAS
        $bentrok = Jadwal::where('kelas_id', $request->kelas_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id)
            ->where(function($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($bentrok) {
            return back()->withErrors(['msg'=>'Jadwal bentrok di kelas ini'])->withInput();
        }

        // VALIDASI BENTROK GURU
        $guruBentrok = Jadwal::where('guru_id', $request->guru_id)
            ->where('hari', $request->hari)
            ->where('id', '!=', $id)
            ->where(function($q) use ($request) {
                $q->where('jam_mulai', '<', $request->jam_selesai)
                  ->where('jam_selesai', '>', $request->jam_mulai);
            })
            ->exists();

        if ($guruBentrok) {
            return back()->withErrors(['msg'=>'Guru bentrok pada jam tersebut'])->withInput();
        }

        $jadwal->update($request->only([
            'kelas_id','guru_id','mapel_id','hari','jam_mulai','jam_selesai'
        ]));

        return redirect()->route('tu.jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
    }

    // EXPORT PDF PER KELAS
    public function exportPDF($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);

        $jadwal = Jadwal::with(['guru','mapel'])
            ->where('kelas_id', $kelas_id)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        $pdf = Pdf::loadView('tu.jadwal.export-pdf', compact('kelas', 'jadwal'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('jadwal_kelas_' . $kelas->nama_kelas . '.pdf');
    }

    // EXPORT PDF PER GURU
    public function exportGuruPDF($guru_id)
    {
        $guru = Guru::findOrFail($guru_id);

        $jadwal = Jadwal::with(['kelas','mapel'])
            ->where('guru_id', $guru_id)
            ->orderByRaw("FIELD(hari, 'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu')")
            ->orderBy('jam_mulai')
            ->get();

        $pdf = Pdf::loadView('tu.jadwal.export-guru-pdf', compact('guru', 'jadwal'))
                ->setPaper('a4', 'portrait');

        return $pdf->download('jadwal_guru_' . $guru->nama . '.pdf');
    }
}
