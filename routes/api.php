<?php

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'middleware' => ['auth:sanctum'],
], function(){

    Route::get('user', [AuthenticatedSessionController::class, 'me']);
});

Route::group([
    'middleware' => ['auth:sanctum'],
    'prefix' => 'v1'
], function(){

    Route::get('request', [BaseController::class, 'fractalResponse']);

    Route::group([
        'as' => 'users.'
    ], function(){
        Route::get('users', [UserController::class, 'index'])->name('index');
        Route::post('users', [UserController::class, 'store'])->name('store');
        Route::get('users/{user}', [UserController::class, 'show'])->name('show');
        Route::patch('users/{user}', [UserController::class, 'update'])->name('update');
        Route::delete('users/{user}', [UserController::class, 'destroy'])->name('destroy');
    });
});
