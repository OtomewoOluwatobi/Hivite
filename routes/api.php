<?php

use App\Http\Controllers\RolePermissionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::get('/', function () {
    return response()->json([
        "msg" => "hinvite says hello"
    ], Response::HTTP_OK);
});

Route::group(['middleware' => 'api',  'prefix' => 'admin'], function ($router) {

    Route::prefix('access_control')->group(function () {

        Route::get('/create', [RolePermissionController::class, 'create']);

        Route::prefix('roles')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index_role']);
            Route::post('/store', [RolePermissionController::class, 'store_role']);
            Route::get('/{id}/show', [RolePermissionController::class, 'show_role']);
            Route::match(['post', 'put'], '/{id}/update', [RolePermissionController::class, 'update_role']);
            Route::delete('/{id}/delete', [RolePermissionController::class, 'destroy_role']);
        });

        Route::prefix('permissions')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index_permission']);
            Route::post('/store', [RolePermissionController::class, 'store_permission']);
            Route::get('/{id}/show', [RolePermissionController::class, 'show_permission']);
            Route::match(['post', 'put'], '/{id}/update', [RolePermissionController::class, 'update_permission']);
            Route::delete('/{id}/delete', [RolePermissionController::class, 'destroy_permission']);
        });
    });
});
