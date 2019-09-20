<?php

namespace PortedCheese\AdminSiteMenu\Http\Requests;

use App\MenuItem;
use Illuminate\Foundation\Http\FormRequest;

class MenuItemStoreRequest extends FormRequest
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
        return MenuItem::requestMenuItemStore($this);
    }

    public function attributes()
    {
        return MenuItem::requestMenuItemStore($this, true);
    }
}
