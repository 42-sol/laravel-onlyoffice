<?php

namespace sol42\LaravelOnlyoffice\Providers;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class OnlyofficeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
           '/config/onlyoffice.php', 'onlyoffice'
        );
    }

    public function boot(): void
    {
        AboutCommand::add('laravel-onlyoffice', fn () => ['Version' => '1.0.2']);

        // publish configs, views
        $this->publishes([
           '/config/onlyoffice.php' => config_path('onlyoffice.php'),
            '/resources/views' => resource_path('views/vendor/onlyoffice'),
        ]);

        // load routes
        $this->loadRoutesFrom('/routes/web.php');

        // load translations
        $this->loadTranslationsFrom('/lang', 'onlyoffice');

        // load views
        $this->loadViewsFrom('/resources/views', 'onlyoffice');
    }
}
