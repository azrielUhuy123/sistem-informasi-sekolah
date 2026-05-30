<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanPembinaanEkskul extends Model
{
    protected $table =
        'catatan_pembinaan_ekskul';

    protected $fillable = [

        'ekskul_id',
        'siswa_id',
        'tahun_ajaran_id',

        'kehadiran',
        'partisipasi',
        'perkembangan',
        'catatan'

    ];
}
