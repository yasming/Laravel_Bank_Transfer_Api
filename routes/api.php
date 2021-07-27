<?php

use App\Http\Controllers\Api\Transfer\TransferController;
use App\Http\Middleware\IsUserMiddleware;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware([IsUserMiddleware::class])->group(function () {
    Route::post('/transfer', TransferController::class)->name('api.transfer');
});