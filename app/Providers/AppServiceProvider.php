<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

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

        $hasPermission = function ($user, $permissionName) {
            if ($user->role === 'master_admin') {
                return true;
            }

            $permissions = Cache::rememberForever('role_' . $user->role . '_permissions', function () use ($user) {
                try {
                    $setting = Setting::where('key', 'role_' . $user->role . '_permissions')->first();
                    return $setting ? $setting->value : [];
                } catch (\Exception $e) {
                    return [];
                }
            });

            return is_array($permissions) && in_array($permissionName, $permissions);
        };

        Gate::define('manage-letter-types', function ($user) use ($hasPermission) {
            return $hasPermission($user, 'manage_letter_types');
        });

        Gate::define('manage-users', function ($user) use ($hasPermission) {
            return $hasPermission($user, 'manage_users');
        });

        Gate::define('manage-requests', function ($user) use ($hasPermission) {
            return $hasPermission($user, 'manage_requests');
        });

        Gate::define('manage-articles', function ($user) use ($hasPermission) {
            return $hasPermission($user, 'manage_articles');
        });

        Gate::define('is-warga', function ($user) {
            return $user->role === 'warga';
        });
    }
}
