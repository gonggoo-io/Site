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

        if (config('app.env') === 'local' || config('app.debug')) {
            $this->app->afterResolving(\Laravel\Socialite\SocialiteManager::class, function ($manager) {
                $manager->extend('kakao', function ($app) use ($manager) {
                    $config = $app['config']['services.kakao'];
                    
                    $provider = new \SocialiteProviders\Kakao\KakaoProvider(
                        $app['request'],
                        $config['client_id'],
                        $config['client_secret'],
                        $config['redirect']
                    );
                    
                    $httpClient = new \GuzzleHttp\Client([
                        'curl' => [
                            CURLOPT_SSL_VERIFYPEER => false,
                            CURLOPT_SSL_VERIFYHOST => false,
                        ],
                        'verify' => false,
                    ]);
                    
                    $provider->setHttpClient($httpClient);
                    
                    return $provider;
                });
            });
        }
    }
    
} 
