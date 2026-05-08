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
        'nama',
        'nip',
        'jenis_kelamin',
        'mapel_id',
        'email',
        'telepon',
        'alamat',
        'foto',
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
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
