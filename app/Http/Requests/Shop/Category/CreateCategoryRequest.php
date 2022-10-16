<?php

namespace App\Http\Requests\Shop\Category;

use App\Http\Requests\BaseRequest;

class CreateCategoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name_category' => 'required',
        ];
    }
}
