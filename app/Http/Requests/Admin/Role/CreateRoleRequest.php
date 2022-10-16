<?php

namespace App\Http\Requests\Admin\Role;

use App\Http\Requests\BaseRequest;

class CreateRoleRequest extends BaseRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            "name_role" => ['required', 'string','max:100']
        ];
    }
}
