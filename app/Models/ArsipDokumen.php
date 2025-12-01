<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArsipDokumen extends Model
{
    protected $table = 'arsip_dokumen';

    protected $fillable = [
        'nama_surat',
        'nomor_surat',
        'tanggal_dibuat',
        'kategori',
        'file_path',
        'keterangan',
    ];
}
