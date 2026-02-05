<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    'kelas_id',
    'jenis_kelamin',
    'tanggal_lahir',
    'alamat',
    'nama_wali',
    'telepon_wali',
];


    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function getAngkatanIdAttribute()
{
    return $this->kelas?->kelasGrup?->angkatan_id;
}

public function kelasGrup()
{
    return $this->kelas?->kelasGrup();
}

}


