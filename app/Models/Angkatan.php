<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    protected $table = 'angkatan';

    protected $fillable = [
        'tahun',
        'keterangan'
    ];

    public function kelasGrup()
    {
        return $this->hasMany(KelasGrup::class);
    }
}