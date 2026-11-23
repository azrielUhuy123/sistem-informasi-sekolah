<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanMenu extends Model
{
    protected $fillable = ['penugasan_item_id', 'menu_key'];

    public function item()
    {
        return $this->belongsTo(PenugasanItem::class, 'penugasan_item_id');
    }
}
