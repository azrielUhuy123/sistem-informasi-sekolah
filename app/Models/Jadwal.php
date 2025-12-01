<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = 'jadwal';

    protected $fillable = [
        'kelas_id',
        'guru_id',
        'mapel_id',
        'hari',
        'jam_mulai',
        'jam_selesai',
    ];

    public function mapel()
    {
        return $this->belongsTo(\App\Models\MataPelajaran::class, 'mapel_id');
    }

    public function guru()
    {
        return $this->belongsTo(\App\Models\Guru::class, 'guru_id');
    }

    public function kelas()
    {
        return $this->belongsTo(\App\Models\Kelas::class, 'kelas_id');
    }
}
