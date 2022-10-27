<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class UpdateToCartRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'quantity' => 'required|numeric|min:0',
        ];
    }
}
