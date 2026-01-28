<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Angkatan extends Model
{
    protected $table = 'angkatan';

    protected $fillable = [
        'tahun',
        'keterangan',
        'aktif',
    ];

    protected $casts = [
        'tahun' => 'integer',
        'aktif' => 'boolean',
    ];
}
