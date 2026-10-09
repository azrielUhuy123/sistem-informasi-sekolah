<?php

namespace App\Providers;

use App\Enums\UserRole;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Pagination\Paginator;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
            
        Paginator::useBootstrap();

            View::composer('layouts.partials.notifications', function ($view): void {
                $user = Auth::user();
                $allowedRoles = [
                    UserRole::SUPERADMIN,
                    UserRole::ADMIN,
                    UserRole::TU,
                    UserRole::GURU,
                    UserRole::MURID,
                ];

                if (! $user || ! in_array($user->role, $allowedRoles, true)) {
                    $view->with([
                        'notification_visible' => false,
                        'unread_count' => 0,
                        'recent_notifications' => collect(),
                    ]);

                    return;
                }

                $view->with([
                    'notification_visible' => true,
                    'unread_count' => $user->unreadNotifications()->count(),
                    'recent_notifications' => $user->notifications()->latest()->limit(5)->get(),
                ]);
            });

        Gate::define('manage-user', function (User $authUser, User $targetUser) {
            // superadmin boleh semua
            if ($authUser->role === 'superadmin') {
                return true;
            }

            // admin TIDAK BOLEH sentuh superadmin
            if ($targetUser->role === 'superadmin') {
                return false;
            }

            // admin boleh kelola user lain
            return true;
        });
    }
}
