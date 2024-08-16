<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;
use App\Http\Controllers\RolePermissionController;

Route::get('/', function () {
    return response()->json([
        "msg" => "hinvite says hello"
    ], Response::HTTP_OK);
});

Route::group(['middleware' => 'api',  'prefix' => 'admin'], function ($router) {

    Route::prefix('access_control')->group(function () {
        Route::prefix('roles')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index']);
            Route::post('/store', [RolePermissionController::class, 'store_role']);
            // Route::get('/{id}/show', [RoleController::class, 'show']);
            // Route::match(['post', 'put'],'/{id}/update', [RoleController::class, 'update']);
            // Route::delete('/{id}/delete', [RoleController::class, 'destroy']);
            // Route::post('/{id}/addPermissions', [RolePermissionController::class, 'addPermissions']);
        });

        // Route::prefix('permissions')->group(function () {
        //     Route::get('/', [PermissionController::class, 'index']);
        //     Route::post('/store', [PermissionController::class, 'store']);
        //     Route::get('/{id}/show', [PermissionController::class, 'show']);
        //     Route::match(['post', 'put'],'/{id}/update', [PermissionController::class, 'update']);
        //     Route::delete('/{id}/delete', [PermissionController::class, 'destroy']);
        // });

//        Route::get('/', [RoleController::class, 'index']);
    });
});
