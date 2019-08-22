<?php

namespace ElicDev\MathCaptcha;

use Illuminate\Support\ServiceProvider;

class MathCaptchaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app['validator']->extend('mathcaptcha', function ($attribute, $value) {
            return $this->app['mathcaptcha']->verify($value);
        });

        $this->publishes([
            __DIR__ . '/config' => config_path(),
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mathcaptcha', function ($app) {
            return new MathCaptcha($this->app['session']);
        });

        $this->mergeConfigFrom(
            __DIR__ . '/config/math-captcha.php', 'math-captcha'
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['mathcaptcha'];
    }
}
