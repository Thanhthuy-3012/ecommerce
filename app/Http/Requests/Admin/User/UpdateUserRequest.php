<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends BaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $user = User::query()->find($this->userId);
        return [
            'user_name'     => 'required',
            'email'         => $user->email == $this->email ? ['required', 'string', 'email','max:100'] : ['required', 'string', 'unique:users', 'email','max:100'],
            'password'      => 'nullable',
            'gender'        => 'required|regex:/^[0-1]/',
            'phone_number'  => ['required', 'string', 'max:11', 'min:10', 'regex:/(0)[0-9]{9,10}/'],
            'address'       => 'required',
            'bithday'       => 'required',
            'role_id'       => 'required',
        ];
    }
}
