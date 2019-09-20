<?php

namespace PortedCheese\AdminSiteMenu\Http\Requests;

use App\MenuItem;
use Illuminate\Foundation\Http\FormRequest;

class MenuItemUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return MenuItem::requestMenuItemUpdate($this);
    }

    public function attributes()
    {
        return MenuItem::requestMenuItemUpdate($this, true);
    }
}
