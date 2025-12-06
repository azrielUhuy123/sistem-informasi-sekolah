<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DataKepegawaian extends Model
{
    protected $table = 'data_kepegawaian';

    protected $fillable = [
        'nama',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'jabatan',
        'status_kerja',
        'pendidikan',
        'riwayat_mengajar',
        'absensi',
        'penilaian_kinerja',
        'pengalaman_tahun',
        'gaji',
        'tunjangan',
        'pelatihan',
    ];
}
