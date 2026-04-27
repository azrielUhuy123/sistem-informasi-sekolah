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
        $tahunAktif = \App\Models\TahunAjaran::aktif();

        return $this->hasManyThrough(
            Siswa::class,
            PenempatanSiswa::class,
            'kelas_id',
            'id',
            'id',
            'siswa_id'
        )
        ->where('penempatan_siswa.tahun_ajaran_id', $tahunAktif?->id)
        ->whereNull('penempatan_siswa.tanggal_keluar');
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
        $tahunAktif = \App\Models\TahunAjaran::aktif();

        return $this->hasMany(PenempatanSiswa::class)
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->whereNull('tanggal_keluar');
    }
    public function waliKelas()
    {
        return $this->hasOne(\App\Models\WaliKelas::class, 'kelas_id');
    }

}
