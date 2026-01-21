<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\KelasMaster;
use App\Models\TahunAjaran;


class KelasGrup extends Model
{
    protected $table = 'kelas_grup';

    protected $fillable = [
        'kelas_master_id',
        'tahun_ajaran_id',
    ];

    // ✅ RELASI KE KELAS MASTER
    public function kelasMaster()
    {
        return $this->belongsTo(KelasMaster::class, 'kelas_master_id');
    }

    // ✅ RELASI KE TAHUN AJARAN
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
