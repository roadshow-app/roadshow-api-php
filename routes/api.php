<?php

use App\Exceptions\Errors\ErrorRouteNotFound;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserController;
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

Route::group(['middleware' => 'api'], function () {
    logApiCall();

    Route::get('', [Controller::class, 'hello']);

    Route::post('login', [UserController::class, 'login']);
    Route::post('register', [UserController::class, 'register']);
    Route::get('user', [UserController::class, 'index']);
    Route::patch('user', [UserController::class, 'update']);
    Route::delete('user', [UserController::class, 'destroy']);

    Route::post('company', [CompanyController::class, 'create']);
    Route::get('company/{id}', [CompanyController::class, 'show']);
    Route::patch('company', [CompanyController::class, 'update']);
    Route::delete('company', [CompanyController::class, 'destroy']);






    Route::fallback(function(){
        return errorResponse(new ErrorRouteNotFound, 404);
    });
});
