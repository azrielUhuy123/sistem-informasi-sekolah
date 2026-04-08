<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalGrup extends Model
{
    protected $table = 'jadwal_grup';

    protected $fillable = [
    'kelas_master_id',
    'tahun_ajaran_id',
    ];

    public function jadwal()
    {
        return $this->hasMany(Jadwal::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }

    public function kelasMaster()
    {
        return $this->belongsTo(KelasMaster::class);
    }

    public function rombel()
    {
        return $this->hasMany(JadwalRombel::class);
    }
}