<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $fillable = [
        'user_id',
        'nama_surat',
        'kategori_surat',
        'perihal',
        'pengirim',
        'tujuan_surat',
        'file_surat',
        'status',
        'validated_by',
        'validated_at'
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI PENGAJU
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | RELASI VALIDATOR
    |--------------------------------------------------------------------------
    */
    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}