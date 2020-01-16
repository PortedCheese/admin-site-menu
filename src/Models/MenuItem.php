<?php

namespace PortedCheese\AdminSiteMenu\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use PortedCheese\AdminSiteMenu\Http\Requests\MenuItemStoreRequest;
use PortedCheese\AdminSiteMenu\Http\Requests\MenuItemUpdateRequest;
use PortedCheese\AdminSiteMenu\Http\Resources\MenuItem as MenuItemResource;
use App\Menu;

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
        'ico',
        'active',
        'single',
        "gate",
    ];

    protected $casts = [
        "active" => "array",
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function (\App\MenuItem $item) {
            // Удаляем подпункты меню.
            $item->clearItems();
            $item->forgetCache();
        });

        static::creating(function (\App\MenuItem $model) {
            if (empty($model->weight)) {
                $query = \App\MenuItem::query()
                    ->select("weight");
                if (! empty($model->parent_id)) {
                    $query->where("parent_id", $model->parent_id);
                }
                $max = $query->where("menu_id", $model->menu_id)
                    ->max("weight");
                $model->weight = $max + 1;
            }
        });

        static::created(function (\App\MenuItem $item) {
            $item->forgetCache();
        });

        static::updated(function (\App\MenuItem $item) {
            $item->forgetCache();
        });
    }

    /**
     * Элемент принадлежит к конкретному меню.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Может быть под меню.
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children() {
        return $this->hasMany(\App\MenuItem::class, 'parent_id');
    }

    /**
     * Может быть родитель.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(\App\MenuItem::class, 'parent_id');
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

    public function getActiveStateAttribute()
    {
        $array = $this->active;
        if (empty($array)) {
            $array = [];
        }
        return implode("|", $array);
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
        $menuItem = \App\MenuItem::create($itemData);
        if (empty($itemData['children'])) {
            return;
        }
        foreach ($itemData['children'] as $child) {
            $child['menu_id'] = $menuId;
            $child['parent_id'] = $menuItem->id;
            \App\MenuItem::create($child);
        }
    }

    /**
     * Валидация создания пункта меню.
     *
     * @param MenuItemStoreRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestMenuItemStore(MenuItemStoreRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                "menu_id" => "Меню",
                "title" => "Заголовок",
            ];
        }
        else {
            return [
                'menu_id' => "required|exists:menus,id",
                'title' => "required|min:2",
            ];
        }
    }

    /**
     * Валидация обновления пункта меню.
     *
     * @param MenuItemUpdateRequest $validator
     * @param bool $attr
     * @return array
     */
    public static function requestMenuItemUpdate(MenuItemUpdateRequest $validator, $attr = false)
    {
        if ($attr) {
            return [
                "title" => "Заголовок",
            ];
        }
        else {
            return [
                'title' => "required|min:2",
            ];
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
            $url = false;
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
        $resource = new MenuItemResource($this);
        $data = $resource->toArray(request());
        if (!empty($this->method)) {
            $children = $this->fillChildrenFromMethod();
        }
        else {
            $children = $this->fillChildren();
        }
        $data['children'] = $children;
        $data['url'] = $this->getUrl();
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
            $info = new MenuItemResource($child);
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
        $class = new \App\MenuItem();
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
