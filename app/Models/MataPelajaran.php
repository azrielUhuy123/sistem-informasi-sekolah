<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    // WAJIB sesuai database
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'kkm'
    ];

    public function gurus()
    {
        return $this->belongsToMany(
            Guru::class,
            'guru_mapel',
            'mapel_id',
            'guru_id'
        );
    }
}
