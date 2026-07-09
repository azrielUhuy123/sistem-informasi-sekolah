<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Siswa;
use App\Models\Ekskul;

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

    public function siswa()
{
    return $this->belongsTo(
        Siswa::class
    );
}

public function ekskul()
{
    return $this->belongsTo(
        Ekskul::class
    );
}
}
