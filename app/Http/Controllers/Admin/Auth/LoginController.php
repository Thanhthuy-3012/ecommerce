<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Constants\Constant;
use App\Http\Controllers\BaseController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;

class LoginController extends BaseController
{

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {

        try {

            $rules = [
                'email' => ['required', 'string', 'max:50'],
                'password' => 'required',
            ];

            $credentials = $request->only('email', 'password');

            $validator = Validator::make($credentials, $rules);
            $errors = $validator->errors();

            if ($errors->first()) {
                return $this->sendError($errors->first(), Response::HTTP_BAD_REQUEST);
            }

            if (!$token = auth()->attempt($credentials)) {
                return $this->sendError(__('Email or password incorrect'), Response::HTTP_BAD_REQUEST);
            }

            if (auth()->user()->role_id != Constant::USER_ROLE['admin']) {
                return $this->sendError(__('Email or password incorrect'), Response::HTTP_BAD_REQUEST);
            }

            $data = [
                'access_token' => $token,
                'token_type' => 'bearer',
            ];
            return $this->sendSuccessResponse($data, __('Login successful'));

        } catch (JWTException $e) {

            return $this->sendError(__('Login faild'), Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function logout()
    {

        try {
            auth()->logout();
            return $this->sendSuccessResponse();
        } catch (JWTException $e) {
            return $this->sendError(__('Token invalid'), Response::HTTP_INTERNAL_SERVER_ERROR);

        }

    }
}
