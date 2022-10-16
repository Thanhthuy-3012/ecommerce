<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;

class CreateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_name'     => 'required',
            'email'         => ['required', 'string', 'unique:users', 'email','max:100'],
            'gender'        => 'required|regex:/^[0-1]/',
            'phone_number'  => ['required', 'string', 'max:11', 'min:10', 'regex:/(0)[0-9]{9,10}/'],
            'address'       => 'required',
            'bithday'       => 'required',
            'role_id'       => 'required',
        ];
    }
}
