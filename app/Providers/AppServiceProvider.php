<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    
    public function boot(): void
    {
        if (!Schema::hasTable('user') || !Schema::hasTable('sessions')) {
            Artisan::call('migrate', ['--force' => true]);
        }

        Event::listen(Login::class, function ($event) {
            if (request()->has('remember') && request()->boolean('remember')) {
                Cookie::queue(
                    Cookie::make(
                        Auth::getRecallerName(),
                        Auth::user()->getRememberToken(),
                        4320
                    )
                );
            }
        });
    }
    
}
