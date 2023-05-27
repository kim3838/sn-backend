<?php

use Illuminate\Http\Request;
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

    Route::get('user', [\App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'me']);
});

Route::group([
    'middleware' => ['auth:sanctum'],
    'prefix' => 'v1'
], function(){

    Route::get('request', [\App\Http\Controllers\Api\BaseController::class, 'fractalResponse']);

    Route::resource('users',    \App\Http\Controllers\Api\UserController::class);
});
