<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kelas extends Model
{
    use SoftDeletes;

    protected $table = 'kelas';

    protected $fillable = [
        'kelas_grup_id',
        'nama_kelas',
        'tingkatan',
        'tahun_ajaran_id',
    ];

    public function kelasGrup()
{
    return $this->belongsTo(KelasGrup::class, 'kelas_grup_id');
}

    public function siswa()
    {
        return $this->hasManyThrough(
            Siswa::class,
            PenempatanSiswa::class,
            'kelas_id', // FK di penempatan
            'id',       // PK di siswa
            'id',       // PK di kelas
            'siswa_id'  // FK ke siswa
        )->whereNull('penempatan_siswa.tanggal_keluar');
    }

    public function getAngkatanIdAttribute()
{
    return $this->kelasGrup?->angkatan_id;
}

public function getTingkatAttribute()
{
    return $this->kelasGrup?->kelasMaster?->tingkat;
}
public function penempatan()
{
    return $this->hasMany(PenempatanSiswa::class);
}

public function siswaAktif()
{
    return $this->hasMany(PenempatanSiswa::class)
        ->whereNull('tanggal_keluar');
}
public function waliKelas()
{
    return $this->hasOne(\App\Models\WaliKelas::class, 'kelas_id');
}

}
