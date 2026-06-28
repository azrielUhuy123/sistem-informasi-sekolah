<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ekskul extends Model
{
    protected $fillable = [

        'nama',
        'guru_id',
        'deskripsi',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'kuota',
        'status'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI GURU PEMBINA
    |--------------------------------------------------------------------------
    */

    public function guru()
    {
        return $this->belongsTo(
            Guru::class
        );
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI ANGGOTA
    |--------------------------------------------------------------------------
    */

    public function anggota()
    {
        return $this->belongsToMany(
            Siswa::class,
            'ekskul_siswa'
        );
    }
    
    public function pembina()
    {
        return $this->hasOne(
            PembinaEkskul::class
        );
    }
}