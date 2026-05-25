<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AbsensiSiswa extends Model
{
    protected $table = 'absensi_siswa';

    protected $fillable = [

        'jadwal_id',
        'siswa_id',
        'kelas_id',
        'tahun_ajaran_id',
        'created_by',
        'tanggal',
        'status',
        'keterangan',

    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }
    public function jadwal()
    {
        return $this->belongsTo(
            PengajuanJadwal::class,
            'jadwal_id'
        );
    }
}