<?php

namespace Gr8devofficial\LaravelFreecassa;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/freekassa.php' => config_path('freekassa.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $configPath = __DIR__ . '/../config/freekassa.php';
        $this->mergeConfigFrom($configPath, 'freekassa');
    }
}
