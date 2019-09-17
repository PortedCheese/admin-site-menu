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
            'target' => ! empty($this->target) ? $this->target : false,
            'method' => ! empty($this->method) ? $this->method : false,
            'template' => ! empty($this->template) ? $this->template : false,
            'children' => [],
        ];
    }
}