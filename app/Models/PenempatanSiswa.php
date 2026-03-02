<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TahunAjaran;

class PenempatanSiswa extends Model
{
    protected $table = 'penempatan_siswa';

    protected $fillable = [
        'siswa_id',
        'kelas_id',
        'tahun_ajaran_id',
        'tanggal_masuk',
        'tanggal_keluar',
    ];

    // Relasi
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
    public function scopeTahunAktif($query)
    {
        $tahunAktif = TahunAjaran::aktif();

        if (!$tahunAktif) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where('tahun_ajaran_id', $tahunAktif->id);
    }
}
