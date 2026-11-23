<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanItem extends Model
{
    protected $fillable = ['penugasan_id', 'type'];

    public function penugasan()
    {
        return $this->belongsTo(Penugasan::class);
    }

    public function details()
    {
        return $this->hasMany(PenugasanDetail::class);
    }

    public function detail()
    {
        return $this->hasOne(PenugasanDetail::class);
    }

    public function menus()
    {
        return $this->hasMany(PenugasanMenu::class);
    }
}
