<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbOrangtua extends Model
{
    protected $table = 'ppdb_orangtua';

    protected $guarded = [];

    public function pendaftar()
    {
        return $this->belongsTo(PpdbPendaftar::class);
    }
}