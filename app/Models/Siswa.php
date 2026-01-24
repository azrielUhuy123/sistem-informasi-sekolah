<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siswa extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'siswa'; // nama tabel

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



    public function penempatan()
{
    return $this->hasMany(PenempatanSiswa::class);
}

// Ambil penempatan AKTIF (helper, opsional tapi berguna)
public function penempatanAktif()
{
    return $this->hasOne(PenempatanSiswa::class)
        ->where('status', 'aktif');
}

}
