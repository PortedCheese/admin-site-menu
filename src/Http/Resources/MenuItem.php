<?php

namespace PortedCheese\AdminSiteMenu\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MenuItem extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'menu_id' => $this->menu_id,
            'route' => ! empty($this->route) ? $this->route : false,
            'active' => ! empty($this->active) ? $this->active : [],
            'single' => $this->single,
            'ico' => ! empty($this->ico) ? $this->ico : false,
            'url' => ! empty($this->url) ? $this->url : false,
            'class' => ! empty($this->class) ? $this->class : "",
            'middleware' => ! empty($this->middleware) ? $this->middleware : false,
            'gate' => ! empty($this->gate) ? $this->gate : false,
            'target' => ! empty($this->target) ? $this->target : false,
            'method' => ! empty($this->method) ? $this->method : false,
            'template' => ! empty($this->template) ? $this->template : false,
            'children' => [],
            'parent_id' => $this->parent_id,

            'editItemUrl' => route('admin.menus.edit-item', ['menuItem' => $this]),
            'createChildUrl' => route('admin.menus.create-child-item', ['menu' => $this->menu_id, 'item' => $this]),
            'deleteItemUrl' => route('admin.menus.destroy-item', ['menuItem' => $this])
        ];
    }
}
