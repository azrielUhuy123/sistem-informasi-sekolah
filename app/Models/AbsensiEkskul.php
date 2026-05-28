<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiEkskul extends Model
{
    protected $table = 'absensi_ekskul';

    protected $fillable = [

        'ekskul_id',
        'siswa_id',
        'tanggal',
        'status',
        'keterangan',
        'created_by'

    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function ekskul()
    {
        return $this->belongsTo(Ekskul::class);
    }
}