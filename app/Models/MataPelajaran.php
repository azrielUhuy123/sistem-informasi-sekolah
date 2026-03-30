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

    public function guru()
    {
        return $this->hasMany(Guru::class, 'mapel_id');
    }
}
