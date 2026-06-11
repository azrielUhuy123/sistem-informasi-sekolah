<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PpdbPendaftar extends Model
{
    protected $table = 'ppdb_pendaftar';

    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(
            Siswa::class,
            'siswa_id'
        );
    }

    public function orangtua()
    {
        return $this->hasMany(
            PpdbOrangtua::class,
            'ppdb_pendaftar_id'
        );
    }

    public function ayah()
    {
        return $this->hasOne(
            PpdbOrangtua::class,
            'ppdb_pendaftar_id'
        )->where('jenis', 'ayah');
    }

    public function ibu()
    {
        return $this->hasOne(
            PpdbOrangtua::class,
            'ppdb_pendaftar_id'
        )->where('jenis', 'ibu');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}