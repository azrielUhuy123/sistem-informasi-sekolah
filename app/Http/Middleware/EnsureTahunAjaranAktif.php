<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class EnsureTahunAjaranAktif
{
    public function handle(Request $request, Closure $next)
    {
        $tahun = TahunAjaran::current()->first();

        if (!$tahun) {
            return redirect()
                ->back()
                ->with('error', 'Belum ada Tahun Ajaran aktif atau transisi.');
        }

        return $next($request);
    }
}
