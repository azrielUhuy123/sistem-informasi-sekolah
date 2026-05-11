<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NomorSurat extends Model
{
    protected $fillable = [

        'user_id',
        'pengirim',
        'nomor_surat',
        'status'

    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI USER
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}