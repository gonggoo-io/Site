<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use SocialiteProviders\Manager\SocialiteWasCalled;

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

        Event::listen(SocialiteWasCalled::class, function ($socialiteWasCalled) {
            $socialiteWasCalled->extendSocialite('kakao', \SocialiteProviders\Kakao\KakaoProvider::class);
        });

        $this->app->afterResolving(\Laravel\Socialite\SocialiteManager::class, function ($manager) {
            $manager->extend('kakao', function ($app) use ($manager) {
                $config = $app['config']['services.kakao'];
                
                if (empty($config['client_id']) || empty($config['client_secret'])) {
                    return null;
                }
                
                $provider = new \SocialiteProviders\Kakao\KakaoProvider(
                    $app['request'],
                    $config['client_id'],
                    $config['client_secret'],
                    $config['redirect']
                );
                
                $httpClient = new \GuzzleHttp\Client([
                    'curl' => [
                        CURLOPT_SSL_VERIFYPEER => config('app.env') === 'production',
                        CURLOPT_SSL_VERIFYHOST => config('app.env') === 'production' ? 2 : 0,
                    ],
                    'verify' => config('app.env') === 'production',
                ]);
                
                $provider->setHttpClient($httpClient);
                
                return $provider;
            });
        });
    }
    
} 
