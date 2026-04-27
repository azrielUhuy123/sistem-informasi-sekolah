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

    public function penempatanSiswa()
    {
        return $this->hasMany(PenempatanSiswa::class);
    }

    public static function aktif()
    {
        return static::where('status', 'aktif')->first();
    }

    public static function transisi()
    {
        return static::where('status', 'transisi')->first();
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

}
