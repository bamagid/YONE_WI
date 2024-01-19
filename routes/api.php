<?php

use App\Http\Controllers\AdminSystemController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReseauController;

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

Route::post("login", [UserController::class, "login"]);
Route::post("loginadmin", [AdminSystemController::class, "login"]);

Route::group([
    "middleware" => ["auth:api"]
], function () {

    Route::get("profile", [UserController::class, "profile"]);
    Route::get("refresh", [UserController::class, "refreshToken"]);
    Route::get("logout", [UserController::class, "logout"]);
});

Route::group([
    "middleware" => ["auth:admin"]
], function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('reseaux', ReseauController::class);
});
