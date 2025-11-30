<?php

namespace App\Imports;

use App\Models\Siswa;
use Maatwebsite\Excel\Concerns\ToModel;

class SiswaImport implements ToModel
{
    public function model(array $row)
    {
        return new Siswa([
            'nis' => $row[0],
            'nisn' => $row[1],
            'nama' => $row[2],
            'kelas_id' => $row[3],
            'jenis_kelamin' => $row[4],
            'tanggal_lahir' => $row[5],
            'alamat' => $row[6],
            'nama_wali' => $row[7],
            'telepon_wali' => $row[8],
        ]);
    }
}
