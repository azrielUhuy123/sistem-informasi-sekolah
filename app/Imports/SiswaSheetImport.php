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
        $this->tahunAjaran = TahunAjaran::aktif();

        if (!$this->tahunAjaran) {
            throw new \Exception("Tidak ada tahun ajaran aktif.");
        }

        if ($this->tahunAjaran->status !== 'aktif') {
            throw new \Exception("Import hanya boleh saat tahun ajaran AKTIF (awal tahun).");
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
                
                if (!is_numeric($nisn) || strlen($nisn) != 10) {
                    throw new \Exception("NISN harus 10 digit angka pada NIS {$nis}");
                }

                $jk = $this->normalizeJK($row['jenis_kelamin']);

                $tanggalLahir = null;

                if (!empty($row['tanggal_lahir'])) {
                    $raw = $row['tanggal_lahir'];

                    if (is_numeric($raw)) {
                        $tanggalLahir = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($raw)
                            ->format('Y-m-d');
                    } else {
                        try {
                            $tanggalLahir = \Carbon\Carbon::createFromFormat('m/d/Y', $raw)
                                ->format('Y-m-d');
                        } catch (\Exception $e) {
                            try {
                                $tanggalLahir = \Carbon\Carbon::createFromFormat('Y-m-d', $raw)
                                    ->format('Y-m-d');
                            } catch (\Exception $e) {
                                throw new \Exception("Tanggal tidak valid: {$raw}");
                            }
                        }
                    }
                }


                $siswa = Siswa::firstOrCreate(
                    ['nis' => $nis],
                    [
                        'nisn' => $nisn,
                        'nama' => $row['nama'],
                        'jenis_kelamin' => $jk,
                        'tanggal_lahir' => $tanggalLahir,
                        'status' => 'aktif'
                    ]
                );

                $penempatanAktif = PenempatanSiswa::where('siswa_id', $siswa->id)
                    ->where('tahun_ajaran_id', $this->tahunAjaran->id)
                    ->whereNull('tanggal_keluar')
                    ->first();

                if ($penempatanAktif && $penempatanAktif->kelas_id == $kelas->id) {
                    continue;
                }

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
    private function normalizeJK($value)
    {
        $v = strtoupper(trim($value));

        if (in_array($v, ['L', 'LAKI-LAKI'])) return 'L';
        if (in_array($v, ['P', 'PEREMPUAN'])) return 'P';

        throw new \Exception("Jenis kelamin tidak valid: {$value}");
    }
}