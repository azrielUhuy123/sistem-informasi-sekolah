<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PegawaiChangeRequest extends Model
{
    protected $fillable = [
        'data_kepegawaian_id',
        'submitted_by',
        'submitted_by_name',
        'status',
    ];

    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(DataKepegawaian::class, 'data_kepegawaian_id');
    }

    public function submittedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PegawaiChangeItem::class, 'pegawai_change_request_id');
    }
}
