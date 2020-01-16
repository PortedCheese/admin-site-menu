<?php

namespace PortedCheese\AdminSiteMenu\Policies;

use App\Menu;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Gate;
use PortedCheese\BaseSettings\Traits\InitPolicy;

class MenuPolicy
{
    use HandlesAuthorization;
    use InitPolicy {
        InitPolicy::__construct as private __ipoConstruct;
    }

    const VIEW_ANY = 2;
    const EDIT_ADMIN = 4;
    const CREATE = 8;
    const VIEW = 16;
    const CREATE_ITEM = 32;
    const DELETE_ITEM = 64;
    const UPDATE_ITEM = 128;

    public function __construct()
    {
        $this->__ipoConstruct("MenuPolicy");
    }

    /**
     * Получить список доступов.
     *
     * @return array
     */
    public static function getPermissions()
    {
        return [
            self::VIEW_ANY => "Просмотр всех",
            self::EDIT_ADMIN => "Редактирование меню админки",
            self::CREATE => "Создание",
            self::VIEW => "Просмотр",
            self::CREATE_ITEM => "Добавление пункта меню",
            self::DELETE_ITEM => "Удаление пункта меню",
            self::UPDATE_ITEM => "Редактирование пункта меню",
        ];
    }

    /**
     * Стандартные права.
     *
     * @return int
     */
    public static function defaultRules()
    {
        return self::VIEW_ANY + self::VIEW + self::CREATE_ITEM + self::DELETE_ITEM + self::UPDATE_ITEM;
    }

    /**
     * Determine whether the user can view any tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->hasPermission($this->model, self::VIEW_ANY);
    }

    /**
     * Редактирование меню админки.
     *
     * @param User $user
     * @return bool
     */
    public function editAdmin(User $user)
    {
        return $user->hasPermission($this->model, self::EDIT_ADMIN);
    }

    /**
     * Determine whether the user can create tasks.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission($this->model, self::CREATE);
    }

    /**
     * Просмотр меню.
     *
     * @param User $user
     * @param Menu $menu
     * @return bool
     */
    public function view(User $user, Menu $menu)
    {
        if ($menu->key == "admin") {
            return $user->hasPermission($this->model, self::EDIT_ADMIN);
        }

        return $user->hasPermission($this->model, self::VIEW);
    }

    /**
     * Удаление меню.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user)
    {
        return Gate::allows("settings-management");
    }

    /**
     * Добавить пункт меню.
     *
     * @param User $user
     * @param Menu $menu
     * @return bool
     */
    public function createItem(User $user, Menu $menu)
    {
        if ($menu->key == "admin") {
            return $user->hasPermission($this->model, self::EDIT_ADMIN);
        }

        return $user->hasPermission($this->model, self::CREATE_ITEM);
    }

    /**
     * Удалить пункт меню.
     *
     * @param User $user
     * @param Menu $menu
     * @return bool
     */
    public function deleteItem(User $user, Menu $menu)
    {
        if ($menu->key == "admin") {
            return $user->hasPermission($this->model, self::EDIT_ADMIN);
        }

        return $user->hasPermission($this->model, self::DELETE_ITEM);
    }

    /**
     * Обновить пункт меню.
     *
     * @param User $user
     * @param Menu $menu
     * @return bool
     */
    public function editItem(User $user, Menu $menu)
    {
        if ($menu->key == "admin") {
            return $user->hasPermission($this->model, self::EDIT_ADMIN);
        }

        return $user->hasPermission($this->model, self::UPDATE_ITEM);
    }
}
