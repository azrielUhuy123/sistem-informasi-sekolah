<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\MataPelajaran;

class Guru extends Model
{
    use SoftDeletes;

    protected $table = 'guru';

    protected $fillable = [
        'user_id',
        'data_kepegawaian_id',
    ];

    /**
     * Relasi ke Mata Pelajaran
     */

    public function mapels()
    {
        return $this->belongsToMany(
            MataPelajaran::class,
            'guru_mapel',
            'guru_id',
            'mapel_id'
        );
    }

    public function waliKelas()
    {
        return $this->hasOne(WaliKelas::class);
    }

    public function pengampuMapel()
    {
        return $this->hasMany(PengampuMapel::class);
    }
    public function pembinaEkskul()
    {
        return $this->hasMany(
            PembinaEkskul::class
        );
    }
    public function pegawai()
    {
        return $this->belongsTo(
            DataKepegawaian::class,
            'data_kepegawaian_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class
        );
    }
    public function getNamaAttribute()
    {
        return $this->pegawai?->nama;
    }

    public function getNipAttribute()
    {
        return $this->pegawai?->nip;
    }

    public function getJenisKelaminAttribute()
    {
        return $this->pegawai?->jenis_kelamin;
    }

    public function getFotoAttribute()
    {
        return $this->pegawai?->foto;
    }
}
