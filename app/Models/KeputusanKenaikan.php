<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeputusanKenaikan extends Model
{
    protected $table = 'keputusan_kenaikan';

    protected $fillable = [

        'siswa_id',
        'kelas_id',
        'guru_id',
        'tahun_ajaran_id',
        'status',
        'catatan',
        'approved',

    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}