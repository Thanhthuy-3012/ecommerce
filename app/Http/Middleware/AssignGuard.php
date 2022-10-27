<?php

namespace App\Http\Middleware;

use App\Constants\Constant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AssignGuard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        try {

            JWTAuth::parseToken()->authenticate();

            $guards = empty($guards) ? [null] : $guards;

            foreach ($guards as $guard) {
                if (Auth::guard($guard)->check()) {

                    if ($guard == "admin" && 
                        auth()->user()->role_id !== Constant::USER_ROLE['admin']) {

                        return response()->json([
                            'success' => false,
                            'message' => __("You don't have permission to access"),
                        ], 403);
                    } else if ($guard == "shop" && 
                        auth()->user()->role_id !== Constant::USER_ROLE['shop']) {

                        return response()->json([
                            'success' => false,
                            'message' => __("You don't have permission to access"),
                        ], 403);
                    } else if ($guard == "user" && 
                        auth()->user()->role_id !== Constant::USER_ROLE['user']) {

                        return response()->json([
                            'success' => false,
                            'message' => __("You don't have permission to access"),
                        ], 403);
                    }
                    return $next($request);
                }
            }
            return response()->json([
                'success' => false,
                'message' => __("You don't have permission to access"),
            ], 403);

        } catch (\Exception$e) {
            return response()->json([
                "success" => false,
                "message" => 'Token invalid',
            ], 403);
        }
    }
}
