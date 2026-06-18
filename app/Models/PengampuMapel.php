<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengampuMapel extends Model
{
    protected $table = 'pengampu_mapel';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mata_pelajaran_id',
        'tahun_ajaran_id',
    ];

    public function guru()
    {
        return $this->belongsTo(
            Guru::class,
            'guru_id'
        );
    }

    public function kelas()
    {
        return $this->belongsTo(
            Kelas::class,
            'kelas_id'
        );
    }

    public function forums()
    {
        return $this->hasMany(
            Elearning::class,
            'pengampu_id'
        );
    }

    public function mapel()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'mata_pelajaran_id'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}