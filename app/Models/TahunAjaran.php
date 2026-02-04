<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'semester',
        'aktif'
    ];

    public function penempatanSiswa()
{
    return $this->hasMany(PenempatanSiswa::class);
}
public static function aktif()
{
    return static::where('aktif', true)->first();
}


}
