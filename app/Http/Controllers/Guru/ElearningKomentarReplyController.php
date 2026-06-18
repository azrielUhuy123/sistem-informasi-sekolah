<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ElearningKomentarReply;
use App\Models\ElearningKomentar;
use App\Models\ElearningKomentarLampiran;
use App\Models\User;
use App\Models\Elearning;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ElearningKomentarReplyController extends Controller
{
    public function store(
        Request $request,
        $komentar
    )
    {
        $request->validate([
            'isi' => 'nullable|string',
            'lampiran.*' => 'nullable|file|max:20480'
        ]);

        if (
            blank($request->isi)
            && !$request->hasFile('lampiran')
        ) {
            return back()->withErrors([
                'isi' => 'Balasan atau lampiran wajib diisi.'
            ]);
        }

        $reply = ElearningKomentarReply::create([

            'komentar_id' => $komentar,

            'reply_to_reply_id' =>
                $request->reply_to_reply_id,

            'user_id' => auth()->id(),

            'isi' => $request->isi,

            'reply_to_name' =>
                $request->reply_to_name,

            'reply_preview' =>
                $request->reply_preview

        ]);

        return back();
    }
}
