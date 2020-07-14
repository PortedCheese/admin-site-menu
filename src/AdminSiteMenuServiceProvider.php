<?php

namespace PortedCheese\AdminSiteMenu;

use App\Menu;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\AdminSiteMenu\Console\Commands\MenuMakeCommand;
use PortedCheese\AdminSiteMenu\Http\Helpers\MenuActive;
use PortedCheese\AdminSiteMenu\Http\Middleware\ManagementMenu;

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

        // Gates.
        Gate::define("menu-management", "App\Policies\MenuPolicy@viewAny");

        view()->composer('admin-site-menu::admin.includes.routes-modal', function ($view) {
            $routes = Route::getRoutes();
            $names = [];
            foreach ($routes->getRoutesByName() as $name => $route) {
                if (
                    in_array("GET", $route->methods) &&
                    (
                        (strstr($name, '.') === FALSE) ||
                        (strstr($name, 'site.') !== FALSE) ||
                        (strstr($name, '.page.') !== FALSE)
                    )
                ) {
                    $uri = $route->uri();
                    $names[$name] = $uri;
                }
            }
            $view->with('routes', $names);
        });

        // Добавляем главное меню сайта в основной шаблон сайта.
        view()->composer('layouts.app', function ($view) {
            $view->with('mainMenu', Menu::getByKey('main'));
        });

        $adminThemes = ['layouts.admin', 'layouts.paper', 'layouts.argon', "layouts.sb-admin"];
        view()->composer($adminThemes, function ($view) {
            $view->with('adminMenu', Menu::getByKey('admin'));
        });
    }

    public function register()
    {
        $this->app->bind('menuactive', function () {
            return app(MenuActive::class);
        });
    }

}