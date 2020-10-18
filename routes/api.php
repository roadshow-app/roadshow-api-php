<?php

use App\Exceptions\Errors\ErrorRouteNotFound;
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

    Route::get('', function () {
        return response()->json('hoi :D', 200);
    });

    Route::fallback(function(){
        return errorResponse(new ErrorRouteNotFound, 404);
    });
});
