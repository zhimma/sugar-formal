<?php

use App\Http\Controllers\Admin\PagesController;
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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['api', 'appGlobal'], 'prefix' => 'auth'], function ($router) {
    // 個人資訊
    Route::get('/', 'AuthController@me');
    // 登入
    Route::post('login', 'AuthController@login');
    // 註冊
    Route::post('register', 'AuthController@register');
    // 登出
    Route::post('logout', 'AuthController@logout');
    // 搜尋會員
    Route::get('search', 'AuthController@search');
    // 取得縣市區資料
    Route::get('districts', 'AuthController@districts');
    // 查看目標會員資料
    Route::get('viewuser/{uid?}', 'AuthController@viewuser');
    // 顯示所有會員傳給自己的訊息
    Route::get('showMessages/{type?}', 'AuthController@chatviewMore');
    // 顯示某個會員傳給自己的所有訊息
    Route::get('chatShow/{cid}', 'AuthController@chat2');
    // 對某個會員傳訊息
    Route::post('chat', 'AuthController@postChat');
    // 刪除對某會員之訊息
    Route::post('chat/deletesingle', 'AuthController@deleteSingle');
    // 封鎖某個會員
    Route::post('/chat/blockAJAX', 'AuthController@postBlockAJAX');
    // 解除封鎖某個會員
    Route::post('/chat/unblockAJAX', 'AuthController@unblockAJAX');
    // 刪除某個會員訊息
    Route::get('/chat/deleterow/{uid}/{sid}', 'AuthController@deleteBetweenGET');
    // 刪除某個會員所有訊息
    Route::get('/chat/deleterowall/{uid}/{sid}', 'AuthController@deleteBetweenGetAll');
    // 收藏會員
    Route::post('/chat/postfavAJAX', 'AuthController@postfavAJAX');
    // 移除收藏會員
    Route::post('/chat/removefavAJAX', 'AuthController@removefavAJAX');
    // 檢舉會員
    Route::post('/chat/reportPost', 'AuthController@reportPost');
    // 設定信息通知/收信設定
    Route::post('/chat/chatSet', 'AuthController@chatSet');
    // 新增對某會員的評價
    Route::post('/chat/evaluation/save', 'AuthController@evaluationSave');
    // 取得某會員所有評價資料
    Route::get('/chat/evaluation/{uid}', 'AuthController@evaluation');
    // 刪除某會員評價資料
    Route::post('/chat/evaluation/delete', 'AuthController@evaluationDelete');
    // 檢舉大頭照/照片
    Route::post('/chat/reportPic', 'AuthController@reportPic');
    // 檢舉訊息
    Route::post('/chat/reportMsg', 'AuthController@reportMsg');
    // 判斷是否需要註冊功能
    Route::get('registerMode', 'AuthController@registerMode');
    // 略過
    Route::post('/search_discard/add', 'AuthController@addSearchIgnore');
    // 解除略過
    Route::get('/search_discard/del', 'AuthController@delSearchIgnore');
    // 收回
    Route::post('/chat/unsend', 'AuthController@unsendChat');
    // 會員專屬
    Route::get('/personal', 'AuthController@personal');
});

Route::apiResource('cfp', 'Api\CfpController');
Route::apiResource('SetAutoBan', 'Api\SetAutoBanController');
Route::post('SetAutoBan/delete', [SetAutoBanController::class, 'destroy']);
Route::post('SetAutoBan/update', [SetAutoBanController::class, 'update']);
Route::post('getAutoBanedCheck', [SetAutoBanController::class, 'getAutoBanedCheck']);
Route::post('tmpNotify', "PagesController@tmpNotify");
