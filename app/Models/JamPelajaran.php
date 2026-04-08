<?php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    protected $table = 'jam_pelajaran';

    public $timestamps = false;

    protected $fillable = [
        'jam_ke',
        'mulai',
        'selesai',
    ];
}
