<?php

namespace PortedCheese\AdminSiteMenu;

use Illuminate\Support\ServiceProvider;

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
        $this->publishes([
            __DIR__ . '/resources/views/layouts/menu' => resource_path('views/vendor/admin-site-menu'),
        ]);
        // Assets.
        $this->publishes([
            __DIR__ . '/public/js' => public_path('vendor/admin-site-menu'),
        ], 'public');
    }

    public function register()
    {

    }

}