<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\ReseauController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\AdminSystemController;
use App\Http\Controllers\ForgotPasswordController;

Route::post("login", [UserController::class, "login"]);
Route::get("profile", [UserController::class, "profile"]);
Route::get("refresh", [UserController::class, "refreshToken"]);
Route::get("logout", [UserController::class, "logout"]);
Route::patch('updateadmin', [AdminSystemController::class, 'update']);
Route::resource('users', UserController::class)->except('edit', 'create', 'show');
Route::get('users/etat/{user}', [UserController::class, "changerEtat"]);
Route::resource('roles', RoleController::class)->except(['show', 'create', 'edit']);
Route::get("roles/restaure/{role}", [RoleController::class, "restore"]);
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])
    ->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])
    ->name('reset.password.post');
Route::post('search', [SearchController::class, 'searching']);
Route::apiResource('abonnements', AbonnementController::class);
Route::patch('abonnements/restaurer/{abonnement}', [AbonnementController::class, "restore"]);
Route::post('abonnements/deleted', [AbonnementController::class, 'deleted']);
Route::post('abonnements/empty-trash', [AbonnementController::class, 'emptyTrash']);
Route::apiResource('lignes', LigneController::class);
Route::patch('lignes/restaurer/{ligne}', [LigneController::class, "restore"]);
Route::post('lignes/deleted', [LigneController::class, 'deleted']);
Route::post('lignes/empty-trash', [LigneController::class, 'emptyTrash']);
Route::apiResource('reseaus', ReseauController::class);
Route::patch('reseaus/restaurer/{reseau}', [ReseauController::class, "restore"]);
Route::post('reseaus/deleted', [ReseauController::class, 'deleted']);
Route::post('reseaus/empty-trash', [ReseauController::class, 'emptyTrash']);
Route::apiResource('sections', SectionController::class);
Route::patch('sections/restaurer/{section}', [SectionController::class, "restore"]);
Route::post('sections/deleted', [SectionController::class, 'deleted']);
Route::post('sections/empty-trash', [SectionController::class, 'emptyTrash']);
Route::apiResource('tarifs', TarifController::class);
Route::patch('tarifs/restaurer/{tarif}', [TarifController::class, "restore"]);
Route::post('tarifs/deleted', [TarifController::class, 'deleted']);
Route::post('tarifs/empty-trash', [TarifController::class, 'emptyTrash']);
Route::apiResource('types', TypeController::class);
Route::patch('types/restaurer/{type}', [TypeController::class, "restore"]);
Route::post('types/deleted', [TypeController::class, 'deleted']);
Route::post('types/empty-trash', [TypeController::class, 'emptyTrash']);
