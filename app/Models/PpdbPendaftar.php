<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbPendaftar extends Model
{
    protected $table = 'ppdb_pendaftar';

    protected $guarded = [];

    public function ayah()
    {
        return $this->hasOne(PpdbOrangtua::class)
            ->where('jenis', 'ayah');
    }

    public function ibu()
    {
        return $this->hasOne(PpdbOrangtua::class)
            ->where('jenis', 'ibu');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}