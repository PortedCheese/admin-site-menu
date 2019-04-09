<?php

namespace PortedCheese\AdminSiteMenu\Http\Controllers;

use PortedCheese\AdminSiteMenu\Http\Requests\MenuItemStoreRequest;
use PortedCheese\AdminSiteMenu\Http\Requests\MenuItemUpdateRequest;
use PortedCheese\AdminSiteMenu\Http\Requests\MenuStoreRequest;
use PortedCheese\AdminSiteMenu\Http\Requests\YamlLoadRequest;
use PortedCheese\AdminSiteMenu\Models\Menu;
use PortedCheese\AdminSiteMenu\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Yaml\Yaml;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-site-menu::admin.menu.index', [
            'menus' => Menu::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param MenuStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(MenuStoreRequest $request)
    {
        Menu::create($request->all());
        return redirect()->route('admin.menus.index')
            ->with('success', 'Новое меню добавлено');
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
        $menu->delete();
        return redirect()->route('admin.menus.index')
            ->with('success', 'Меню удалено');
    }

    /**
     * Форма создания пункта меню.
     *
     * @param Menu $menu
     * @param MenuItem|NULL $item
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function createItem(Menu $menu, MenuItem $item = NULL)
    {
        return view('admin-site-menu::admin.menu.create-item', [
            'menu' => $menu,
            'parent' => !empty($item) ? $item->id : $item,
        ]);
    }

    /**
     * Добавление пункта меню.
     *
     * @param MenuItemStoreRequest $request
     * @param Menu $menu
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeItem(MenuItemStoreRequest $request, Menu $menu)
    {
        MenuItem::create($request->all());
        return redirect()->route('admin.menus.index')
            ->with('success', 'Пункт меню добавлен');
    }

    /**
     * Удаление пункта меню.
     * @param MenuItem $menuItem
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroyItem(MenuItem $menuItem)
    {
        $menuItem->delete();
        return redirect()->route('admin.menus.index')
            ->with('success', 'Пункт меню удален');
    }

    /**
     * Форма редактирования пункта меню.
     *
     * @param MenuItem $menuItem
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editItem(MenuItem $menuItem)
    {
        return view('admin-site-menu::admin.menu.edit-item', [
            'menuItem' => $menuItem,
        ]);
    }

    /**
     * Обновление пункта меню.
     *
     * @param MenuItemUpdateRequest $request
     * @param MenuItem $menuItem
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateItem(MenuItemUpdateRequest $request, MenuItem $menuItem)
    {
        $menuItem->update($request->all());
        return redirect()->back()
            ->with('success', 'Успешно обновлено');
    }

    /**
     * Выгрузить струтктуру меню.
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function export()
    {
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
     * @param YamlLoadRequest $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function import(YamlLoadRequest $request)
    {
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
}
