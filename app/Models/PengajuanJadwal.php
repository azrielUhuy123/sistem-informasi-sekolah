<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\JamPelajaran;

class PengajuanJadwal extends Model
{
    protected $table = 'pengajuan_jadwal';

    protected $fillable = [
        'guru_id',
        'kelas_id',
        'mapel_id',
        'hari_id',
        'jam_pelajaran_id',
        'tahun_ajaran_id',
        'status',
        'catatan',
    ];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function mapel()
    {
        return $this->belongsTo(
            MataPelajaran::class,
            'mapel_id'
        );
    }

    public function hari()
    {
        return $this->belongsTo(
            HariSekolah::class,
            'hari_id'
        );
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function jamPelajaran()
    {
        return $this->belongsTo(
            JamPelajaran::class,
            'jam_pelajaran_id'
        );
    }
}