<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EkskulSiswa extends Model
{
    protected $table = 'ekskul_siswa';

    protected $fillable = [

        'ekskul_id',
        'siswa_id',
        'tahun_ajaran_id',
        'status'

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