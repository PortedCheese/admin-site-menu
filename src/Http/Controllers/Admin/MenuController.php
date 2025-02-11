<?php

namespace PortedCheese\AdminSiteMenu\Http\Controllers\Admin;

use App\Menu;
use App\MenuItem;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use PortedCheese\AdminSiteMenu\Http\Requests\YamlLoadRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Yaml\Yaml;

class MenuController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->authorizeResource(Menu::class, "menu");
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $noKeys = [];
        if (! Gate::allows("editAdmin", Menu::class)) {
            $noKeys[] = "admin";
        }
        $menus = Menu::query()
            ->whereNotIn("key", $noKeys)
            ->orderBy("title")
            ->get();
        return view('admin-site-menu::admin.menu.index', [
            'menus' => $menus,
        ]);
    }

    /**
     * Просмотр меню.
     *
     * @param Menu $menu
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Menu $menu)
    {
        $menuStructure = Menu::getByKey($menu->key);
        return view("admin-site-menu::admin.menu.show", [
            'menu' => $menu,
            'structure' => $menuStructure,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->storeValidator($request->all());
        $menu = Menu::create($request->all());
        return redirect()
            ->route('admin.menus.show', ['menu' => $menu])
            ->with('success', 'Новое меню добавлено');
    }

    /**
     * Валидация сохранения меню.
     *
     * @param $data
     */
    protected function storeValidator($data)
    {
        Validator::make($data, [
            "title" => ["required", "max:100", "unique:menus,title"],
            "key" => ["required", "max:100", "unique:menus,key"],
        ], [], [
            "title" => "Название",
            "key" => "Ключ",
        ])->validate();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Menu $menu)
    {
        if (in_array($menu->key, ['main', 'admin'])) {
            return redirect()
                ->back()
                ->with('danger', 'Невозможно удалить это меню');
        }
        $menu->delete();
        return redirect()
            ->route('admin.menus.index')
            ->with('success', 'Меню удалено');
    }

    /**
     * Форма создания пункта меню.
     *
     * @param Menu $menu
     * @param MenuItem|NULL $item
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createItem(Menu $menu, MenuItem $item = NULL)
    {
        $this->authorize("createItem", $menu);
        return view('admin-site-menu::admin.item.create', [
            'menu' => $menu,
            'parent' => !empty($item) ? $item->id : $item,
        ]);
    }

    /**
     * Добавление пункта меню.
     *
     * @param Request $request
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function storeItem(Request $request, Menu $menu)
    {
        $this->authorize("createItem", $menu);
        $this->storeItemValidator($request->all());

        $userInput = $request->all();
        if (! empty($userInput['active_state'])) {
            $userInput['active'] = explode("|", $userInput['active_state']);
        }
        $userInput['single'] = !empty($userInput['single']) ? 1 : 0;
        MenuItem::create($userInput);
        return redirect()
            ->route('admin.menus.show', ['menu' => $menu])
            ->with('success', 'Пункт меню добавлен');
    }

    protected function storeItemValidator($data)
    {
        Validator::make($data, [
            "menu_id" => ["required", "exists:menus,id"],
            "title" => ["required", "max:100"],
        ], [], [
            "menu_id" => "Меню",
            "title" => "Заголовок",
        ])->validate();
    }

    /**
     * Удаление пункта меню.
     * @param MenuItem $menuItem
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroyItem(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $this->authorize("deleteItem", $menu);
        $menuItem->delete();
        return redirect()->back()
            ->with('success', 'Пункт меню удален');
    }

    /**
     * Форма редактирования пункта меню.
     *
     * @param MenuItem $menuItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function editItem(MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $this->authorize("editItem", $menu);
        return view('admin-site-menu::admin.item.edit', [
            'menuItem' => $menuItem,
            'menu' => $menu,
        ]);
    }

    /**
     * Обновление пункта меню.
     *
     * @param Request $request
     * @param MenuItem $menuItem
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateItem(Request $request, MenuItem $menuItem)
    {
        $menu = $menuItem->menu;
        $this->authorize("editItem", $menu);
        $this->updateItemValidator($request->all(), $menuItem);

        $userInput = $request->all();
        if (! empty($userInput['active_state'])) {
            $userInput['active'] = explode("|", $userInput['active_state']);
        }
        else {
            $userInput['active'] = null;
        }
        $userInput['single'] = !empty($userInput['single']) ? 1 : 0;
        $menuItem->update($userInput);
        return redirect()
            ->route('admin.menus.show', ['menu' => $menu])
            ->with('success', 'Успешно обновлено');
    }

    /**
     * Валидация обновления.
     *
     * @param $data
     * @param MenuItem $menuItem
     */
    protected function updateItemValidator($data, MenuItem $menuItem)
    {
        Validator::make($data, [
            "title" => ["required", "max:100"],
        ], [], [
            "title" => "Заголовок",
        ])->validate();
    }

    /**
     * Выгрузить струтктуру меню.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function export()
    {
        $this->authorize("settings-management");
        $data = Menu::getExport();
        $yaml = Yaml::dump($data);
        $response = response($yaml, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="export.yaml"',
        ]);
        return $response;
    }

    /**
     * Загрузка структуры.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function import(Request $request)
    {
        $this->authorize("settings-management");
        $this->importValidator($request->all());

        if ($request->hasFile('file')) {
            $content = $request
                ->file('file')
                ->get();
            $data = Yaml::parse($content);
            if (!empty($data)) {
                Menu::makeImport($data);
            }
            return redirect()
                ->route('admin.menus.index')
                ->with('success', 'Меню обновлено');
        }
        else {
            return redirect()
                ->route('admin.menus.index')
                ->with('danger', 'Файл не найден');
        }
    }

    protected function importValidator($data)
    {
        Validator::make($data, [
            "file" => ["required", "file", "mimes:yaml,yml,txt"],
        ], [], [
            "file" => "Файл",
        ])->validate();
    }

    /**
     * Изменить вес меню.
     *
     * @param Request $request
     * @param MenuItem $menuItem
     * @return array
     */
    public function changeWeight(Request $request, MenuItem $menuItem)
    {
        if (!(
            $request->has('changed') &&
            is_numeric($request->get('changed')) &&
            $request->get('changed') >= 0
        )) {
            return [
                'success' => FALSE,
                'message' => "Вес не найден",
            ];
        }
        $menuItem->weight = $request->get('changed');
        $menuItem->save();
        return [
            'success' => TRUE,
            'weight' => $menuItem->weight,
        ];
    }

    /**
     * Применить порядок.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeItemsWeight(Request $request)
    {
        if (! empty($request->get("items"))) {
            $items = $request->get("items");
            $menuId = $items[0]['menu_id'];
            $this->setWeight($items);
            try {
                $menu = Menu::find($menuId);
                Cache::forget("menu:{$menu->key}");
            }
            catch (\Exception $exception) {
                return response()
                    ->json("Меню не найдено, кэш не очищен");
            }
            return response()
                ->json("Порядок сохранен");
        }
        else {
            return response()
                ->json("Ошибка, недостаточно данных");
        }
    }

    /**
     * Установить вес.
     *
     * @param array $items
     */
    private function setWeight(array $items)
    {
        foreach ($items as $weight => $item) {
            if (! empty($item['children'])) {
                $this->setWeight($item['children']);
            }
            $id = $item['id'];
            DB::table("menu_items")
                ->where("id", $id)
                ->update(["weight" => $weight]);
        }
    }
}
