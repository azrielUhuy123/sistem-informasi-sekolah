<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\PembinaEkskul;

class GuruModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        /*
        |--------------------------------------------------------------------------
        | DEFAULT MODE
        |--------------------------------------------------------------------------
        */

        if (! session()->has('guru_mode')) {

            session([
                'guru_mode' => 'akademik'
            ]);

        }

        /*
        |--------------------------------------------------------------------------
        | CEK PEMBINA EKSKUL
        |--------------------------------------------------------------------------
        */

        $guru = auth()->user()?->guru;

        if ($guru) {

            $isPembina = PembinaEkskul::where(
                    'guru_id',
                    $guru->id
                )
                ->where(
                    'tahun_ajaran_id',
                    current_tahun_id()
                )
                ->exists();

            /*
            |--------------------------------------------------------------------------
            | JIKA BUKAN PEMBINA TAPI MODE MASIH EKSKUL
            |--------------------------------------------------------------------------
            */

            if (
                session('guru_mode') === 'ekskul'
                && ! $isPembina
            ) {

                session([
                    'guru_mode' => 'akademik'
                ]);

            }

        }

        return $next($request);
    }
}