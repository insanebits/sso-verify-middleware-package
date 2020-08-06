<?php

namespace Hostinger\SsoJwtDecode;

use Illuminate\Support\ServiceProvider;

/**
 * Class SsoJwtDecodeServiceProvider
 * @package Hostinger\SsoJwtDecode
 */
class SsoJwtDecodeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/sso-jwt-decode.php' => config_path('sso-jwt-decode.php')]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/sso-jwt-decode.php', 'sso-jwt-decode');
    }
}
