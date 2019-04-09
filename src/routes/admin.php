<?php

// Админка.
Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'role:admin|editor'],
    'namespace' => 'PortedCheese\AdminSiteMenu\Http\Controllers'
], function () {
    // Роуты меню.
    Route::resource('menus', 'MenuController', [
        'as' => 'admin'
    ])->except([
        'show',
        'edit'
    ]);
    // Получить файл структы меню.
    Route::post('/menus/export', 'MenuController@export')
        ->name('admin.menus.export');
    Route::post('/menus/import', 'MenuController@import')
        ->name('admin.menus.import');
    // Роуты для элементов меню.
    Route::prefix('menus/items')->group(function () {
        // Добавить элемент меню.
        Route::get('/add/{menu}', 'MenuController@createItem')
            ->name('admin.menus.create-item');
        // Добавить элемент нижнего уровня.
        Route::get('/add/{menu}/{item}', 'MenuController@createItem')
            ->name('admin.menus.create-child-item');
        // Сохранить элемент.
        Route::post('/add/{menu}/store', 'MenuController@storeItem')
            ->name('admin.menus.store-item');
        // Редактирование элемента.
        Route::get('/edit/{menuItem}', 'MenuController@editItem')
            ->name('admin.menus.edit-item');
        // Обновление элемента.
        Route::put('/add/{menuItem}/update', 'MenuController@updateItem')
            ->name('admin.menus.update-item');
        // Удаление элемента.
        Route::delete('/delete/{menuItem}', "MenuController@destroyItem")
            ->name('admin.menus.destroy-item');
    });

    // Роуты для аякса.
    Route::prefix('vue')->group(function () {
        // Роуты меню.
        Route::prefix('menu')->group(function () {
            // Сменить вес элемента.
            Route::post('/{menuItem}/weight', 'MenuController@changeWeight')
                ->name('admin.vue.menu.weight');
        });
    });
});