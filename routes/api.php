<?php

use App\Http\Controllers\AdminSystemController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReseauController;
use App\Http\Controllers\SearchController;

Route::post("login", [UserController::class, "login"]);
Route::get("profile", [UserController::class, "profile"]);
Route::get("refresh", [UserController::class, "refreshToken"]);
Route::get("logout", [UserController::class, "logout"]);
Route::patch('updateadmin', [AdminSystemController::class, 'update']);
Route::resource('users', UserController::class)->except('edit', 'create', 'show');
Route::get('users/etat/{user}', [UserController::class, "changerEtat"]);
Route::resource('roles', RoleController::class)->except(['show', 'create', 'edit']);
Route::get("roles/restaure/{role}", [RoleController::class, "restore"]);
Route::resource('reseaus', ReseauController::class);
Route::get("reseaus/restaurer/{reseau}", [ReseauController::class, "restore"]);
Route::post('search', [SearchController::class, 'searching']);
