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
    ];

    // =========================
    // RELATIONS
    // =========================

    public function kelasGrup()
    {
        return $this->belongsTo(KelasGrup::class, 'kelas_grup_id')
            ->with(['kelasMaster', 'angkatan']);
    }

    public function penempatan()
    {
        return $this->hasMany(PenempatanSiswa::class);
    }

    public function waliKelas()
    {
        return $this->hasOne(\App\Models\WaliKelas::class, 'kelas_id');
    }

    // =========================
    // ACCESSORS
    // =========================

    public function getAngkatanIdAttribute()
    {
        return $this->kelasGrup?->angkatan_id;
    }

    public function getTingkatAttribute()
    {
        return $this->kelasGrup?->kelasMaster?->tingkat;
    }

    // =========================
    // QUERY HELPERS
    // =========================

    /**
     * Ambil siswa aktif di kelas ini (tahun aktif default)
     */
    public function siswaAktif($tahunId = null)
    {
        $tahunId = $tahunId ?? \App\Models\TahunAjaran::current()?->id;

        return $this->hasMany(PenempatanSiswa::class)
            ->where('tahun_ajaran_id', $tahunId)
            ->whereNull('tanggal_keluar')
            ->with('siswa');
    }

    /**
     * Scope: filter berdasarkan tingkat
     */
    public function scopeTingkat($query, $tingkat)
    {
        return $query->whereHas('kelasGrup.kelasMaster', function ($q) use ($tingkat) {
            $q->where('tingkat', $tingkat);
        });
    }

    public function pengajuanJadwal()
    {
        return $this->hasMany(PengajuanJadwal::class);
    }
}