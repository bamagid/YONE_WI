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
use App\Http\Controllers\NewsletterController;

Route::post("login", [UserController::class, "login"]);
Route::get("profile", [UserController::class, "profile"]);
Route::get("refresh", [UserController::class, "refreshToken"]);
Route::get("logout", [UserController::class, "logout"]);
Route::patch('updateadmin', [AdminSystemController::class, 'update']);
Route::apiResource('users', UserController::class)->except('show');
Route::get('users/etat/{user}', [UserController::class, "changerEtat"]);
Route::apiResource('roles', RoleController::class)->except(['show']);
Route::put("roles/restaure/{role}", [RoleController::class, "restore"]);
Route::get("roles/deleted", [RoleController::class, "deleted"]);
Route::post("roles/empty-trash", [RoleController::class, "emptyTrash"]);
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])
    ->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])
    ->name('reset.password.post');
Route::post('search', [SearchController::class, 'searching']);
Route::apiResource('abonnements', AbonnementController::class);
Route::patch('abonnements/restaurer/{abonnement}', [AbonnementController::class, "restore"]);
Route::get('abonnements/deleted/all', [AbonnementController::class, 'deleted']);
Route::post('abonnements/empty-trash', [AbonnementController::class, 'emptyTrash']);
Route::apiResource('lignes', LigneController::class);
Route::patch('lignes/restaurer/{ligne}', [LigneController::class, "restore"]);
Route::get('lignes/deleted/all', [LigneController::class, 'deleted']);
Route::post('lignes/empty-trash', [LigneController::class, 'emptyTrash']);
Route::apiResource('reseaus', ReseauController::class);
Route::patch('reseaus/restaurer/{reseau}', [ReseauController::class, "restore"]);
Route::get('reseaus/deleted/all', [ReseauController::class, 'deleted']);
Route::post('reseaus/empty-trash', [ReseauController::class, 'emptyTrash']);
Route::apiResource('sections', SectionController::class);
Route::patch('sections/restaurer/{section}', [SectionController::class, "restore"]);
Route::get('sections/deleted/all', [SectionController::class, 'deleted']);
Route::post('sections/empty-trash', [SectionController::class, 'emptyTrash']);
Route::apiResource('tarifs', TarifController::class);
Route::patch('tarifs/restaurer/{tarif}', [TarifController::class, "restore"]);
Route::get('tarifs/deleted/all', [TarifController::class, 'deleted']);
Route::post('tarifs/empty-trash', [TarifController::class, 'emptyTrash']);
Route::apiResource('types', TypeController::class);
Route::patch('types/restaurer/{type}', [TypeController::class, "restore"]);
Route::get('types/deleted/all', [TypeController::class, 'deleted']);
Route::post('types/empty-trash', [TypeController::class, 'emptyTrash']);
Route::post('newsletter', [NewsletterController::class, 'subscribe']);
Route::post('newsletter/all', [NewsletterController::class, 'showSubscribers']);
