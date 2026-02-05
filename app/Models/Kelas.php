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

    public function kelasGrup()
{
    return $this->belongsTo(KelasGrup::class, 'kelas_grup_id');
}

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'kelas_id');
    }

    public function getAngkatanIdAttribute()
{
    return $this->kelasGrup?->angkatan_id;
}

public function getTingkatAttribute()
{
    return $this->kelasGrup?->kelasMaster?->tingkat;
}

}
