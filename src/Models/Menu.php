<?php

namespace PortedCheese\AdminSiteMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'key',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($menu) {
            // Удаляем пункты меню.
            $menu->clearItems();
        });
    }

    /**
     * У меню много ссылок.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany('PortedCheese\AdminSiteMenu\Models\MenuItem');
    }

    /**
     * При удалении меню удалить все ссылки.
     */
    public function clearItems()
    {
        foreach ($this->items as $item) {
            $item->delete();
        }
    }

    /**
     * Ищем по ключу.
     *
     * @param $key
     * @return array|bool
     */
    public static function getByKey($key)
    {
        $cached = Cache::get("menu:$key");
        if (!empty($cached)) {
            return $cached;
        }
        try {
            $menu = Menu::where('key', $key)->firstOrFail();
        } catch (\Exception $e) {
            return [];
        }
        $menuItems = $menu->items
            ->where('parent_id', NULL)
            ->sortBy('weight');
        $output = [];
        foreach ($menuItems as $menuItem) {
            $output[] = $menuItem->prepareForRender();
        }
        Cache::forever("menu:$key", $output);
        return $output;
    }

    /**
     * Получить данные для экспорта.
     *
     * @return array
     */
    public static function getExport()
    {
        $data = [];
        $menus = Menu::all();
        foreach ($menus as $menu) {
            $info = $menu->toArray();
            $items = [];
            foreach ($menu->items->where('parent_id', NULL) as $item) {
                $items[] = $item->prepareForExport();
            }
            $info['items'] = $items;
            $data[] = $info;
        }
        return $data;
    }

    /**
     * Заполнение меню из файла.
     *
     * @param $data
     */
    public static function makeImport($data)
    {
        foreach ($data as $menuData) {
            if (empty($menuData['key']) || empty($menuData['title'])) {
                continue;
            }
            try {
                $menu = Menu::where('key', $menuData['key'])
                    ->firstOrFail();
            } catch (\Exception $e) {
                $menu = Menu::create($menuData);
            }
            // Чистим старое меню, что бы не дублировать.
            $menu->clearItems();
            if (empty($menuData['items'])) {
                continue;
            }
            $menuId = $menu->id;
            foreach ($menuData['items'] as $menuItemData) {
                MenuItem::makeImport($menuId, $menuItemData);
            }
        }
    }
}
