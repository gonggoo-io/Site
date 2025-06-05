<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

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
    }
    
}
