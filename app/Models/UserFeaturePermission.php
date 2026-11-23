<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserFeaturePermission extends Model
{
    protected $fillable = [
        'user_id',
        'feature_key',
        'allowed',
    ];

    protected function casts(): array
    {
        return [
            'allowed' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
