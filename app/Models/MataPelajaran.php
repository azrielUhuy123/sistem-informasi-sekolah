<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode',
        'nama_mapel',
        'kkm',
    ];

    public function gurus()
    {
        return $this->hasMany(\App\Models\Guru::class, 'mapel_id');
    }

    public function jadwals()
    {
        return $this->hasMany(\App\Models\Jadwal::class, 'mapel_id');
    }
}
