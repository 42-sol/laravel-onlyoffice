<?php

namespace sol42\LaravelOnlyoffice;

use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Support\ServiceProvider;

class OnlyofficeServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/config/onlyoffice.php', 'onlyoffice');
    }

    public function boot(): void
    {
        AboutCommand::add('laravel-onlyoffice', fn () => ['Version' => '1.0.8']);

        // publish configs, views
        $this->publishes([
            __DIR__.'/config/onlyoffice.php' => config_path('onlyoffice.php'),
            __DIR__.'/resources/views' => resource_path('views/vendor/onlyoffice'),
        ], 'default');

        $this->publishes([
            __DIR__.'/Services/OnlyoffcieService.php' => app_path('onlyoffice.php')
        ], 'service');

        // load routes
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // load translations
        $this->loadTranslationsFrom(__DIR__.'/lang', 'onlyoffice');

        // load views
        $this->loadViewsFrom(__DIR__.'/resources/views', 'onlyoffice');
    }
}
