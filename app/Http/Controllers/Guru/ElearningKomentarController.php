<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Elearning;
use App\Models\ElearningKomentar;
use Illuminate\Http\Request;

class ElearningKomentarController extends Controller
{
    public function store(
        Request $request,
        $elearning
    )
    {
        $request->validate([
            'isi' => 'required|string'
        ]);

        ElearningKomentar::create([

            'elearning_id' => $elearning,

            'user_id' => auth()->id(),

            'isi' => $request->isi

        ]);

        return back()
            ->with(
                'success',
                'Komentar berhasil ditambahkan'
            );
    }
}