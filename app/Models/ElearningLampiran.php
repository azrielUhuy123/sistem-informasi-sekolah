<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElearningLampiran extends Model
{
    protected $table = 'elearning_lampiran';

    protected $fillable = [
        'elearning_id',
        'nama_file',
        'path'
    ];

    public function elearning()
    {
        return $this->belongsTo(Elearning::class);
    }
}