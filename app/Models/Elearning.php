<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elearning extends Model
{
    protected $fillable = [
        'guru_id',
        'pengampu_id',
        'wali_kelas_id',
        'kelas_id',
        'mapel_id',
        'judul',
        'konten',
        'deadline'
    ];

    protected $casts = [
        'deadline' => 'datetime'
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(MataPelajaran::class,'mapel_id');
    }

    public function lampiran()
    {
        return $this->hasMany(ElearningLampiran::class);
    }

    public function komentar()
    {
        return $this->hasMany(
            ElearningKomentar::class
        );
    }

    public function pengampu()
    {
        return $this->belongsTo(
            PengampuMapel::class,
            'pengampu_id'
        );
    }

    public function waliKelas()
    {
        return $this->belongsTo(
            WaliKelas::class,
            'wali_kelas_id'
        );
    }
}
