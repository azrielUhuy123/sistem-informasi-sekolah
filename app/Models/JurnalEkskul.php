<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JurnalEkskul extends Model
{
    protected $table = 'jurnal_ekskul';

    protected $fillable = [

        'ekskul_id',
        'tanggal',
        'materi',
        'kegiatan',
        'catatan',
        'created_by'

    ];

    public function ekskul()
    {
        return $this->belongsTo(
            Ekskul::class
        );
    }
}