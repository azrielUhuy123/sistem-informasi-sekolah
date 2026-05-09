<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    protected $table = 'dokumen';

    protected $fillable = [
        'nama_surat',
        'kategori_surat',
        'jenis_dokumen',
        'file'
    ];
}