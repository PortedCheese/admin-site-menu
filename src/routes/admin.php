<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "admin",
    'as' => 'admin.',
    'middleware' => ['web', 'role:admin|editor'],
    'namespace' => 'PortedCheese\AdminSiteMenu\Http\Controllers'
], function () {

    // Роуты меню.
    Route::resource('menus', 'MenuController')->except([
        'edit'
    ]);

    // Получить файл структы меню.
    Route::post('/menus/export', 'MenuController@export')
        ->name('menus.export');
    Route::post('/menus/import', 'MenuController@import')
        ->name('menus.import');

    // Роуты для элементов меню.
    Route::prefix('menus/items')->group(function () {
        // Добавить элемент меню.
        Route::get('/add/{menu}', 'MenuController@createItem')
            ->name('menus.create-item');
        // Добавить элемент нижнего уровня.
        Route::get('/add/{menu}/{item}', 'MenuController@createItem')
            ->name('menus.create-child-item');
        // Сохранить элемент.
        Route::post('/add/{menu}/store', 'MenuController@storeItem')
            ->name('menus.store-item');
        // Редактирование элемента.
        Route::get('/edit/{menuItem}', 'MenuController@editItem')
            ->name('menus.edit-item');
        // Обновление элемента.
        Route::put('/add/{menuItem}/update', 'MenuController@updateItem')
            ->name('menus.update-item');
        // Удаление элемента.
        Route::delete('/delete/{menuItem}', "MenuController@destroyItem")
            ->name('menus.destroy-item');
    });

    // Роуты для аякса.
    Route::prefix('vue')->group(function () {
        // Роуты меню.
        Route::prefix('menu')->group(function () {
            // Сменить вес элемента.
            Route::post('/{menuItem}/weight', 'MenuController@changeWeight')
                ->name('vue.menu.weight');
        });
    });
});