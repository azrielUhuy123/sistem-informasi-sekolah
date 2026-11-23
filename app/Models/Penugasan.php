<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penugasan extends Model
{
    protected $fillable = [
        'data_kepegawaian_id',
        'assigned_by',
        'status',
        'published_at',
        'revoked_at',
        'revoked_by',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function pegawai()
    {
        return $this->belongsTo(DataKepegawaian::class, 'data_kepegawaian_id');
    }

    public function items()
    {
        return $this->hasMany(PenugasanItem::class);
    }

    public function assignedBy()
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    public function revokedBy()
    {
        return $this->belongsTo(User::class, 'revoked_by');
    }
}
