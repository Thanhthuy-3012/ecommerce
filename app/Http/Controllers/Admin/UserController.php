<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Constant;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\CreateUserRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends BaseController
{
    protected $user;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function listUser()
    {
        $users = $this->user->with('role')->where('role_id', '!=', Constant::USER_ROLE['admin'])->get();

        foreach ($users as $user) {
            $user['gender'] = Constant::GENDER[$user->gender];
        }
        return $this->sendSuccessResponse($users);
    }

    public function createUser(CreateUserRequest $request)
    {
        try {
            $user = $this->user->create(array_merge(
                $request->all(),
                [
                    'password' => Hash::make('password'),
                ]
            ));

            return $this->sendSuccessResponse($user, 'Create User Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function updateUser($userId, UpdateUserRequest $request)
    {
        try {
            $user = $this->user->find($userId);
            
            if (!$user) return $this->sendError('User does not exist');

            $user->update(array_merge(
                $request->all(),
                [
                    'password' => Hash::make($request->password)
                ]
            ));

            return $this->sendSuccessResponse($user, 'Update User Succeed');
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function detailUser($userId) 
    {
        try {
            $user = $this->user->with('role')->find($userId);

            if (!$user) return $this->sendError('User does not exist');

            $user['gender'] = Constant::GENDER[$user->gender];

            return $this->sendSuccessResponse($user);
        } catch (\Throwable $th) {
            return $this->sendError($th->getMessage());
        }
    }

    public function deleteUser($userId)
    {
        try {
            $user = $this->user->with('role')->find($userId);

            if (!$user) return $this->sendError('User does not exist');
            
            DB::beginTransaction();
            
            $shops = Shop::query()->where('user_id', $userId)->get();

            foreach ($shops as $shop) {
                Product::query()->whereRelation('category', 'shop_id', '=', $shop->id)->delete();
                Category::query()->where('shop_id', $shop->id)->delete();
                $shop->delete();
            }
            $user->delete();

            DB::commit();

            return $this->sendSuccessResponse(null, 'Delete User Succeed');
        } catch (\Throwable $th) {
            DB::rollBack();

            return $this->sendError($th->getMessage());
        }
    }
}
