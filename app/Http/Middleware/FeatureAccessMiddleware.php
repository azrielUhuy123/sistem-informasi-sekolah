<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FeatureAccessMiddleware
{
    public function handle(Request $request, Closure $next, string $features): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $routeName = $request->route()?->getName();
        $menuKeys = $routeName
            ? MenuRouteAccessMiddleware::menuKeysForRoute($routeName)
            : [];
        $featureKeys = $menuKeys ?: array_map('trim', explode(',', $features));

        foreach ($featureKeys as $featureKey) {
            if ($user->hasFeature($featureKey)) {
                return $next($request);
            }
        }

        abort(403, 'Akses fitur tidak diberikan.');
    }
}
