<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Pagination\Paginator;
use App\Models\User;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
            
        Paginator::useBootstrap();

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
