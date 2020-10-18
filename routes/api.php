<?php

use App\Exceptions\Errors\ErrorRouteNotFound;
use App\Http\Controllers\Controller;
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

Route::prefix('')->group(function () {
    logApiCall();



    Route::get('', [Controller::class, 'hello']);

    Route::fallback(function(){
        return errorResponse(new ErrorRouteNotFound, 404);
    });
});
