<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerkembanganSiswa extends Model
{
    protected $table = 'perkembangan_siswa';

    protected $fillable = [

        'siswa_id',
        'kelas_id',
        'guru_id',
        'tahun_ajaran_id',
        'semester',
        'karakter',
        'sosial',
        'disiplin',
        'kebersihan',
        'catatan',

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

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}