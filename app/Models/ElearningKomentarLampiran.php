<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElearningKomentarLampiran extends Model
{
    protected $table = 'elearning_komentar_lampiran';

    protected $fillable = [
        'komentar_id',
        'reply_id',
        'nama_file',
        'path'
    ];

    public function komentar()
    {
        return $this->belongsTo(
            ElearningKomentar::class,
            'komentar_id'
        );
    }

    public function reply()
    {
        return $this->belongsTo(
            ElearningKomentarReply::class,
            'reply_id'
        );
    }
}