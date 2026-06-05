<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Berita;

class GatewayController extends Controller
{
    public function berita()
    {
        return Berita::where('is_publish', true)
            ->latest()
            ->take(6)
            ->get([
                'id',
                'judul',
                'slug',
                'ringkasan',
                'gambar',
                'created_at'
            ]);
    }

    public function detailBerita($slug)
    {
        return Berita::where(
            'slug',
            $slug
        )->firstOrFail();
    }
}