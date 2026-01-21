<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeuanganPemasukan extends Model
{
    protected $table = 'keuangan_pemasukan';

    protected $fillable = [
        'tanggal',
        'sumber_dana',
        'periode_anggaran',
        'nominal',
        'keterangan',
        'dokumen',
    ];
}
