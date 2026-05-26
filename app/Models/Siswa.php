<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use SoftDeletes;

    protected $table = 'siswa';

    protected $fillable = [
        'nama',
        'nis',
        'nisn',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'nama_wali',
        'telepon_wali',
    ];

    public function penempatan()
    {
        return $this->hasMany(PenempatanSiswa::class)
            ->with(['kelas.kelasGrup.angkatan', 'tahunAjaran'])
            ->orderByDesc('tanggal_masuk');
    }

    public function penempatanAktif()
    {
        return $this->hasOne(PenempatanSiswa::class)
            ->whereNull('tanggal_keluar')
            ->whereHas('tahunAjaran', function ($q) {
                $q->where('status', 'aktif');
            })
            ->with(['kelas.kelasGrup.angkatan', 'tahunAjaran']);
    }

    public function getStatusTahunAktifAttribute()
    {
        $tahun = \App\Models\TahunAjaran::current();

        if (!$tahun) {
            return '-';
        }

        $penempatan = $this->penempatan()
            ->where('tahun_ajaran_id', $tahun->id)
            ->whereNull('tanggal_keluar')
            ->first();

        return $penempatan ? 'Aktif' : 'Belum ditempatkan';
    }

    public function user()
    {
        return $this->belongsTo(
            \App\Models\User::class,
            'user_id'
        );
    }

    
    
}