<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas'; // nama tabel

    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'tahun_ajaran',
    ];

    public function siswa()
    {
        return $this->hasMany(\App\Models\Siswa::class, 'kelas_id');
    }

}
