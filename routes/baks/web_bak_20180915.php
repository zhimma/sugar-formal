<<<<<<< HEAD
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a given Closure or controller and enjoy the fresh air.
|
*/


/*
|--------------------------------------------------------------------------
| All caches
|--------------------------------------------------------------------------
|
| The section is to clear something that we want to use shared hosting but
| it can't normally show we expected.
| Here we need to clear cache using web route.
|
*/

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

/*
|--------------------------------------------------------------------------
| Error Handler Redirect Page
|--------------------------------------------------------------------------
*/

Route::get('/error', 'PagesController@error');

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/

Route::get('/', 'PagesController@home');
Route::get('/notification', 'PagesController@notification');
Route::get('/feature', 'PagesController@feature');
Route::get('/terms', 'PagesController@terms');
Route::get('/contact', 'PagesController@contact');
Route::get('/banned', 'PagesController@banned')->name('banned');

/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

/*
|--------------------------------------------------------------------------
| Registration & Activation
|--------------------------------------------------------------------------
*/
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

Route::get('/activate/token/{token}', 'Auth\ActivateController@activate');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/activate', 'Auth\ActivateController@showActivate');
    Route::get('/activate/send-token', 'Auth\ActivateController@sendToken');
});


/*
|--------------------------------------------------------------------------
| Vip Free Activation
|--------------------------------------------------------------------------
*/
//Route::post('/dashboard/activatefree', ['uses' => 'PagesController@activate', 'as' => 'activate']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'active', 'femaleActive']], function () {

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    Route::get('/users/switch-back', 'Admin\UserController@switchUserBack')->name('escape');

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/user', 'namespace' => 'User'], function () {
        //Route::get('settings', 'SettingsController@settings');
        Route::get('password', 'PasswordController@password');
        Route::post('password', 'PasswordController@update');
    });

    Route::get('/user/view/{uid}', 'PagesController@viewuser');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::post('/dashboard', 'PagesController@profileUpdate');
    Route::post('dashboard/settings', 'PagesController@settingsUpdate');
    Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');
    Route::get('/dashboard/cancel', 'PagesController@showCheckAccount');
    Route::post('/dashboard/cancelpay', 'PagesController@cancelpay');
    Route::post('/dashboard/chat', 'MessageController@postChat');
    Route::post('/dashboard/chatpay', 'PagesController@postChatpay')->name('chatpay');
    Route::post('/dashboard/chatpaycomment', 'PagesController@postChatpayComment')->name('chatpaycomment');
    Route::post('/dashboard/header/{admin?}', 'ImageController@resizeImagePostHeader');
    Route::post('/dashboard/image/{admin?}', 'ImageController@resizeImagePost');
    Route::post('/dashboard/imagedel/{admin?}', 'ImageController@deleteImage');
    Route::post('/dashboard/block', 'PagesController@postBlock');
    Route::post('/dashboard/unblock', 'PagesController@unblock');
    Route::post('/dashboard/fav', 'PagesController@postfav');
    Route::post('/dashboard/report', 'PagesController@report');
    Route::post('/dashboard/reportNext', 'PagesController@reportNext')->name('reportNext');
    Route::post('/dashboard/upgradepay', 'PagesController@upgradepay');
    Route::post('/dashboard/deleteboard', 'BoardController@deleteBoard')->name('deleteBoard');

    Route::group(['middleware' => ['vipc']], function () {
        Route::post('/dashboard/board', 'PagesController@postBoard');
        Route::get('/dashboard/history', 'PagesController@history');
        Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/fav', 'PagesController@fav');
    });

    Route::group(['middleware' => ['filled']], function () {

        Route::get('/dashboard/board', 'PagesController@board');
        //Route::get('/dashboard/history', 'PagesController@history');
        //Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/upgradesuccess', 'PagesController@upgradesuccess');
        Route::get('/dashboard/search', 'PagesController@search');
        Route::get('/dashboard/chat', 'MessageController@chatview');
        Route::get('/dashboard/chat/{cid}', 'PagesController@chat');

        // delete message
//        Route::get('/dashboard/chat/deleterow/{uid}/{sid}', ['uses' => 'MessageController@deleteBetween', 'as' => 'deleteBetween']);
//        Route::get('/dashboard/chat/deleteall/{uid}', ['uses' => 'MessageController@deleteAll', 'as' => 'deleteAll']);
//        Route::get('/dashboard/chat/deletesingle/{uid}/{sid}/{ct_time}/{content}', ['uses' => 'MessageController@deleteSingle', 'as' => 'deleteSingle']);
        Route::post('/dashboard/chat/deleterow', 'MessageController@deleteBetween')->name('deleteBetween');
        Route::post('/dashboard/chat/deleteall', 'MessageController@deleteAll')->name('deleteAll');
        Route::post('/dashboard/chat/deletesingle', 'MessageController@deleteSingle')->name('deleteSingle');
        Route::post('/dashboard/chat/reportMessage', 'MessageController@reportMessage')->name('reportMessage');
        Route::get('/dashboard/chat/reportMessage/{id}/{sid}', 'MessageController@showReportMessagePage')->name('reportMessagePage');
        //Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/upgrade', 'PagesController@upgrade');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {

        Route::get('dashboard', 'DashboardController@index');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::resource('manager', 'UserController', ['except' => ['create', 'show']]);
        Route::post('users/search', 'UserController@search')->name('users/manager');
        Route::get('users/search', 'UserController@index')->name('users/manager');
        Route::post('users/advSearch', 'UserController@advSearch')->name('users/advSearch');
        Route::get('users/advSearch', 'UserController@advIndex');
        Route::get('users/advInfo/{id}', 'UserController@advInfo')->name('users/advInfo');
        Route::get('users/advInfo/edit/{id}', 'UserController@advInfo');
        Route::post('users/advInfo/edit/{id}', 'UserController@saveAdvInfo')->name('users/save');
        Route::post('users/toggleUserBlock', 'UserController@toggleUserBlock');
        Route::get('users/toggleUserBlock/{id}', 'UserController@toggleUserBlock_simple')->name('toggleUserBlock');
        Route::post('users/userUnblock', 'UserController@userUnblock');
        Route::get('users/banUserWithDayAndMessage/{user_id}/{msg_id}/{days?}', 'UserController@banUserWithDayAndMessage')->name('banUserWithDayAndMessage');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures');
        Route::post('users/pictures', 'UserController@searchUserPictures')->name('users/pictures');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify');
        Route::get('users/message/to/{id}', 'UserController@showAdminMessenger');
        Route::get('users/message/to/{id}/{mid}', 'UserController@showAdminMessengerWithMessageId')->name('AdminMessengerWithMessageId');
        Route::get('users/message/unreported/to/{id}/{mid}', 'UserController@showAdminMessengerWithReportedId')->name('AdminMessengerWithReportedId');
        Route::post('users/message/send/{id}', 'UserController@sendAdminMessage')->name('admin/send');
        Route::post('users/message/multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple');
        Route::get('users/message/search', 'UserController@showMessageSearchPage')->name('users/message/search');
        Route::get('users/message/search/reported', 'UserController@showReportedMessages')->name('users/message/search/reported');
        Route::post('users/message/search', 'UserController@searchMessage');
        Route::post('users/message/modify', 'UserController@modifyMessage')->name('users/message/modify');
        Route::post('users/message/delete', 'UserController@deleteMessage')->name('users/message/delete');
        Route::post('users/message/edit', 'UserController@editMessage')->name('users/message/edit');
        Route::get('users/bannedList', 'UserController@showBannedList')->name('users/bannedList');
        Route::get('users/reported', 'UserController@showReportedUsersPage')->name('users/reported');
        Route::post('users/reported', 'UserController@showReportedUsersList')->name('users/reported');
        Route::get('users/switch', 'UserController@showUserSwitch')->name('users/switch');
        Route::post('users/switch', 'UserController@switchSearch')->name('users/switch/search');
        Route::get('users/invite', 'UserController@getInvite');
        Route::get('users/switch/{id}', 'UserController@switchToUser')->name('users/switch/to');
        Route::post('users/invite', 'UserController@postInvite');
        Route::post('users/genderToggler', 'UserController@toggleGender');
        Route::post('users/VIPToggler', 'UserController@toggleVIP');
        Route::get('announcement', 'UserController@showAdminAnnouncement')->name('admin/announcement');
        Route::post('announcement/save', 'UserController@saveAdminAnnouncement')->name('admin/announcement/save');
        Route::get('/chat', 'MessageController@chatview')->name('admin/chat');
        Route::get('/chat/{cid}', 'PagesController@chat');
        Route::post('/chat', 'MessageController@postChat');

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', 'RoleController', ['except' => ['show']]);
        Route::post('roles/search', 'RoleController@search');
        Route::get('roles/search', 'RoleController@index');
    });
});
=======
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a given Closure or controller and enjoy the fresh air.
|
*/


/*
|--------------------------------------------------------------------------
| All caches
|--------------------------------------------------------------------------
|
| The section is to clear something that we want to use shared hosting but
| it can't normally show we expected.
| Here we need to clear cache using web route.
|
*/

//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

/*
|--------------------------------------------------------------------------
| Error Handler Redirect Page
|--------------------------------------------------------------------------
*/

Route::get('/error', 'PagesController@error');

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/

Route::get('/', 'PagesController@home');
Route::get('/notification', 'PagesController@notification');
Route::get('/feature', 'PagesController@feature');
Route::get('/terms', 'PagesController@terms');
Route::get('/contact', 'PagesController@contact');
Route::get('/banned', 'PagesController@banned')->name('banned');

/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

// Password Reset Routes...
Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset', 'Auth\ResetPasswordController@reset');

/*
|--------------------------------------------------------------------------
| Registration & Activation
|--------------------------------------------------------------------------
*/
Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

Route::get('/activate/token/{token}', 'Auth\ActivateController@activate');

Route::group(['middleware' => ['auth']], function () {

    Route::get('/activate', 'Auth\ActivateController@showActivate');
    Route::get('/activate/send-token', 'Auth\ActivateController@sendToken');
});


/*
|--------------------------------------------------------------------------
| Vip Free Activation
|--------------------------------------------------------------------------
*/
//Route::post('/dashboard/activatefree', ['uses' => 'PagesController@activate', 'as' => 'activate']);

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['auth', 'active', 'femaleActive']], function () {

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    Route::get('/users/switch-back', 'Admin\UserController@switchUserBack')->name('escape');

    /*
    |--------------------------------------------------------------------------
    | User
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => '/user', 'namespace' => 'User'], function () {
        //Route::get('settings', 'SettingsController@settings');
        Route::get('password', 'PasswordController@password');
        Route::post('password', 'PasswordController@update');
    });

    Route::get('/user/view/{uid}', 'PagesController@viewuser');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::post('/dashboard', 'PagesController@profileUpdate');
    Route::post('dashboard/settings', 'PagesController@settingsUpdate');
    Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');
    Route::get('/dashboard/cancel', 'PagesController@showCheckAccount');
    Route::post('/dashboard/cancelpay', 'PagesController@cancelpay');
    Route::post('/dashboard/chat', 'MessageController@postChat');
    Route::post('/dashboard/chatpay', 'PagesController@postChatpay')->name('chatpay');
    Route::post('/dashboard/chatpaycomment', 'PagesController@postChatpayComment')->name('chatpaycomment');
    Route::post('/dashboard/header/{admin?}', 'ImageController@resizeImagePostHeader');
    Route::post('/dashboard/image/{admin?}', 'ImageController@resizeImagePost');
    Route::post('/dashboard/imagedel/{admin?}', 'ImageController@deleteImage');
    Route::post('/dashboard/block', 'PagesController@postBlock');
    Route::post('/dashboard/unblock', 'PagesController@unblock');
    Route::post('/dashboard/fav', 'PagesController@postfav');
    Route::post('/dashboard/report', 'PagesController@report');
    Route::post('/dashboard/reportNext', 'PagesController@reportNext')->name('reportNext');
    Route::post('/dashboard/upgradepay', 'PagesController@upgradepay');
    Route::post('/dashboard/deleteboard', 'BoardController@deleteBoard')->name('deleteBoard');

    Route::group(['middleware' => ['vipc']], function () {
        Route::post('/dashboard/board', 'PagesController@postBoard');
        Route::get('/dashboard/history', 'PagesController@history');
        Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/fav', 'PagesController@fav');
    });

    Route::group(['middleware' => ['filled']], function () {

        Route::get('/dashboard/board', 'PagesController@board');
        //Route::get('/dashboard/history', 'PagesController@history');
        //Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/upgradesuccess', 'PagesController@upgradesuccess');
        Route::get('/dashboard/search', 'PagesController@search');
        Route::get('/dashboard/chat', 'MessageController@chatview');
        Route::get('/dashboard/chat/{cid}', 'PagesController@chat');

        // delete message
//        Route::get('/dashboard/chat/deleterow/{uid}/{sid}', ['uses' => 'MessageController@deleteBetween', 'as' => 'deleteBetween']);
//        Route::get('/dashboard/chat/deleteall/{uid}', ['uses' => 'MessageController@deleteAll', 'as' => 'deleteAll']);
//        Route::get('/dashboard/chat/deletesingle/{uid}/{sid}/{ct_time}/{content}', ['uses' => 'MessageController@deleteSingle', 'as' => 'deleteSingle']);
        Route::post('/dashboard/chat/deleterow', 'MessageController@deleteBetween')->name('deleteBetween');
        Route::post('/dashboard/chat/deleteall', 'MessageController@deleteAll')->name('deleteAll');
        Route::post('/dashboard/chat/deletesingle', 'MessageController@deleteSingle')->name('deleteSingle');
        Route::post('/dashboard/chat/reportMessage', 'MessageController@reportMessage')->name('reportMessage');
        Route::get('/dashboard/chat/reportMessage/{id}/{sid}', 'MessageController@showReportMessagePage')->name('reportMessagePage');
        //Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/upgrade', 'PagesController@upgrade');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'admin'], function () {

        Route::get('dashboard', 'DashboardController@index');

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        Route::resource('manager', 'UserController', ['except' => ['create', 'show']]);
        Route::post('users/search', 'UserController@search')->name('users/manager');
        Route::get('users/search', 'UserController@index')->name('users/manager');
        Route::post('users/advSearch', 'UserController@advSearch')->name('users/advSearch');
        Route::get('users/advSearch', 'UserController@advIndex');
        Route::get('users/advInfo/{id}', 'UserController@advInfo')->name('users/advInfo');
        Route::get('users/advInfo/edit/{id}', 'UserController@advInfo');
        Route::post('users/advInfo/edit/{id}', 'UserController@saveAdvInfo')->name('users/save');
        Route::post('users/toggleUserBlock', 'UserController@toggleUserBlock');
        Route::get('users/toggleUserBlock/{id}', 'UserController@toggleUserBlock_simple')->name('toggleUserBlock');
        Route::post('users/userUnblock', 'UserController@userUnblock');
        Route::get('users/banUserWithDayAndMessage/{user_id}/{msg_id}/{days?}', 'UserController@banUserWithDayAndMessage')->name('banUserWithDayAndMessage');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures');
        Route::post('users/pictures', 'UserController@searchUserPictures')->name('users/pictures');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify');
        Route::get('users/message/to/{id}', 'UserController@showAdminMessenger');
        Route::get('users/message/to/{id}/{mid}', 'UserController@showAdminMessengerWithMessageId')->name('AdminMessengerWithMessageId');
        Route::get('users/message/unreported/to/{id}/{mid}', 'UserController@showAdminMessengerWithReportedId')->name('AdminMessengerWithReportedId');
        Route::post('users/message/send/{id}', 'UserController@sendAdminMessage')->name('admin/send');
        Route::post('users/message/multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple');
        Route::get('users/message/search', 'UserController@showMessageSearchPage')->name('users/message/search');
        Route::get('users/message/search/reported', 'UserController@showReportedMessages')->name('users/message/search/reported');
        Route::post('users/message/search', 'UserController@searchMessage');
        Route::post('users/message/modify', 'UserController@modifyMessage')->name('users/message/modify');
        Route::post('users/message/delete', 'UserController@deleteMessage')->name('users/message/delete');
        Route::post('users/message/edit', 'UserController@editMessage')->name('users/message/edit');
        Route::get('users/bannedList', 'UserController@showBannedList')->name('users/bannedList');
        Route::get('users/reported', 'UserController@showReportedUsersPage')->name('users/reported');
        Route::post('users/reported', 'UserController@showReportedUsersList')->name('users/reported');
        Route::get('users/switch', 'UserController@showUserSwitch')->name('users/switch');
        Route::post('users/switch', 'UserController@switchSearch')->name('users/switch/search');
        Route::get('users/invite', 'UserController@getInvite');
        Route::get('users/switch/{id}', 'UserController@switchToUser')->name('users/switch/to');
        Route::post('users/invite', 'UserController@postInvite');
        Route::post('users/genderToggler', 'UserController@toggleGender');
        Route::post('users/VIPToggler', 'UserController@toggleVIP');
        Route::get('announcement', 'UserController@showAdminAnnouncement')->name('admin/announcement');
        Route::post('announcement/save', 'UserController@saveAdminAnnouncement')->name('admin/announcement/save');
        Route::get('/chat', 'MessageController@chatview')->name('admin/chat');
        Route::get('/chat/{cid}', 'PagesController@chat');
        Route::post('/chat', 'MessageController@postChat');

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', 'RoleController', ['except' => ['show']]);
        Route::post('roles/search', 'RoleController@search');
        Route::get('roles/search', 'RoleController@index');
    });
});
>>>>>>> simon_foreign_area
