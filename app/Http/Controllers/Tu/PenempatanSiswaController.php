<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenempatanSiswaController extends Controller
{
    /**
     * FORM ASSIGN SISWA KE KELAS
     * (dipanggil dari tombol "Assign Kelas" di menu SISWA)
     */
    public function create(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:siswa,id'
    ]);

    $siswa = Siswa::findOrFail($request->siswa_id);

    $tahunAktif = TahunAjaran::where('aktif', 1)->firstOrFail();

    $kelas = Kelas::where('tahun_ajaran_id', $tahunAktif->id)->get();

    return view('tu.penempatan-siswa.create', compact(
        'siswa',
        'kelas',
        'tahunAktif'
    ));
}


    /**
     * SIMPAN PENEMPATAN SISWA
     */
    public function store(Request $request)
{
    $request->validate([
        'siswa_id' => 'required|exists:siswa,id',
        'kelas_id' => 'required|exists:kelas,id',
        'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
    ]);

    // pastikan tidak ada aktif ganda
    PenempatanSiswa::where('siswa_id', $request->siswa_id)
        ->where('status', 'aktif')
        ->update(['status' => 'naik']);

    PenempatanSiswa::create([
        'siswa_id'        => $request->siswa_id,
        'kelas_id'        => $request->kelas_id,
        'tahun_ajaran_id' => $request->tahun_ajaran_id,
        'status'          => 'aktif',
    ]);

    return redirect()
        ->route('tu.siswa.index')
        ->with('success', 'Siswa berhasil ditempatkan ke kelas');
}


    /**
     * PREVIEW NAIK KELAS
     * (tanpa mengubah data)
     */
    public function previewNaikKelas()
    {
        $tahunAktif = TahunAjaran::where('aktif', 1)->firstOrFail();

        $tahunBerikutnya = TahunAjaran::where('id', '>', $tahunAktif->id)
            ->orderBy('id')
            ->first();

        if (!$tahunBerikutnya) {
            return back()->with('error', 'Tahun ajaran berikutnya belum tersedia');
        }

        $penempatan = PenempatanSiswa::with(['siswa', 'kelas'])
            ->where('tahun_ajaran_id', $tahunAktif->id)
            ->where('status', 'aktif')
            ->get();

        $preview = $penempatan->map(function ($p) use ($tahunBerikutnya) {

            $kelasTujuan = Kelas::where('tingkat', $p->kelas->tingkat + 1)
                ->where('tahun_ajaran_id', $tahunBerikutnya->id)
                ->first();

            return [
                'siswa'        => $p->siswa,
                'kelas_asal'   => $p->kelas->nama_kelas,
                'kelas_tujuan' => $kelasTujuan?->nama_kelas ?? 'LULUS',
                'status'       => $kelasTujuan ? 'Naik' : 'Lulus',
            ];
        });

        return view(
            'tu.penempatan-siswa.preview-naik-kelas',
            compact('preview', 'tahunAktif', 'tahunBerikutnya')
        );
    }

    /**
     * EKSEKUSI NAIK KELAS (MASS PROMOTION)
     */
    public function naikKelas()
    {
        $tahunAktif = TahunAjaran::where('aktif', 1)->firstOrFail();

        $tahunBerikutnya = TahunAjaran::where('id', '>', $tahunAktif->id)
            ->orderBy('id')
            ->firstOrFail();

        DB::transaction(function () use ($tahunAktif, $tahunBerikutnya) {

            $penempatanAktif = PenempatanSiswa::with('kelas')
                ->where('tahun_ajaran_id', $tahunAktif->id)
                ->where('status', 'aktif')
                ->get();

            foreach ($penempatanAktif as $item) {

                // Tandai penempatan lama
                $item->update(['status' => 'naik']);

                // Cari kelas tingkat berikutnya
                $kelasTujuan = Kelas::where('tingkat', $item->kelas->tingkat + 1)
                    ->where('tahun_ajaran_id', $tahunBerikutnya->id)
                    ->first();

                if ($kelasTujuan) {
                    // Naik kelas
                    PenempatanSiswa::create([
                        'siswa_id'        => $item->siswa_id,
                        'kelas_id'        => $kelasTujuan->id,
                        'tahun_ajaran_id' => $tahunBerikutnya->id,
                        'status'          => 'aktif',
                    ]);
                } else {
                    // Lulus
                    PenempatanSiswa::create([
                        'siswa_id'        => $item->siswa_id,
                        'kelas_id'        => null,
                        'tahun_ajaran_id' => $tahunBerikutnya->id,
                        'status'          => 'lulus',
                    ]);
                }
            }

            // Nonaktifkan tahun ajaran lama, aktifkan baru
            $tahunAktif->update(['aktif' => 0]);
            $tahunBerikutnya->update(['aktif' => 1]);
        });

        return redirect()
            ->route('tu.siswa.index')
            ->with('success', 'Naik kelas berhasil diproses');
    }
}
