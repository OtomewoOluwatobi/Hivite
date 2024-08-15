<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Response;

Route::get('/', function () {
    return response()->json([
        "msg" => "hinvite says hello"
    ], Response::HTTP_OK);
});
