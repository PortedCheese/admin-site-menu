<?php

namespace PortedCheese\AdminSiteMenu\Http\Requests;

use App\Menu;
use Illuminate\Foundation\Http\FormRequest;

class MenuStoreRequest extends FormRequest
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
        return Menu::requestMenuStore($this);
    }

    public function attributes()
    {
        return Menu::requestMenuStore($this, true);
    }
}
