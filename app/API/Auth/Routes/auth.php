<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::group([
    "prefix" => "auth"
], function () {
    Route::post("login", "App\API\Auth\Presentation\AuthController@login")->name("login");
    Route::post("register", "App\API\Auth\Presentation\AuthController@register")->name("register");
    Route::group([
        "middleware" => "auth:api"
    ], function () {
        Route::get("logout", "App\API\Auth\Presentation\AuthController@logout");
        Route::get("user", "App\API\Auth\Presentation\AuthController@getUser");
    });
});
