<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PegawaiChangeItem extends Model
{
    protected $fillable = [
        'pegawai_change_request_id',
        'field',
        'old_value',
        'new_value',
        'evidence_path',
        'status',
        'rejection_reason',
        'verified_by',
        'verified_at',
        'resubmitted_from_id',
    ];

    protected function casts(): array
    {
        return ['verified_at' => 'datetime'];
    }

    public function changeRequest(): BelongsTo
    {
        return $this->belongsTo(PegawaiChangeRequest::class, 'pegawai_change_request_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function resubmittedFrom(): BelongsTo
    {
        return $this->belongsTo(self::class, 'resubmitted_from_id');
    }

    public function resubmissions(): HasMany
    {
        return $this->hasMany(self::class, 'resubmitted_from_id');
    }

    public function decisions(): HasMany
    {
        return $this->hasMany(PegawaiChangeDecision::class, 'pegawai_change_item_id');
    }
}
