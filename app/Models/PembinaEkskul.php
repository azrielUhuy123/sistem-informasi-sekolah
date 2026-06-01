<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembinaEkskul extends Model
{
    protected $table = 'pembina_ekskul';

    protected $fillable = [

        'guru_id',
        'ekskul_id',
        'tahun_ajaran_id',

    ];

    /*
    |--------------------------------------------------------------------------
    | RELATION
    |--------------------------------------------------------------------------
    */

    public function guru()
    {
        return $this->belongsTo(
            Guru::class
        );
    }

    public function ekskul()
    {
        return $this->belongsTo(
            Ekskul::class
        );
    }
}