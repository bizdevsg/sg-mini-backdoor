<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

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
        Gate::define('manage-user-management', fn (User $user): bool => $user->isSuperadmin());

        RateLimiter::for('login', function (Request $request): Limit {
            $email = Str::lower((string) $request->string('email'));

            return Limit::perMinute(5)->by(Str::transliterate($email.'|'.$request->ip()));
        });

        RateLimiter::for('contact-form', function (Request $request): Limit {
            return Limit::perMinute(10)->by(Str::transliterate('contact-form|'.$request->ip()));
        });
    }
}
