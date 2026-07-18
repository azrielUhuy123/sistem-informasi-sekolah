<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KeputusanKenaikan extends Model
{
    protected $table = 'keputusan_kenaikan';

    protected $fillable = [

        'siswa_id',
        'kelas_id',
        'guru_id',
        'tahun_ajaran_id',
        'status',
        'catatan',
        'approval_status',
        'approved_by',
        'approved_at',
        'submitted_at',
        'catatan_kepsek',

    ];

    protected $casts = [

        'approved_at' => 'datetime',
        'submitted_at' => 'datetime',

    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function isDraft()
    {
        return $this->approval_status === 'draft';
    }

    public function isPending()
    {
        return $this->approval_status === 'pending';
    }

    public function isRejected()
    {
        return $this->approval_status === 'rejected';
    }

    public function isApproved()
    {
        return $this->approval_status === 'approved';
    }
}