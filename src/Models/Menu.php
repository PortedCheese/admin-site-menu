<?php

namespace PortedCheese\AdminSiteMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\MenuItem;
use Illuminate\Support\Facades\Log;

class Menu extends Model
{
    protected $fillable = [
        'title',
        'key',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (\App\Menu $menu) {
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
        return $this->hasMany(MenuItem::class);
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
        $cacheKey = "menu:$key";
        $output = Cache::rememberForever($cacheKey, function () use ($key) {
            try {
                $menu = self::query()
                    ->where('key', $key)
                    ->firstOrFail();
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
            return $output;
        });
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
        $menus = self::all();
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
                $menu = self::query()
                    ->where('key', $menuData['key'])
                    ->firstOrFail();
            } catch (\Exception $e) {
                $menu = self::create($menuData);
            }
            // Чистим элементы меню которых нет.
            $menu->clearExportUuid($menuData['items']);
            if (empty($menuData['items'])) {
                continue;
            }
            $menuId = $menu->id;
            foreach ($menuData['items'] as $menuItemData) {
                MenuItem::makeImport($menuId, $menuItemData);
            }
        }
    }

    public function clearExportUuid($items)
    {
        $ids = $this->findUuid($items);
        debugbar()->info($ids);
        $menuItems = MenuItem::query()
            ->where("menu_id", $this->id)
            ->whereNotIn("uuid", $ids)
            ->orWhereNull("uuid")
            ->get();
        debugbar()->info($menuItems);
        foreach ($menuItems as $item) {
            $item->delete();
        }
    }

    public function findUuid($items)
    {
        $ids = [];
        foreach ($items as $item) {
            if (! empty($item["uuid"])) {
                $ids[] = $item["uuid"];
                if (! empty($item["children"])) {
                    $sub = $this->findUuid($items["children"]);
                    $ids = array_merge($ids, $sub);
                }
            }
        }
        return $ids;
    }
}
