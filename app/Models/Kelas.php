<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelas';

    protected $fillable = [
        'kelas_master_id',
        'tahun_ajaran_id',
        'nama_kelas',
    ];

    /* ================= RELASI ================= */

    public function kelasMaster()
    {
        return $this->belongsTo(KelasMaster::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class);
    }
}
