<?php

namespace App\Http\Requests\Shop\Product;

use App\Http\Requests\BaseRequest;

class UpdateProductRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'category_id'       => 'required',
            'name'              => 'required',
            'flag_image_product'=> 'required',
            'image_product'     => $this->flag_image_product == 0 ? 'nullable' : 'required|mimes:jpg,jpeg,png|max:10240',
            'flag_image1'       => 'required',
            'image1'            => $this->flag_image1 == 0 ? 'nullable' : 'nullable|mimes:jpg,jpeg,png|max:10240',
            'flag_image2'       => 'required',
            'image2'            => $this->flag_image2 == 0 ? 'nullable' : 'nullable|mimes:jpg,jpeg,png|max:10240',
            'flag_image3'       => 'required',
            'image3'            => $this->flag_image3 == 0 ? 'nullable' : 'nullable|mimes:jpg,jpeg,png|max:10240',
            'flag_image4'       => 'required',
            'image4'            => $this->flag_image4 == 0 ? 'nullable' : 'nullable|mimes:jpg,jpeg,png|max:10240',
            'flag_image5'       => 'required',
            'image5'            => $this->flag_image5 == 0 ? 'nullable' : 'nullable|mimes:jpg,jpeg,png|max:10240',
            'discount'          => 'nullable',
            'content'           => 'required',
            'price'             => 'required',
        ];
    }
}
