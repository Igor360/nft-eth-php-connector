<?php

namespace Igor360\NftEthPhpConnector;

use Igor360\NftEthPhpConnector\Interfaces\ConfigInterface;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => \config_path(ConfigInterface::BASE_KEY . '.php'),
            ], 'config');
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', ConfigInterface::BASE_KEY);
    }
}
