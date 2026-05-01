<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'semester',
        'status'
    ];

    // =========================
    // RELATION
    // =========================
    public function penempatanSiswa()
    {
        return $this->hasMany(PenempatanSiswa::class);
    }

    // =========================
    // QUERY SCOPES
    // =========================
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'selesai');
    }

    // =========================
    // STATIC HELPERS
    // =========================

    /**
     * Ambil tahun ajaran yang sedang aktif
     */
    public static function current()
    {
        return self::where('status', 'aktif')->first();
    }

    /**
     * Alias biar fleksibel dipakai di controller lama
     */
    public static function aktif()
    {
        return self::current();
    }

    /**
     * Ambil tahun ajaran terakhir yang sudah selesai
     * (dipakai untuk naik kelas)
     */
    public static function lastSelesai()
    {
        return self::where('status', 'selesai')
            ->latest('id')
            ->first();
    }

    /**
     * Cek apakah ada tahun aktif
     */
    public static function hasActive()
    {
        return self::where('status', 'aktif')->exists();
    }
}