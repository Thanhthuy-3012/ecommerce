<?php

namespace App\Http\Requests\Shop\Product;

use App\Http\Requests\BaseRequest;

class CreateProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'   => 'required',
            'name'          => 'required',
            'image_product' => 'required|mimes:jpg,jpeg,png|max:10240',
            'image1'        => 'nullable|mimes:jpg,jpeg,png|max:10240',
            'image2'        => 'nullable|mimes:jpg,jpeg,png|max:10240',
            'image3'        => 'nullable|mimes:jpg,jpeg,png|max:10240',
            'image4'        => 'nullable|mimes:jpg,jpeg,png|max:10240',
            'image5'        => 'nullable|mimes:jpg,jpeg,png|max:10240',
            'discount'      => 'nullable',
            'content'       => 'required',
            'price'         => 'required|numeric',
        ];
    }
}
