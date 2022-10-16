<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\BaseRequest;

class CreateShopRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_shop'     => 'required',
            'address'       => 'required',
            'phone_number'  => ['required', 'string', 'max:11', 'min:10', 'regex:/(0)[0-9]{9,10}/'],
            'email_shop'    => ['required', 'string', 'unique:shops', 'email','max:100'],
            'image'         => 'required|mimes:jpg,jpeg,png|max:10240',
            'description'   => 'required',
        ];
    }
}
