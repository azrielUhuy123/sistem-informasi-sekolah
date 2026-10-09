<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $this->ensureSupportedRole($request);

        $user = $request->user();
        $notifications = $user->notifications()->latest()->paginate(10);
        $unreadCount = $user->unreadNotifications()->count();
        $actionUrls = $notifications->getCollection()->mapWithKeys(function ($notification) use ($request) {
            return [
                $notification->id => $this->internalActionUrl(
                    data_get($notification->data, 'action_url'),
                    $request,
                ),
            ];
        });

        return view('notifications.index', compact(
            'notifications',
            'unreadCount',
            'actionUrls',
        ));
    }

    public function read(Request $request, string $notification)
    {
        $this->ensureSupportedRole($request);

        $notification = $request->user()->notifications()->findOrFail($notification);
        $notification->markAsRead();

        $actionUrl = $this->internalActionUrl(
            data_get($notification->data, 'action_url'),
            $request,
        );

        return $actionUrl
            ? redirect()->to($actionUrl)
            : redirect()->route('notifications.index');
    }

    public function readAll(Request $request)
    {
        $this->ensureSupportedRole($request);

        $request->user()->unreadNotifications()->update([
            'read_at' => now(),
        ]);

        return redirect()->route('notifications.index');
    }

    private function ensureSupportedRole(Request $request): void
    {
        abort_unless(in_array($request->user()->role, [
            UserRole::SUPERADMIN,
            UserRole::ADMIN,
            UserRole::TU,
            UserRole::GURU,
            UserRole::MURID,
        ], true), 403);
    }

    private function internalActionUrl(mixed $actionUrl, Request $request): ?string
    {
        if (
            ! is_string($actionUrl)
            || preg_match('/[\x00-\x20\x7f\\\\]/', $actionUrl)
        ) {
            return null;
        }

        $parts = parse_url($actionUrl);
        $hasAuthority = is_array($parts)
            && (isset($parts['scheme']) || isset($parts['host']));

        if (
            $parts === false
            || isset($parts['user'])
            || isset($parts['pass'])
        ) {
            return null;
        }

        if ($hasAuthority) {
            $currentOrigin = parse_url($request->getSchemeAndHttpHost());

            if (
                ! isset($parts['scheme'], $parts['host'])
                || ! in_array(strtolower($parts['scheme']), ['http', 'https'], true)
                || ! is_array($currentOrigin)
                || $this->normalizedOrigin($parts) !== $this->normalizedOrigin($currentOrigin)
            ) {
                return null;
            }
        } elseif (
            ! isset($parts['path'])
            || ! str_starts_with($actionUrl, '/')
            || str_starts_with($actionUrl, '//')
        ) {
            return null;
        }

        $path = $parts['path'] ?? '/';

        if (! str_starts_with($path, '/') || str_starts_with($path, '//')) {
            return null;
        }

        $decodedPath = $path;

        do {
            if (preg_match('/%(?:2f|5c)/i', $decodedPath)) {
                return null;
            }

            $previousPath = $decodedPath;
            $decodedPath = rawurldecode($decodedPath);
        } while ($decodedPath !== $previousPath);

        if (preg_match('/[\x00-\x20\x7f\\\\]/', $decodedPath)) {
            return null;
        }

        $segments = explode('/', trim($decodedPath, '/'));

        if (in_array('..', $segments, true) || in_array('.', $segments, true)) {
            return null;
        }

        try {
            $matchedRoute = Route::getRoutes()->match(
                Request::create($request->getSchemeAndHttpHost().$path, 'GET')
            );
        } catch (HttpExceptionInterface) {
            return null;
        }

        if ($matchedRoute->getName() === 'gateway') {
            return null;
        }

        return $path
            . (isset($parts['query']) ? '?'.$parts['query'] : '')
            . (isset($parts['fragment']) ? '#'.$parts['fragment'] : '');
    }

    private function normalizedOrigin(array $parts): ?string
    {
        $scheme = strtolower($parts['scheme'] ?? '');
        $host = strtolower($parts['host'] ?? '');

        if (! in_array($scheme, ['http', 'https'], true) || $host === '') {
            return null;
        }

        $port = $parts['port'] ?? null;

        if (($scheme === 'http' && $port === 80) || ($scheme === 'https' && $port === 443)) {
            $port = null;
        }

        if (str_contains($host, ':') && ! str_starts_with($host, '[')) {
            $host = '['.$host.']';
        }

        return $scheme.'://'.$host.($port !== null ? ':'.$port : '');
    }
}