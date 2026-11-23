<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanDetail extends Model
{
    protected $fillable = [
        'penugasan_item_id',
        'kelas_id',
        'mata_pelajaran_id',
        'ekskul_id',
    ];

    public function item()
    {
        return $this->belongsTo(PenugasanItem::class, 'penugasan_item_id');
    }
}
