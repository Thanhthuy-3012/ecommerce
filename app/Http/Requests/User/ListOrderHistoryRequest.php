<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class ListOrderHistoryRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'start_date'    => 'nullable',
            'end_date'      => 'nullable'
        ];
    }
}
