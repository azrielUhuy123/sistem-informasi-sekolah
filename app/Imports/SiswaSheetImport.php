<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithTitle;

class SiswaSheetImport implements ToCollection, WithHeadingRow, WithTitle
{
    protected $sheetName;
    protected $tahunAjaran;

    public function __construct()
    {
        $this->tahunAjaran = TahunAjaran::where('status', 'aktif')->first();

        if (!$this->tahunAjaran) {
            throw new \Exception("Tidak ada tahun ajaran aktif.");
        }
    }

    public function title(): string
    {
        return $this->sheetName;
    }

    public function collection(Collection $rows)
    {
        $kelas = Kelas::where('nama_kelas', $this->sheetName)->first();

        if (!$kelas) {
            return; // Skip jika nama sheet tidak ada di tabel kelas
        }

        DB::transaction(function () use ($rows, $kelas) {

            foreach ($rows as $row) {

                if (empty($row['nis'])) {
                    continue;
                }

                $nis  = trim((string) $row['nis']);
                $nisn = trim((string) $row['nisn']);

                $siswa = Siswa::firstOrCreate(
                    ['nis' => $nis],
                    [
                        'nisn' => $nisn,
                        'nama' => $row['nama'],
                        'jenis_kelamin' => $row['jenis_kelamin'],
                        'tanggal_lahir' => Carbon::parse($row['tanggal_lahir']),
                        'status' => 'aktif'
                    ]
                );

                $penempatanAktif = PenempatanSiswa::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $this->tahunAjaran->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                if (!$penempatanAktif) {

                    PenempatanSiswa::create([
                        'siswa_id' => $siswa->id,
                        'kelas_id' => $kelas->id,
                        'tahun_ajaran_id' => $this->tahunAjaran->id,
                        'tanggal_masuk' => now(),
                        'tanggal_keluar' => null
                    ]);

                } else {

                    if ($penempatanAktif->kelas_id != $kelas->id) {

                        $penempatanAktif->update([
                            'tanggal_keluar' => now()
                        ]);

                        PenempatanSiswa::create([
                            'siswa_id' => $siswa->id,
                            'kelas_id' => $kelas->id,
                            'tahun_ajaran_id' => $this->tahunAjaran->id,
                            'tanggal_masuk' => now(),
                            'tanggal_keluar' => null
                        ]);
                    }
                }
            }
        });
    }

    public function setTitle($title)
    {
        $this->sheetName = $title;
    }
}