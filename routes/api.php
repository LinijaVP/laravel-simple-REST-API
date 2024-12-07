<?php

use App\Http\Controllers\Api\V1\WantlistController;
use App\Http\Controllers\Api\V1\CustomerController;
use App\Http\Controllers\SwaggerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Base route for swagger 
//Route::get('/',[SwaggerController::class,'index']);

// Routes for api resources
Route::group(['prefix'=> 'v1',"namespace" => "App\Http\Controllers\Api\V1"], function () {
    Route::apiResource('customer', CustomerController::class);
    Route::apiResource('wantlist', WantlistController::class);

    Route::post("wantlist/multiple", ["uses" => "WantlistController@storeMultiple"]);

    // Routes for users
    Route::post("/register", [AuthController::class, "register"]);
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/logout", [AuthController::class, "logout"])->middleware('auth:sanctum');
});
