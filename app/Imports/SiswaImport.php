<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\DB;

class SiswaImport implements ToCollection, WithHeadingRow
{
    protected int $angkatan;
    protected int $kelasId;

    public function __construct(int $angkatan, int $kelasId)
    {
        $this->angkatan = $angkatan;
        $this->kelasId  = $kelasId;
    }

    public function collection(Collection $rows)
    {
        DB::transaction(function () use ($rows) {

            foreach ($rows as $row) {

                // SKIP BARIS KOSONG
                if (empty($row['nama']) || empty($row['nis'])) {
                    continue;
                }

                // ===============================
                // INSERT SISWA
                // ===============================
                $siswa = Siswa::firstOrCreate(
                    ['nis' => $row['nis']],
                    [
                        'angkatan'      => $this->angkatan,
                        'nama'          => $row['nama'],
                        'nisn'          => $row['nisn'] ?? null,
                        'jenis_kelamin' => strtoupper(substr($row['jenis_kelamin'] ?? 'L', 0, 1)),
                        'tanggal_lahir' => $row['tanggal_lahir'] ?? null,
                        'alamat'        => $row['alamat'] ?? null,
                        'nama_wali'     => $row['nama_wali'] ?? null,
                        'telepon_wali'  => $row['telepon_wali'] ?? null,
                    ]
                );

                // ===============================
                // CEK SUDAH ADA PENEMPATAN AKTIF?
                // ===============================
                $sudahAda = PenempatanSiswa::where('siswa_id', $siswa->id)
                    ->where('status', 'aktif')
                    ->exists();

                if ($sudahAda) {
                    continue;
                }

                // ===============================
                // BUAT PENEMPATAN BARU
                // ===============================  
                PenempatanSiswa::create([
                    'siswa_id'      => $siswa->id,
                    'kelas_id'      => $this->kelasId,
                    'tahun_ajaran_id' => session('tahun_ajaran_aktif_id'), // atau helper kamu
                    'status'        => 'aktif',
                ]);
            }
        });
    }
}
