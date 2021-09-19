<?php

use App\Http\Controllers\Api\SetAutoBanController;
use Illuminate\Http\Request;

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

Route::apiResource('cfp', 'Api\CfpController');
Route::apiResource('SetAutoBan', 'Api\SetAutoBanController');
Route::post('SetAutoBan/delete', [SetAutoBanController::class, 'destroy']);
Route::post('SetAutoBan/update', [SetAutoBanController::class, 'update']);
