<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\PenempatanSiswa;
class TahunAjaranController extends Controller
{
    /**
     * INDEX — Sorting + Search + Filter Deleted + Pagination
     */
    public function index(Request $request)
    {
        $query = TahunAjaran::query();

        // Search
        if ($request->filled('q')) {
            $query->where(function ($q) use ($request) {
                $q->where('tahun', 'like', '%' . $request->q . '%')
                ->orWhere('semester', 'like', '%' . $request->q . '%')
                ->orWhere('status', 'like', '%' . $request->q . '%');
            });
        }

        // Data terbaru paling atas
        $tahun = $query
            ->orderByDesc('id')
            ->paginate(5)
            ->withQueryString();

        $tahunAktif = TahunAjaran::current();

        return view('tu.tahun.index', compact(
            'tahun',
            'tahunAktif'
        ));
    }

    /**
     * CREATE PAGE
     */
    public function create()
    {
        $last = \App\Models\TahunAjaran::orderByDesc('id')->first();

        if ($last) {
            [$start, $end] = explode('/', $last->tahun);

            if ($last->semester === 'Ganjil') {
                // lanjut ke Genap (tahun sama)
                $tahunAuto = "{$start}/{$end}";
                $semesterAuto = 'Genap';
            } else {
                // lanjut ke tahun baru
                $tahunAuto = ((int)$start + 1) . '/' . ((int)$end + 1);
                $semesterAuto = 'Ganjil';
            }
        } else {
            // kondisi awal (database kosong)
            $year = now()->year;
            $tahunAuto = "{$year}/" . ($year + 1);
            $semesterAuto = 'Ganjil';
        }

        return view('tu.tahun.create', compact('tahunAuto', 'semesterAuto'));
    }

    /**
     * STORE
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'tahun'    => 'required|regex:/^\d{4}\/\d{4}$/',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $exists = TahunAjaran::where('tahun', $data['tahun'])
            ->where('semester', $data['semester'])
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'tahun' => 'Tahun ajaran dan semester ini sudah ada.'
            ])->withInput();
        }

        $last = TahunAjaran::orderByDesc('id')->first();

        if ($last) {

            [$lastStart, $lastEnd] = explode('/', $last->tahun);
            [$newStart, $newEnd]   = explode('/', $data['tahun']);

            $lastStart = (int)$lastStart;
            $lastEnd   = (int)$lastEnd;
            $newStart  = (int)$newStart;
            $newEnd    = (int)$newEnd;

            // CASE A: dari GANJIL → harus GENAP (tahun sama)
            if ($last->semester === 'Ganjil') {

                if (
                    $data['semester'] !== 'Genap' ||
                    $newStart !== $lastStart ||
                    $newEnd !== $lastEnd
                ) {
                    return back()->withErrors([
                        'tahun' => 'Urutan salah. Setelah Ganjil harus Genap di tahun yang sama.'
                    ])->withInput();
                }
            }

            // CASE B: dari GENAP → harus GANJIL tahun berikutnya
            if ($last->semester === 'Genap') {

                if (
                    $data['semester'] !== 'Ganjil' ||
                    $newStart !== $lastStart + 1 ||
                    $newEnd !== $lastEnd + 1
                ) {
                    return back()->withErrors([
                        'tahun' => 'Urutan salah. Setelah Genap harus Ganjil tahun berikutnya.'
                    ])->withInput();
                }
            }
        }

        // 🔵 SIMPAN
        TahunAjaran::create([
            'tahun'    => $data['tahun'],
            'semester' => $data['semester'],
            'status'   => 'draft',
        ]);

        return redirect()
            ->route('tu.tahun.index')
            ->with('success', 'Tahun ajaran berhasil dibuat');
    }

    /**
     * EDIT PAGE
     */
    public function edit($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        return view('tu.tahun.edit', compact('tahun'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $tahun = TahunAjaran::findOrFail($id);

        $data = $request->validate([
            'tahun'    => 'required|regex:/^\d{4}\/\d{4}$/',
            'semester' => 'required|in:Ganjil,Genap',
        ]);

        $tahun->update([
            'tahun'    => $data['tahun'],
            'semester' => $data['semester'],
        ]);

        return redirect()
            ->route('tu.tahun.index')
            ->with('success', 'Tahun ajaran berhasil diperbarui!');
    }


    /**
     * DELETE (Soft Delete)
     */
    public function destroy($id)
    {
        $tahun = TahunAjaran::findOrFail($id);
        $tahun->delete();

        return redirect()->route('tu.tahun.index')
            ->with('success', 'Data dipindahkan ke trash.');
    }

    /**
     * RESTORE
     */
    public function restore($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        $tahun->restore();

        return back()->with('success', 'Data berhasil direstore.');
    }

    /**
     * FORCE DELETE
     */
    public function forceDelete($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        $tahun->forceDelete();

        return back()->with('success', 'Data berhasil dihapus permanen.');
    }

    /**
     * BULK ACTION
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,restore,forceDelete',
            'ids'    => 'required|array',
        ]);

        switch ($request->action) {
            case 'delete':
                TahunAjaran::whereIn('id', $request->ids)->delete();
                break;

            case 'restore':
                TahunAjaran::onlyTrashed()->whereIn('id', $request->ids)->restore();
                break;

            case 'forceDelete':
                TahunAjaran::onlyTrashed()->whereIn('id', $request->ids)->forceDelete();
                break;
        }

        return response()->json(['message' => 'Aksi berhasil dilakukan.']);
    }

    /**
     * AJAX SEARCH
     */
    public function ajaxSearch(Request $request)
    {
        $keyword = $request->q;

        $data = TahunAjaran::where('tahun', 'like', "%$keyword%")
            ->orderBy('tahun')
            ->get();

        return response()->json($data);
    }

    public function detail($id)
    {
        $tahun = TahunAjaran::withTrashed()->findOrFail($id);
        return view('tu.tahun.detail', compact('tahun'));
    }

    public function tutupTahun()
    {
        $genap = TahunAjaran::where('status', 'aktif')
            ->where('semester', 'Genap')
            ->first();

        if (!$genap) {
            return back()->with('error', 'Tidak ada semester genap aktif');
        }

        // VALIDASI WAJIB: semua siswa sudah diproses
        $masihAda = PenempatanSiswa::where('tahun_ajaran_id', $genap->id)
            ->whereNull('tanggal_keluar')
            ->count();

        if ($masihAda > 0) {
            return back()->with('error', "Masih ada {$masihAda} siswa belum diproses naik kelas");
        }

        DB::transaction(function () use ($genap) {
            $genap->update(['status' => 'selesai']);
        });

        return back()->with('success', 'Tahun ajaran berhasil ditutup');
    }
    public function aktifkan($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        if ($ta->status !== 'draft') {
            return back()->with('error', 'Hanya draft yang bisa diaktifkan');
        }

        // pastikan tidak ada aktif lain
        if (TahunAjaran::where('status', 'aktif')->exists()) {
            return back()->with('error', 'Masih ada tahun ajaran aktif');
        }

        // validasi urutan
        $last = TahunAjaran::where('status', 'selesai')
            ->orderByDesc('id')
            ->first();

        if ($last) {
            if ($last->semester === 'Ganjil' && $ta->semester !== 'Genap') {
                return back()->with('error', 'Urutan salah: setelah Ganjil harus Genap');
            }

            if ($last->semester === 'Genap' && $ta->semester !== 'Ganjil') {
                return back()->with('error', 'Urutan salah: setelah Genap harus Ganjil');
            }
        }

        $ta->update([
            'status' => 'aktif'
        ]);

        return back()->with('success', 'Tahun ajaran berhasil diaktifkan');
    }
    public function lanjutSemester()
    {
        $ganjil = TahunAjaran::where('status', 'aktif')
            ->where('semester', 'Ganjil')
            ->first();

        if (!$ganjil) {
            return back()->with('error', 'Tidak ada semester ganjil aktif');
        }

        $genap = TahunAjaran::where('tahun', $ganjil->tahun)
            ->where('semester', 'Genap')
            ->first();

        if (!$genap) {
            return back()->with('error', 'Semester genap belum dibuat');
        }

        if ($genap->status !== 'draft') {
            return back()->with('error', 'Semester genap sudah pernah diproses');
        }

        DB::transaction(function () use ($ganjil, $genap) {
            $ganjil->update(['status' => 'selesai']);
            $genap->update(['status' => 'aktif']);
        });

        return back()->with('success', 'Berhasil pindah ke semester genap');
    }
    
}
