<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CandidatController;
use App\Http\Controllers\CandidatureController;
use App\Http\Controllers\FormationController;

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

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
Route::controller(FormationController::class)->group(function () {
    Route::get('list/formation', 'index');
    Route::post('ajout/formation', 'store');
    Route::post('cloture/formation/{formation}', 'cloture');
    Route::post('modifier/formation/{formation}', 'update');
    Route::delete('supprimer/formation/{formation}', 'delete');
});
Route::controller(CandidatureController::class)->group(function () {
    Route::post('revoquer/candiadture/{candiadture}/{formation}', 'delete');
    Route::get('/show/candidature', 'index');
    Route::post('candidater/{formation}', 'store');
    Route::post('modifier/candiadture/{candiadture}', 'update');
});
Route::controller(CandidatController::class)->group(function () {
    Route::get('/show/candidat', 'index');
});
