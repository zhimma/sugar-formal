<?php

use App\Http\Controllers\api\SetAutoBanController;
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

Route::apiResource('cfp', 'api\CfpController');
Route::apiResource('SetAutoBan', 'api\SetAutoBanController');
Route::post('SetAutoBan/delete', [SetAutoBanController::class, 'destroy']);
Route::post('SetAutoBan/update', [SetAutoBanController::class, 'update']);
