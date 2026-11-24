<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PegawaiChangeDecision extends Model
{
    protected $fillable = [
        'pegawai_change_item_id',
        'actor_id',
        'action',
        'reason',
        'acted_at',
    ];

    protected function casts(): array
    {
        return ['acted_at' => 'datetime'];
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(PegawaiChangeItem::class, 'pegawai_change_item_id');
    }

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }
}
