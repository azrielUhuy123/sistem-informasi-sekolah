<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnggotaEkskul extends Model
{
    protected $fillable = [

        'ekskul_id',
        'siswa_id',
        'tahun_ajaran_id',
        'status'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI EKSKUL
    |--------------------------------------------------------------------------
    */

    public function ekskul()
    {
        return $this->belongsTo(
            Ekskul::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI SISWA
    |--------------------------------------------------------------------------
    */

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class
        );
    }
}