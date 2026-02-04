<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class EnsureTahunAjaranAktif
{
    public function handle(Request $request, Closure $next)
    {
        if (!TahunAjaran::aktif()) {
            return redirect()
                ->back()
                ->with('error', 'Belum ada Tahun Ajaran aktif. Hubungi Admin.');
        }

        return $next($request);
    }
}
