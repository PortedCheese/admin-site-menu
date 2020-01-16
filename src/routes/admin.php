<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "admin",
    'as' => 'admin.',
    'middleware' => ['web', 'management'],
    'namespace' => 'App\Http\Controllers\Vendor\AdminSiteMenu\Admin'
], function () {

    // Роуты меню.
    Route::resource('menus', 'MenuController')->except([
        'edit'
    ]);

    Route::group([
        "middleware" => ["super"],
    ], function () {
        // Получить файл структы меню.
        Route::post('/menus/export', 'MenuController@export')
            ->name('menus.export');
        Route::post('/menus/import', 'MenuController@import')
            ->name('menus.import');
    });

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
    Route::group([
        'prefix' => "vue/menu",
        "as" => "vue.menu.",
    ], function () {
        // Сменить вес элемента.
        Route::post('/{menuItem}/weight', 'MenuController@changeWeight')
            ->name('weight');
        // Изменить вес элементов.
        Route::put("/order", "MenuController@changeItemsWeight")
            ->name("order");
    });
});