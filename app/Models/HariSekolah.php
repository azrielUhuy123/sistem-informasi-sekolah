<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HariSekolah extends Model
{
    protected $table = 'hari_sekolah';

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'urutan',
    ];
}