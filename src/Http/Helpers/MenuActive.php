<?php

namespace PortedCheese\AdminSiteMenu\Http\Helpers;

use Illuminate\Support\Facades\Route;

class MenuActive
{
    public $currentRoute;
    public $data;

    const ACTIONS = [
        'getListClass',
        'getLinkClass',
        'getActive',
    ];

    /**
     * MenuActive constructor.
     * @param bool|object $menuItem
     * @param bool $currentRoute
     */
    public function __construct()
    {
        $this->currentRoute = Route::currentRouteName();
        $this->data = false;
    }

    /**
     * Вызвать метод.
     *
     * @param $method
     * @param $args
     * @return bool
     */
    public function __call($method, $args)
    {
        if (in_array($method, self::ACTIONS)) {
            $this->methodRouter($method, $args);
        }
        return $this->data;
    }

    /**
     * Вызов функции.
     *
     * @param $method
     * @param $args
     */
    public function methodRouter($method, $args)
    {
        switch ($method) {
            case 'getListClass':
                call_user_func_array([$this, "getCurrentListClass"], $args);
                break;

            case "getLinkClass":
                call_user_func_array([$this, "getCurrentLinkClass"], $args);
                break;

            case 'getActive':
                call_user_func_array([$this, "getCurrentActive"], $args);
                break;
        }
    }

    /**
     * Сформировать класс ссылки.
     *
     * @param $item
     * @param $active
     * @param string $begin
     */
    protected function getCurrentLinkClass($item, $active, $begin = '')
    {
        $class = [];
        if (! empty($begin)) {
            $class[] = $begin;
        }
        if ($item->children) {
            $class[] = "dropdown-toggle";
        }
        if ($item->class) {
            $class[] = $item->class;
        }
        if ($active) {
            $class[] = "active";
        }
        $this->data = implode(" ", $class);
    }

    /**
     * Класс элемента списка.
     *
     * @param $item
     * @param string $begin
     */
    protected function getCurrentListClass($item, $begin = '')
    {
        $class = [];
        if (! empty($begin)) {
            $class[] = $begin;
        }
        if ($item->children) {
            $class[] = "dropdown";
        }
        $this->data = implode(" ", $class);
    }

    /**
     * Активная ссылка.
     *
     * @param $item
     * @return bool
     */
    protected function getCurrentActive($item)
    {
        if ($item->single) {
            $active = $this->currentRoute === $item->route;
        }
        else {
            $active = $this->makeSubRoutes($item);
        }
        $this->data = $active;
        return $active;
    }

    /**
     * Посмотреть условие активации ссылки на подстараницах.
     *
     * @param $item
     * @return bool
     */
    private function makeSubRoutes($item)
    {
        $current = $this->splitRoute($this->currentRoute);
        $activeRoutes = [$this->splitRoute($item->route)];
        $disabledRoutes = [];
        if (! empty($item->active)) {
            foreach ($item->active as $route) {
                $result = $this->splitRoute($route);
                if (strripos($result, "!") === 0) {
                    $disabledRoutes[] = str_replace("!", "", $result);
                }
                else {
                    $activeRoutes[] = $result;
                }
            }
        }
        if (in_array($current, $disabledRoutes)) {
            return false;
        }
        return in_array($current, $activeRoutes);
    }

    /**
     * Разбить путь.
     *
     * @param $route
     * @return string
     */
    private function splitRoute($route)
    {
        $exploded = explode('.', $route);
        if (count($exploded) == 1) {
            return $route;
        }
        $str = [];
        for ($i = 0; $i < count($exploded) - 1; $i++) {
            $str[] = $exploded[$i];
        }
        return implode(".", $str);
    }
}