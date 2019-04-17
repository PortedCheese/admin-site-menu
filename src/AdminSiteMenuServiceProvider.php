<?php

namespace PortedCheese\AdminSiteMenu;

use Illuminate\Support\ServiceProvider;
use PortedCheese\AdminSiteMenu\Console\Commands\MenuMakeCommand;

class AdminSiteMenuServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Подключение роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'admin-site-menu');
        
        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/admin-site-menu'),
        ], 'public');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                MenuMakeCommand::class,
            ]);
        }
    }

    public function register()
    {

    }

}