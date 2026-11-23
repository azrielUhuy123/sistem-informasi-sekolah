<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class MenuRouteAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        abort_unless($user, 401);

        if ($user->role === 'superadmin') {
            return $next($request);
        }

        $routeName = $request->route()?->getName();
        abort_unless($routeName, 403, 'Route tidak memiliki pemetaan hak akses menu.');

        if (in_array($routeName, config('assignment_menus.unrestricted_routes', []), true)) {
            return $next($request);
        }

        $menuKeys = $this->menuKeysForRoute($routeName);
        abort_unless($menuKeys, 403, 'Menu ini belum memiliki pemetaan hak akses.');
        abort_unless($user->hasAnyAssignmentMenu($menuKeys), 403, 'Akses menu tidak diberikan melalui penugasan aktif.');

        return $next($request);
    }

    public static function menuKeysForRoute(string $routeName): array
    {
        foreach (config('assignment_menus.route_access', []) as $pattern => $menuKeys) {
            if (Str::is($pattern, $routeName)) {
                return $menuKeys;
            }
        }

        return [];
    }
}
