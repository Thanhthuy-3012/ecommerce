<?php

namespace App\Http\Requests\Shop;

use App\Http\Requests\BaseRequest;
use App\Models\Shop;

class UpdateShopRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $shop = Shop::query()->find($this->shopId);
        return [
            'name_shop'     => 'required',
            'address'       => 'required',
            'phone_number'  => ['required', 'string', 'max:11', 'min:10', 'regex:/(0)[0-9]{9,10}/'],
            'email_shop'    => $shop->email_shop == $this->email_shop ? 'required' : ['required', 'string', 'unique:shops', 'email','max:100'],
            'flag_image'    => 'required',
            'image'         => $this->flag_image == 0 ? 'nullable' : 'required|mimes:jpg,jpeg,png|max:10240',
            'description'   => 'required',
        ];
    }
}
