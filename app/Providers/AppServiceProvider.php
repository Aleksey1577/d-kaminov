<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\RateLimiter;


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
        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->input('email');

            return [
                Limit::perMinute(10)->by($request->ip()),
                Limit::perMinute(5)->by($request->ip().'|'.$email),
            ];
        });

        RateLimiter::for('callback', fn (Request $request) => Limit::perMinute(6)->by($request->ip()));
        RateLimiter::for('checkout', fn (Request $request) => Limit::perMinute(5)->by($request->ip()));
    }

    
}
