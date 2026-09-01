<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('is-admin', function ($user) {
            return in_array($user->role, ['master_admin', 'super_admin', 'admin']);
        });

        Gate::define('is-master-admin', function ($user) {
            return $user->role === 'master_admin';
        });

        Gate::define('is-super-admin', function ($user) {
            return in_array($user->role, ['master_admin', 'super_admin']);
        });

        Gate::define('is-warga', function ($user) {
            return $user->role === 'warga';
        });
    }
}
