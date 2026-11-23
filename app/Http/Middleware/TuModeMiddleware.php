<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TuModeMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $user = $request->user();
        if ($user?->role === 'superadmin') {
            return $next($request);
        }
        abort_unless($user && in_array($user->role, ['guru', 'tu'], true), 403);

        $hasOperatorAccess = $user->hasAnyAssignmentMenu(
            array_keys(config('assignment_menus.types.operator.menus', []))
        );
        $hasPpdbAccess = $user->hasAnyAssignmentMenu(
            array_keys(config('assignment_menus.types.ppdb.menus', []))
        );
        abort_unless($hasOperatorAccess || $hasPpdbAccess, 403, 'Penugasan operator atau PPDB aktif diperlukan.');

        $mode = session('tu_mode');
        if ($mode === 'ppdb' && ! $hasPpdbAccess) {
            $mode = 'operasional';
        } elseif ($mode !== 'ppdb' && ! $hasOperatorAccess && $hasPpdbAccess) {
            $mode = 'ppdb';
        } elseif (! in_array($mode, ['operasional', 'ppdb'], true)) {
            $mode = $hasOperatorAccess ? 'operasional' : 'ppdb';
        }

        session(['tu_mode' => $mode]);

        return $next($request);
    }
}