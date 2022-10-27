<?php

namespace App\Http\Requests\User;

use App\Http\Requests\BaseRequest;

class CreateTransactionRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'orders'        => 'required',
            'user_name'     => 'required',
            'user_phone'    => ['required', 'string', 'max:11', 'min:10', 'regex:/(0)[0-9]{9,10}/'],
            'address'       => 'required',
            'amount'        => 'required',
            'payment'       => 'nullable',
            'payment_info'  => 'nullable',
            'security'      => 'nullable',
            'status'        => 'nullable',
        ];
    }
}
