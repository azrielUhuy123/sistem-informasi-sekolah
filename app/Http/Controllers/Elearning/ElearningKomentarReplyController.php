<?php

namespace App\Http\Controllers\Elearning;

use App\Http\Controllers\Controller;
use App\Models\ElearningKomentarReply;
use App\Models\ElearningKomentar;
use App\Models\ElearningKomentarLampiran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ElearningKomentarReplyController extends Controller
{
    public function store(
        Request $request,
        ElearningKomentar $komentar
    )
    {
        $user = Auth::user();
        $siswa = $user->siswa;
        $kelasAktif = $siswa?->penempatanAktif?->kelas;
        $forum = $komentar->elearning;

        if (!$kelasAktif || !$forum || $forum->kelas_id != $kelasAktif->id) {
            abort(403);
        }

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

            'komentar_id' => $komentar->id,

            'reply_to_reply_id' =>
                $request->reply_to_reply_id,

            'user_id' => $user->id,

            'isi' => $request->isi,

            'reply_to_name' =>
                $request->reply_to_name,

            'reply_preview' =>
                $request->reply_preview

        ]);

        /*
        |--------------------------------------------------------------------------
        | Upload Lampiran
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('lampiran')) {

            foreach ($request->file('lampiran') as $file) {

                $path = $file->store(
                    'elearning/reply',
                    'public'
                );

                ElearningKomentarLampiran::create([

                    'reply_id' => $reply->id,

                    'nama_file' =>
                        $file->getClientOriginalName(),

                    'path' => $path

                ]);
            }
        }

        return back()->with(
            'success',
            'Balasan berhasil dikirim.'
        );
    }
}