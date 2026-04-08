<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalRombel extends Model
{
    protected $table = 'jadwal_rombel';

    protected $fillable = [
        'jadwal_grup_id',
        'nama_rombel'
    ];

    public function grup()
    {
        return $this->belongsTo(JadwalGrup::class, 'jadwal_grup_id');
    }
}
