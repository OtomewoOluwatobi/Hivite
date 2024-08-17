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
        Route::prefix('roles')->group(function () {
            Route::get('/', [RolePermissionController::class, 'index_role']);
            Route::post('/store', [RolePermissionController::class, 'store_role']);
        });
    });
});
