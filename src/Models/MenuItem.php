<?php

namespace PortedCheese\AdminSiteMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

class MenuItem extends Model
{
    protected $fillable = [
        'title',
        'menu_id',
        'route',
        'url',
        'weight',
        'class',
        'middleware',
        'parent_id',
        'target',
        'method',
        'template',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($item) {
            // Удаляем подпункты меню.
            $item->clearItems();
            $item->forgetCache();
        });
        static::created(function ($item) {
            $item->forgetCache();
        });
        static::updated(function ($item) {
            $item->forgetCache();
        });
    }

    /**
     * Элемент принадлежит к конкретному меню.
     */
    public function menu()
    {
        return $this->belongsTo('PortedCheese\AdminSiteMenu\Models\Menu');
    }

    /**
     * Может быть под меню.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany('PortedCheese\AdminSiteMenu\Models\MenuItem', 'parent_id');
    }

    /**
     * Может быть родитель.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\MenuItem', 'parent_id');
    }

    /**
     * Нужно что бы что то было заполненно, иначе ссылка не выведется.
     * @param $value
     */
    public function setUrlAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['url'] = '#';
        }
        else {
            $this->attributes['url'] = $value;
        }
    }

    /**
     * Обработка импорта.
     *
     * @param $menuId
     * @param $itemData
     */
    public static function makeImport($menuId, $itemData)
    {
        $itemData['menu_id'] = $menuId;
        $menuItem = MenuItem::create($itemData);
        if (empty($itemData['children'])) {
            return;
        }
        foreach ($itemData['children'] as $child) {
            $child['menu_id'] = $menuId;
            $child['parent_id'] = $menuItem->id;
            MenuItem::create($child);
        }
    }

    /**
     * Получаем адрес ссылки.
     *
     * @return mixed|string
     */
    public function getUrl()
    {
        if (!empty($this->route) && Route::has($this->route)) {
            $url = route($this->route);
        }
        elseif (!empty($this->url)) {
            $url = $this->url;
        }
        else {
            $url = FALSE;
        }
        return $url;
    }

    /**
     * Удалить подменю.
     */
    public function clearItems()
    {
        foreach ($this->children as $item) {
            $item->delete();
        }
    }

    /**
     * Подготовка к выводу.
     *
     * @return object
     */
    public function prepareForRender()
    {
        $data = $this->toArray();
        if (!empty($this->method)) {
            $children = $this->fillChildrenFromMethod();
        }
        else {
            $children = $this->fillChildren();
        }
        $data['children'] = $children;
        $data['url'] = $this->getUrl();
        $data['ico'] = false;
        if (!empty($this->class) && strripos($this->class, '@') === 0) {
            $data['class'] = false;
            $data['ico'] = str_replace('@', '', $this->class);
        }
        $data['activeChild'] = [];
        if (!empty($this->route)) {
            $exploded = explode('|', $this->route);
            if (count($exploded) > 1) {
                foreach ($exploded as $item) {
                    // Разделить роут элемента.
                    $exploded = explode('.', str_replace("@", '', $item));
                    $route = [];
                    for ($i = 0; $i < count($exploded) - 1; $i++) {
                        $route[] = $exploded[$i];
                    }
                    $explodedItemRoute = implode('.', $route);
                    $data['activeChild'][] = $explodedItemRoute;
                }
            }
        }
        return (object) $data;
    }

    /**
     * Подготовка к экспорту.
     */
    public function prepareForExport()
    {
        $data = $this->toArray();
        if ($this->children->count()) {
            $info = [];
            foreach ($this->children as $child) {
                $info[] = $child->prepareForExport();
            }
            $data['children'] = $info;
        }
        return $data;
    }

    /**
     * Обходим дочерние элементы.
     *
     * @return array
     */
    private function fillChildren()
    {
        $childrenData = [];
        foreach ($this->children->sortBy('weight') as $child) {
            $info = $child->toArray();
            $info['children'] = false;
            $info['ico'] = false;
            $info['url'] = $child->getUrl();
            $childrenData[] = (object) $info;
        }
        return $childrenData;
    }

    /**
     * Берем список из указанного метода.
     *
     * @return array
     */
    private function fillChildrenFromMethod()
    {
        $method = $this->method;
        $children = [];
        if (class_exists('\App\MenuItem')) {
            $class = new \App\MenuItem();
        }
        else {
            $class = $this;
        }
        if (method_exists($class, $method)) {
            $children = [];
            $array = $class->{$method}();
            foreach ($array as $key => $item) {
                // Два поля обязательны.
                if (empty($item['title']) || empty($item['url'])) {
                    continue;
                }
                // Остальные можно заполнить по умолчанию.
                // У дочерних не может быть еще дочерних.
                $item['children'] = FALSE;
                $item['ico'] = false;
                $item['id'] = empty($item['id']) ? "{$this->id}-$key" : $item['id'];
                $item['class'] = empty($item['class']) ? "" : $item['class'];
                $item['target'] = empty($item['target']) ? "" : $item['target'];
                $children[] = (object) $item;
            }
        }
        return $children;
    }

    /**
     * Очистить кэш меню.
     */
    private function forgetCache()
    {
        $menu = $this->menu;
        if (empty($menu)) {
            return;
        }
        $key = $menu->key;
        Cache::forget("menu:$key");
    }


}
