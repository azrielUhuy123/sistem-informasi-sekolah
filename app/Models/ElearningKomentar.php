<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElearningKomentar extends Model
{
    protected $table = 'elearning_komentar';

    protected $fillable = [
        'elearning_id',
        'user_id',
        'isi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function elearning()
    {
        return $this->belongsTo(Elearning::class);
    }
}