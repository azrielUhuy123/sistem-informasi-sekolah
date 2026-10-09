<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Helpers\TingkatKelasHelper;
use App\Models\Kelas;
use App\Models\KeputusanKenaikan;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
            return back()->with('error', 'Tidak ada Semester Genap yang aktif.');
        }

        $tahunTujuan = $this->tahunBerikutnya($genap->tahun);

        if (!$tahunTujuan) {
            return back()->with('error', 'Format Tahun Ajaran Genap tidak valid.');
        }

        $ganjilTujuan = TahunAjaran::where('tahun', $tahunTujuan)
            ->where('semester', 'Ganjil')
            ->first();

        if (!$ganjilTujuan) {
            return back()->with('error', "Periode tujuan {$tahunTujuan} Ganjil belum tersedia.");
        }

        if ($ganjilTujuan->status !== 'draft') {
            return back()->with('error', "Periode tujuan {$tahunTujuan} Ganjil harus berstatus Draft.");
        }

        $hasil = $this->annualRollover($genap->id, $ganjilTujuan->id, ['aktif']);

        return back()->with(
            'success',
            "Penutupan Tahun Ajaran dan Kenaikan Kelas berhasil. Tahun Ajaran {$tahunTujuan} Ganjil diaktifkan. {$this->ringkasanAnnualRollover($hasil)}"
        );
    }
    public function aktifkan($id)
    {
        $ta = TahunAjaran::findOrFail($id);

        if ($ta->status !== 'draft') {
            return back()->with('error', 'Hanya Tahun Ajaran berstatus Draft yang bisa diaktifkan.');
        }

        // pastikan tidak ada aktif lain
        if (TahunAjaran::where('status', 'aktif')->exists()) {
            return back()->with('error', 'Masih ada tahun ajaran aktif');
        }

        if ($ta->semester === 'Genap') {
            $ganjilSumber = TahunAjaran::where('tahun', $ta->tahun)
                ->where('semester', 'Ganjil')
                ->first();

            if (!$ganjilSumber || $ganjilSumber->status !== 'selesai') {
                return back()->with('error', 'Semester Ganjil pada tahun yang sama harus berstatus Selesai sebelum Semester Genap diaktifkan.');
            }

            $jumlah = $this->transisikanKeGenap($ganjilSumber->id, $ta->id, ['selesai']);
            $this->logPlacementYangDilewati($ganjilSumber->id, $ta->id, $jumlah);

            return back()->with(
                'success',
                'Peralihan Semester berhasil. Semester Genap diaktifkan. ' . $this->ringkasanTransisi($jumlah)
            );
        }

        if ($ta->semester === 'Ganjil') {
            $tahunSumber = $this->tahunSebelumnya($ta->tahun);

            if (!$tahunSumber) {
                return back()->with('error', 'Format Tahun Ajaran target tidak valid.');
            }

            $genapSumber = TahunAjaran::where('tahun', $tahunSumber)
                ->where('semester', 'Genap')
                ->first();

            if ($genapSumber) {
                if ($genapSumber->status !== 'selesai') {
                    return back()->with('error', "Periode sumber {$tahunSumber} Genap harus berstatus Selesai terlebih dahulu.");
                }

                $hasil = $this->annualRollover($genapSumber->id, $ta->id, ['selesai']);

                return back()->with(
                    'success',
                    "Penutupan Tahun Ajaran dan Kenaikan Kelas berhasil. Tahun Ajaran {$ta->tahun} Ganjil diaktifkan. {$this->ringkasanAnnualRollover($hasil)}"
                );
            }

            if (TahunAjaran::where('status', 'selesai')->exists()) {
                return back()->with('error', "Periode sumber {$tahunSumber} Genap tidak tersedia.");
            }
        }

        $ta->update([
            'status' => 'aktif'
        ]);

        return back()->with('success', 'Tahun ajaran berhasil diaktifkan');
    }

    private function annualRollover(int $sourceId, int $targetId, array $sourceStatuses): array
    {
        return DB::transaction(function () use ($sourceId, $targetId, $sourceStatuses) {
            $genap = TahunAjaran::whereKey($sourceId)
                ->lockForUpdate()
                ->firstOrFail();
            $ganjil = TahunAjaran::whereKey($targetId)
                ->lockForUpdate()
                ->firstOrFail();

            $tahunTujuan = $this->tahunBerikutnya($genap->tahun);

            if (
                !in_array($genap->status, $sourceStatuses, true) ||
                $genap->semester !== 'Genap' ||
                !$tahunTujuan ||
                $ganjil->tahun !== $tahunTujuan ||
                $ganjil->semester !== 'Ganjil' ||
                $ganjil->status !== 'draft'
            ) {
                throw ValidationException::withMessages([
                    'tahun' => 'Periode sumber atau target berubah/tidak sesuai untuk Penutupan Tahun Ajaran dan Kenaikan Kelas.',
                ]);
            }

            $periodeAktifLain = TahunAjaran::where('status', 'aktif')
                ->where('id', '!=', $genap->id)
                ->exists();

            if ($periodeAktifLain) {
                throw ValidationException::withMessages([
                    'tahun' => 'Ada Tahun Ajaran berstatus Aktif lain; Penutupan Tahun Ajaran dan Kenaikan Kelas dibatalkan.',
                ]);
            }

            $placements = PenempatanSiswa::with([
                'siswa',
                'kelas.kelasGrup.kelasMaster',
            ])
                ->where('tahun_ajaran_id', $genap->id)
                ->whereNull('tanggal_keluar')
                ->lockForUpdate()
                ->get();

            $plans = [];
            $seenStudents = [];

            foreach ($placements as $placement) {
                if (isset($seenStudents[$placement->siswa_id])) {
                    throw ValidationException::withMessages([
                        'siswa' => 'Ditemukan lebih dari satu penempatan siswa aktif pada periode sebelumnya.',
                    ]);
                }

                $seenStudents[$placement->siswa_id] = true;
                $siswa = $placement->siswa;
                $kelasAsal = $placement->kelas;
                $grupAsal = $kelasAsal?->kelasGrup;
                $kelasMasterAsal = $grupAsal?->kelasMaster;

                if (!$siswa || !$kelasAsal || !$grupAsal || !$kelasMasterAsal) {
                    throw ValidationException::withMessages([
                        'siswa' => "Penempatan siswa sebelumnya {$placement->id} memiliki relasi sumber yang tidak lengkap.",
                    ]);
                }

                if (
                    !$siswa->angkatan_id ||
                    !$grupAsal->angkatan_id ||
                    (int) $siswa->angkatan_id !== (int) $grupAsal->angkatan_id
                ) {
                    throw ValidationException::withMessages([
                        'siswa' => "Angkatan siswa pada penempatan sebelumnya {$placement->id} tidak sesuai dengan kelas asal.",
                    ]);
                }

                $mengulangTingkatVI = false;

                if ($kelasMasterAsal->tingkat === 'VI') {
                    $keputusan = KeputusanKenaikan::where('siswa_id', $siswa->id)
                        ->where('kelas_id', $kelasAsal->id)
                        ->where('tahun_ajaran_id', $genap->id)
                        ->lockForUpdate()
                        ->get();

                    if ($keputusan->count() !== 1 || $keputusan->first()->approval_status !== 'approved') {
                        throw ValidationException::withMessages([
                            'siswa' => "Keputusan kenaikan siswa tingkat VI pada penempatan {$placement->id} belum final.",
                        ]);
                    }

                    $mengulangTingkatVI = $keputusan->first()->status === 'tinggal';
                    $tingkatTujuan = $mengulangTingkatVI ? 'VI' : 'LULUS';
                } else {
                    $tingkatTujuan = TingkatKelasHelper::berikutnya($kelasMasterAsal->tingkat);
                }

                if (!$tingkatTujuan) {
                    throw ValidationException::withMessages([
                        'siswa' => "Tingkat tujuan untuk penempatan siswa {$placement->id} tidak dapat ditentukan.",
                    ]);
                }

                $kelasTujuan = null;
                $placementTujuan = null;

                if ($tingkatTujuan !== 'LULUS') {
                    $prefixKelasAsal = $mengulangTingkatVI
                        ? 'VI'
                        : $kelasMasterAsal->tingkat;
                    $rombel = str_starts_with($kelasAsal->nama_kelas, $prefixKelasAsal)
                        ? substr($kelasAsal->nama_kelas, strlen($prefixKelasAsal))
                        : '';

                    if (!preg_match('/^[A-Za-z]+$/', $rombel)) {
                        throw ValidationException::withMessages([
                            'kelas' => "Kode rombel pada penempatan siswa sebelumnya {$placement->id} tidak valid.",
                        ]);
                    }

                    $namaKelasTujuan = $tingkatTujuan . $rombel;

                    $kelasTujuan = Kelas::where('nama_kelas', $namaKelasTujuan)
                        ->whereHas('kelasGrup', function ($query) use ($siswa, $tingkatTujuan) {
                            $query->where('angkatan_id', $siswa->angkatan_id)
                                ->whereHas('kelasMaster', function ($query) use ($tingkatTujuan) {
                                    $query->where('tingkat', $tingkatTujuan);
                                });
                        })
                        ->first();

                    if (
                        !$kelasTujuan ||
                        (int) $kelasTujuan->kelasGrup?->angkatan_id !== (int) $siswa->angkatan_id ||
                        $kelasTujuan->kelasGrup?->kelasMaster?->tingkat !== $tingkatTujuan
                    ) {
                        throw ValidationException::withMessages([
                            'kelas' => "Kelas tujuan {$namaKelasTujuan} untuk penempatan siswa {$placement->id}, tingkat {$kelasMasterAsal->tingkat}, Angkatan {$siswa->angkatan_id} tidak tersedia.",
                        ]);
                    }

                    $placementsTujuan = PenempatanSiswa::where('siswa_id', $siswa->id)
                        ->where('tahun_ajaran_id', $ganjil->id)
                        ->whereNull('tanggal_keluar')
                        ->lockForUpdate()
                        ->get();

                    if ($placementsTujuan->count() > 1) {
                        throw ValidationException::withMessages([
                            'siswa' => 'Siswa memiliki lebih dari satu penempatan siswa aktif pada periode berikutnya.',
                        ]);
                    }

                    $placementTujuan = $placementsTujuan->first();

                    if ($placementTujuan && (int) $placementTujuan->kelas_id !== (int) $kelasTujuan->id) {
                        throw ValidationException::withMessages([
                            'siswa' => 'Penempatan siswa pada periode berikutnya menunjuk ke kelas yang berbeda.',
                        ]);
                    }
                } else {
                    $placementTujuan = PenempatanSiswa::where('siswa_id', $siswa->id)
                        ->where('tahun_ajaran_id', $ganjil->id)
                        ->whereNull('tanggal_keluar')
                        ->lockForUpdate()
                        ->first();

                    if ($placementTujuan) {
                        throw ValidationException::withMessages([
                            'siswa' => 'Siswa tingkat VI sudah memiliki penempatan siswa aktif pada periode berikutnya.',
                        ]);
                    }
                }

                $plans[] = [
                    'source' => $placement,
                    'target_class' => $kelasTujuan,
                    'target_placement' => $placementTujuan,
                ];
            }

            $tanggalRollover = now()->toDateString();
            $dibuat = 0;
            $sudahAda = 0;
            $lulus = 0;

            foreach ($plans as $plan) {
                $plan['source']->update(['tanggal_keluar' => $tanggalRollover]);

                if (!$plan['target_class']) {
                    $lulus++;
                    continue;
                }

                if ($plan['target_placement']) {
                    $sudahAda++;
                    continue;
                }

                PenempatanSiswa::create([
                    'siswa_id' => $plan['source']->siswa_id,
                    'kelas_id' => $plan['target_class']->id,
                    'tahun_ajaran_id' => $ganjil->id,
                    'tanggal_masuk' => $tanggalRollover,
                    'tanggal_keluar' => null,
                ]);
                $dibuat++;
            }

            $genap->update(['status' => 'selesai']);
            $ganjil->update(['status' => 'aktif']);

            return compact('dibuat', 'sudahAda', 'lulus');
        });
    }

    private function tahunBerikutnya(string $tahun): ?string
    {
        if (!preg_match('/^(\d{4})\/(\d{4})$/', $tahun, $match)) {
            return null;
        }

        return ((int) $match[1] + 1) . '/' . ((int) $match[2] + 1);
    }

    private function tahunSebelumnya(string $tahun): ?string
    {
        if (!preg_match('/^(\d{4})\/(\d{4})$/', $tahun, $match)) {
            return null;
        }

        return ((int) $match[1] - 1) . '/' . ((int) $match[2] - 1);
    }

    private function ringkasanAnnualRollover(array $hasil): string
    {
        return "Penempatan siswa dibuat: {$hasil['dibuat']}; sudah ada: {$hasil['sudahAda']}; ditutup sebagai LULUS: {$hasil['lulus']}.";
    }
    public function lanjutSemester()
    {
        $ganjil = TahunAjaran::where('status', 'aktif')
            ->where('semester', 'Ganjil')
            ->first();

        if (!$ganjil) {
            return back()->with('error', 'Tidak ada Semester Ganjil yang aktif.');
        }

        $genap = TahunAjaran::where('tahun', $ganjil->tahun)
            ->where('semester', 'Genap')
            ->first();

        if (!$genap) {
            return back()->with('error', 'Semester Genap belum dibuat untuk Peralihan Semester.');
        }

        if ($genap->status !== 'draft') {
            return back()->with('error', 'Semester Genap sudah pernah diproses.');
        }

        $jumlah = $this->transisikanKeGenap($ganjil->id, $genap->id, ['aktif']);
        $this->logPlacementYangDilewati($ganjil->id, $genap->id, $jumlah);

        return back()->with(
            'success',
            'Peralihan Semester Ganjil ke Genap berhasil. ' . $this->ringkasanTransisi($jumlah)
        );
    }

    private function transisikanKeGenap(int $ganjilId, int $genapId, array $statusGanjil): array
    {
        $jumlahDibuat = 0;
        $jumlahSudahAda = 0;
        $jumlahMismatch = 0;
        $jumlahTidakValid = 0;

        DB::transaction(function () use (
            $ganjilId,
            $genapId,
            $statusGanjil,
            &$jumlahDibuat,
            &$jumlahSudahAda,
            &$jumlahMismatch,
            &$jumlahTidakValid
        ) {
            $ganjil = TahunAjaran::whereKey($ganjilId)
                ->lockForUpdate()
                ->firstOrFail();
            $genap = TahunAjaran::whereKey($genapId)
                ->lockForUpdate()
                ->firstOrFail();

            if (
                !in_array($ganjil->status, $statusGanjil, true) ||
                $ganjil->semester !== 'Ganjil' ||
                $genap->status !== 'draft' ||
                $genap->semester !== 'Genap' ||
                $genap->tahun !== $ganjil->tahun
            ) {
                throw ValidationException::withMessages([
                    'tahun' => 'Status atau urutan semester berubah. Muat ulang sebelum melanjutkan.',
                ]);
            }

            $placements = PenempatanSiswa::with([
                'siswa',
                'kelas.kelasGrup.kelasMaster',
            ])
                ->where('tahun_ajaran_id', $ganjil->id)
                ->whereNull('tanggal_keluar')
                ->lockForUpdate()
                ->get();

            $tanggalTransisi = now()->toDateString();

            foreach ($placements as $placement) {
                $siswa = $placement->siswa;
                $kelas = $placement->kelas;
                $angkatanKelas = $kelas?->kelasGrup?->angkatan_id;

                if (
                    !$siswa ||
                    !$kelas ||
                    !$kelas->kelasGrup ||
                    !$kelas->kelasGrup->kelasMaster ||
                    !$angkatanKelas
                ) {
                    $jumlahTidakValid++;
                    continue;
                }

                if (
                    !$siswa->angkatan_id ||
                    (int) $siswa->angkatan_id !== (int) $angkatanKelas
                ) {
                    $jumlahMismatch++;
                    continue;
                }

                $placementGenapAda = PenempatanSiswa::where('siswa_id', $placement->siswa_id)
                    ->where('tahun_ajaran_id', $genap->id)
                    ->exists();

                if ($placementGenapAda) {
                    $jumlahSudahAda++;
                    continue;
                }

                PenempatanSiswa::create([
                    'siswa_id' => $placement->siswa_id,
                    'kelas_id' => $placement->kelas_id,
                    'tahun_ajaran_id' => $genap->id,
                    'tanggal_masuk' => $tanggalTransisi,
                    'tanggal_keluar' => null,
                ]);

                $jumlahDibuat++;
            }

            $ganjil->update(['status' => 'selesai']);
            $genap->update(['status' => 'aktif']);
        });

        return compact('jumlahDibuat', 'jumlahSudahAda', 'jumlahMismatch', 'jumlahTidakValid');
    }

    private function logPlacementYangDilewati(int $ganjilId, int $genapId, array $jumlah): void
    {
        if ($jumlah['jumlahMismatch'] > 0) {
            Log::warning('Transisi semester melewati placement dengan Angkatan berbeda.', [
                'tahun_ajaran_ganjil_id' => $ganjilId,
                'tahun_ajaran_genap_id' => $genapId,
                'jumlah_dilewati' => $jumlah['jumlahMismatch'],
            ]);
        }

        if ($jumlah['jumlahTidakValid'] > 0) {
            Log::warning('Transisi semester melewati placement dengan relasi siswa/kelas tidak lengkap.', [
                'tahun_ajaran_ganjil_id' => $ganjilId,
                'tahun_ajaran_genap_id' => $genapId,
                'jumlah_dilewati' => $jumlah['jumlahTidakValid'],
            ]);
        }
    }

    private function ringkasanTransisi(array $jumlah): string
    {
        return "Penempatan siswa dibuat: {$jumlah['jumlahDibuat']}; sudah ada: {$jumlah['jumlahSudahAda']}; dilewati karena ketidaksesuaian Angkatan: {$jumlah['jumlahMismatch']}; relasi tidak valid: {$jumlah['jumlahTidakValid']}.";
    }
    
}
