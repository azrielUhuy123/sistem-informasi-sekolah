<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanJadwalEkskul extends Model
{
    protected $table = 'pengajuan_jadwal_ekskul';

    protected $guarded = [];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function ekskul()
    {
        return $this->belongsTo(
            Ekskul::class
        );
    }

    public function guru()
    {
        return $this->belongsTo(
            Guru::class
        );
    }

    public function hari()
    {
        return $this->belongsTo(
            HariSekolah::class,
            'hari_id'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(
            TahunAjaran::class,
            'tahun_ajaran_id'
        );
    }
}