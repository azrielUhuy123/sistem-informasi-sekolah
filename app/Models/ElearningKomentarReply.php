<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElearningKomentarReply extends Model
{
    protected $fillable = [
        'komentar_id',
        'user_id',
        'isi',
        'reply_to_name',
        'reply_preview'
    ];

    public function komentar()
    {
        return $this->belongsTo(
            ElearningKomentar::class,
            'komentar_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function lampiran()
    {
        return $this->hasMany(
            ElearningKomentarLampiran::class,
            'reply_id'
        );
    }

    public function replyTo()
    {
        return $this->belongsTo(
            ElearningKomentarReply::class,
            'reply_to_reply_id'
        );
    }
}
