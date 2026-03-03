<?php

namespace Hakhant\Payments\Providers;

use Hakhant\Payments\Gateways\KbzGateway;
use Hakhant\Payments\Payment;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../../config/config.php' => config_path('payment.php'),
            ], 'mm-payment');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'payment');

        // Register the main class to use with the facade
        $this->app->singleton('payment', function ($app) {
            $config = $app['config']->get('payment');
            
            return new Payment(
                new KbzGateway($config)
            );
        });
    }
}
