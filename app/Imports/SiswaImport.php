<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Wajib ada NIS
        if (empty($row['nis'])) {
            return null;
        }

        // Ambil & normalisasi jenis kelamin
        $jkRaw = strtolower(trim($row['jenis_kelamin (l/p)'] ?? ''));

$jk = match ($jkRaw) {
    'l', 'laki-laki', 'laki laki' => 'L',
    'p', 'perempuan'              => 'P',
    default                       => 'L', // DEFAULT AGAR TIDAK DI-SKIP
};


        // Skip baris jika jenis kelamin tidak valid
        if (!$jk) {
            return null;
        }

        return Siswa::updateOrCreate(
            ['nis' => $row['nis']],
            [
                'nama'          => $row['nama'] ?? null,
                'nisn'          => $row['nisn'] ?? null,
                'jenis_kelamin' => $jk,
                'tanggal_lahir' => $row['tanggal_lahir (yyyy-mm-dd)'] ?? null,
                'alamat'        => $row['alamat'] ?? null,
                'nama_wali'     => $row['nama_wali'] ?? null,
                'telepon_wali'  => $row['telepon_wali'] ?? null,
            ]
        );
    }
}
