<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuruModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        if ($user?->role === 'superadmin') {
            return $next($request);
        }
        abort_unless($user && in_array($user->role, ['guru', 'tu'], true), 403);

        $academicMenus = array_merge(
            array_keys(config('assignment_menus.types.wali.menus', [])),
            array_keys(config('assignment_menus.types.guru.menus', []))
        );
        $hasAcademicAccess = $user->hasAnyAssignmentMenu($academicMenus);
        $hasExtracurricularAccess = $user->hasAnyAssignmentMenu(
            array_keys(config('assignment_menus.types.ekskul.menus', []))
        );

        $mode = session('guru_mode');
        if ($mode === 'ekskul' && ! $hasExtracurricularAccess) {
            $mode = 'akademik';
        } elseif ($mode !== 'ekskul' && ! $hasAcademicAccess && $hasExtracurricularAccess) {
            $mode = 'ekskul';
        } elseif (! in_array($mode, ['akademik', 'ekskul'], true)) {
            $mode = $hasAcademicAccess ? 'akademik' : 'ekskul';
        }

        session(['guru_mode' => $mode]);

        return $next($request);
    }
}