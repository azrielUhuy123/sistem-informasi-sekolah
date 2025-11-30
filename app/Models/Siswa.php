<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

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

    // Relasi: Siswa milik satu Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }
}
