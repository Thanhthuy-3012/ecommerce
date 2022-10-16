<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\BaseController;
use App\Http\Requests\Admin\Role\CreateRoleRequest;
use App\Http\Requests\Admin\Role\UpdateRoleRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    protected $role;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function listRole()
    {
        $roles = $this->role->all();
        return $this->sendSuccessResponse($roles);
    }

    public function createRole(CreateRoleRequest $request)
    {
        try {
            $role = $this->role->create($request->all());

            return $this->sendSuccessResponse($role, 'Create Role Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function updateRole($roleId, UpdateRoleRequest $request)
    {
        try {
            $role = $this->role->find($roleId);
            
            if (!$role) return $this->sendError('Role not exist');

            $role->update($request->all());

            return $this->sendSuccessResponse($role, 'Update Role Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function detailRole($roleId) 
    {
        try {
            $role = $this->role->find($roleId);
            
            if (!$role) return $this->sendError('Role not exist');

            return $this->sendSuccessResponse($role);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function deleteRole($roleId) 
    {
        try {
            DB::beginTransaction();

            $role = $this->role->find($roleId);
            
            if (!$role) return $this->sendError('Role not exist');

            if (in_array($role->id, Constant::USER_ROLE)) return $this->sendError('This role can not delete');

            $role->delete();
            User::query()->where('role_id', $roleId)->update([
                'role_id' => null
            ]);

            DB::commit();

            return $this->sendSuccessResponse(null, 'Delete Role Succeed');
        } catch (\Throwable $th) {
            DB::rollBack();
            
            return $this->sendError($th->getMessage());
        }
    }
}
