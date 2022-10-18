<?php

use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Shop\Auth\LoginController as ShopLoginController;
use App\Http\Controllers\Shop\CategoryController;
use App\Http\Controllers\Shop\ProductController;
use App\Http\Controllers\Shop\ShopController as ShopShopController;
use App\Http\Controllers\User\Auth\LoginController as AuthLoginController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->group(function () {
    Route::group(['prefix' => 'admin'], function () {
        Route::post('/login', [LoginController::class, 'login']);
        Route::post('/logout', [LoginController::class, 'logout']);

        Route::group(['middleware' => ['assign.guard:admin']], function () {
            Route::group(['prefix' => 'user'], function () {
                Route::get('/', [UserController::class, 'listUser']);
                Route::post('/create', [UserController::class, 'createUser']);
                Route::patch('/{userId}', [UserController::class, 'updateUser']);
                Route::get('/{userId}', [UserController::class, 'detailUser']);
                Route::delete('/{userId}', [UserController::class, 'deleteUser']);
            });
    
            Route::group(['prefix' => 'role'], function () {
                Route::get('/', [RoleController::class, 'listRole']);
                Route::post('/create', [RoleController::class, 'createRole']);
                Route::patch('/{roleId}', [RoleController::class, 'updateRole']);
                Route::get('/{roleId}', [RoleController::class, 'detailRole']);
                Route::delete('/{roleId}', [RoleController::class, 'deleteRole']);
            });
    
            Route::group(['prefix' => 'shop'], function () {
                Route::get('/', [ShopController::class, 'list']);
                Route::get('/{shopId}', [ShopController::class, 'show']);
                Route::delete('/{shopId}', [ShopController::class, 'delete']);
            });
        });
    });

    Route::group(['prefix' => 'shop'], function () {
        Route::post('/login', [ShopLoginController::class, 'login']);
        Route::post('/logout', [ShopLoginController::class, 'logout']);
        Route::post('/register', [ShopLoginController::class, 'register']);

        Route::group(['middleware' => ['assign.guard:shop']], function () {
            Route::get('/', [ShopShopController::class, 'list']);
            Route::post('/create', [ShopShopController::class, 'create']);
            Route::post('/{shopId}', [ShopShopController::class, 'update']);
            Route::get('/{shopId}', [ShopShopController::class, 'show']);
            Route::delete('/{shopId}', [ShopShopController::class, 'delete']);

            Route::group(['prefix' => 'category'], function () {
                Route::get('/{shopId}', [CategoryController::class, 'list']);
                Route::post('/create/{shopId}', [CategoryController::class, 'create']);
                Route::patch('/{categoryId}', [CategoryController::class, 'update']);
                Route::get('/{categoryId}', [CategoryController::class, 'show']);
                Route::delete('/{categoryId}', [CategoryController::class, 'delete']);
            });

            Route::get('/{shopId}/product', [ProductController::class, 'listProductByShopId']);
            Route::get('/category/{categoryId}/product', [ProductController::class, 'listProductByCategoryId']);
            Route::post('/product/create', [ProductController::class, 'create']);
            Route::post('/product/{productId}', [ProductController::class, 'update']);
            Route::get('/product/{productId}', [ProductController::class, 'show']);
            Route::delete('/product/{productId}', [ProductController::class, 'delete']);
        });
    });

    Route::group(['prefix' => 'user'], function () {
        Route::post('/login', [AuthLoginController::class, 'login']);
        Route::post('/logout', [AuthLoginController::class, 'logout']);
        Route::post('/register', [AuthLoginController::class, 'register']);
    });
});
