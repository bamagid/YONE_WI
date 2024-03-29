<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\TarifController;
use App\Http\Controllers\ReseauController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\AbonnementController;
use App\Http\Controllers\HistoriqueController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\AdminSystemController;
use App\Http\Controllers\ForgotPasswordController;

Route::post("login", [UserController::class, "login"]);
Route::get("profile", [UserController::class, "profile"]);
Route::get("refresh", [UserController::class, "refreshToken"]);
Route::get("logout", [UserController::class, "logout"]);
Route::post('updateadmin', [AdminSystemController::class, 'update']);
Route::get('contactsadmin', [AdminSystemController::class, 'showContact']);
Route::apiResource('users', UserController::class)->except('show', 'update', 'destroy');
Route::get('users/blocked', [UserController::class, "usersblocked"]);
Route::post('/users/{user}', [UserController::class, 'update']);
Route::patch('/users/{user}', [UserController::class, 'destroy']);
Route::patch('users/etat/{user}', [UserController::class, "changerEtat"]);
Route::apiResource('roles', RoleController::class)->except(['show']);
Route::put("roles/restaurer/{role}", [RoleController::class, "restore"]);
Route::put("roles/delete/{role}", [RoleController::class, "delete"]);
Route::get("roles/deleted", [RoleController::class, "deleted"]);
Route::post("roles/empty-trash", [RoleController::class, "emptyTrash"]);
Route::post('forget-password', [ForgotPasswordController::class, 'submitForgetPasswordForm'])
    ->name('forget.password.post');
Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetPasswordForm'])
    ->name('reset.password.get');
Route::post('reset-password', [ForgotPasswordController::class, 'submitResetPasswordForm'])
    ->name('reset.password.post');
Route::apiResource('abonnements', AbonnementController::class);
Route::get('mesabonnements', [AbonnementController::class, "mesabonnements"]);
Route::patch('abonnements/restaurer/{abonnement}', [AbonnementController::class, "restore"]);
Route::get('abonnements/subscribe/{abonnement}', [AbonnementController::class, "subscribe"]);
Route::patch('abonnements/delete/{abonnement}', [AbonnementController::class, "delete"]);
Route::get('abonnements/deleted/all', [AbonnementController::class, 'deleted']);
Route::post('abonnements/empty-trash', [AbonnementController::class, 'emptyTrash']);
Route::apiResource('lignes', LigneController::class);
Route::get('meslignes', [LigneController::class, "meslignes"]);
Route::patch('lignes/restaurer/{ligne}', [LigneController::class, "restore"]);
Route::patch('lignes/delete/{ligne}', [LigneController::class, "delete"]);
Route::get('lignes/deleted/all', [LigneController::class, 'deleted']);
Route::post('lignes/empty-trash', [LigneController::class, 'emptyTrash']);
Route::apiResource('reseaus', ReseauController::class);
Route::patch('reseaus/restaurer/{reseau}', [ReseauController::class, "restore"]);
Route::patch('reseaus/delete/{reseau}', [ReseauController::class, "delete"]);
Route::post('reseau/details', [ReseauController::class, "details"])->middleware('auth:api');
Route::get('reseaus/deleted/all', [ReseauController::class, 'deleted']);
Route::post('reseaus/empty-trash', [ReseauController::class, 'emptyTrash']);
Route::apiResource('sections', SectionController::class);
Route::patch('sections/restaurer/{section}', [SectionController::class, "restore"]);
Route::patch('sections/delete/{section}', [SectionController::class, "delete"]);
Route::get('messections', [SectionController::class, "messections"]);
Route::get('sections/deleted/all', [SectionController::class, 'deleted']);
Route::post('sections/empty-trash', [SectionController::class, 'emptyTrash']);
Route::apiResource('tarifs', TarifController::class);
Route::get('mestarifs', [TarifController::class, "mestarifs"]);
Route::patch('tarifs/restaurer/{tarif}', [TarifController::class, "restore"]);
Route::patch('tarifs/delete/{tarif}', [TarifController::class, "delete"]);
Route::get('tarifs/deleted/all', [TarifController::class, 'deleted']);
Route::post('tarifs/empty-trash', [TarifController::class, 'emptyTrash']);
Route::apiResource('types', TypeController::class);
Route::get('mestypes', [TypeController::class, "mestypes"]);
Route::patch('types/restaurer/{type}', [TypeController::class, "restore"]);
Route::patch('types/delete/{type}', [TypeController::class, "delete"]);
Route::get('types/deleted/all', [TypeController::class, 'deleted']);
Route::post('types/empty-trash', [TypeController::class, 'emptyTrash']);
Route::post('newsletter/subscribe', [NewsletterController::class, 'subscribe']);
Route::post('newsletter/unscribe', [NewsletterController::class, 'unscribe']);
Route::get('newsletter/all', [NewsletterController::class, 'showSubscribers']);
Route::get('contacts', [ContactController::class, 'index']);
Route::post('contacts', [ContactController::class, 'store']);
Route::get('historiques', [HistoriqueController::class, 'index']);
Route::post('historiques/classe', [HistoriqueController::class, 'historiquesentite']);
Route::post('historiques/user', [HistoriqueController::class, 'historiquesuser']);
