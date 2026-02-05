<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Angkatan;
use App\Models\Kelas;
use App\Models\KelasMaster;


class KelasGrup extends Model
{
    protected $table = 'kelas_grup';

    protected $fillable = [
        'angkatan_id',
        'kelas_master_id',
    ];

    /**
     * Relasi ke Angkatan
     */
    public function angkatan()
    {
        return $this->belongsTo(Angkatan::class, 'angkatan_id');
    }

    /**
     * Relasi ke Tingkat (kelas_master)
     */
    public function kelasMaster()
    {
        return $this->belongsTo(KelasMaster::class, 'kelas_master_id');
    }

    /**
     * Rombel dalam grup ini
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'kelas_grup_id');
    }
}
