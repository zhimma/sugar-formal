<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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
Route::get('/sftp-test', function(){
    $date = \Carbon\Carbon::now()->subDay()->toDateString();
    $date = str_replace('-', '', $date);
    if(file_exists(storage_path('app/RP_761404_'.$date.'.dat'))){
        $fileContent = file_get_contents(storage_path('app/RP_761404_'.$date.'.dat'));
        $destinDate = \Carbon\Carbon::now()->toDateString();
        $destinDate = str_replace('-', '', $destinDate);
        $file = 'RP_761404_'.$destinDate.'.dat';
        GrahamCampbell\Flysystem\Facades\Flysystem::connection('sftp')->put($file, $fileContent);

        \DB::table('log_dat_file')->insert(
            ['upload_check' => 0,
                'local_file'    => 'RP_761404_'.$date.'.dat',
                'remote_file' => $file,
                'content' => "RP_761404_".$date.".dat: Upload completed."]
        );
        return "RP_761404_".$date."dat: Upload completed.";
    }
    else{
        \DB::table('log_dat_file')->insert(
            ['upload_check' => 0,
                'local_file'    => 'RP_761404_'.$date.'.dat',
                'remote_file' => '',
                'content' => "File RP_761404_".$date.".dat not found, upload process didn't initiate."]
        );
        return "File not found, upload process didn't initiate.";
    }
});

Route::get('/sftp-check-test', function(){
    $localDate = \Carbon\Carbon::now()->subDay()->toDateString();
    $localDate = str_replace('-', '', $localDate);
    if(file_exists(storage_path('app/RP_761404_'.$localDate.'.dat'))){
        $localFileContent = file_get_contents(storage_path('app/RP_761404_'.$localDate.'.dat'));
        $remoteDate = \Carbon\Carbon::now()->toDateString();
        $remoteDate = str_replace('-', '', $remoteDate);
        $remoteFile = 'RP_761404_'.$remoteDate.'.dat';
        $remoteFileContent = GrahamCampbell\Flysystem\Facades\Flysystem::connection('sftp')->read($remoteFile);
        if($localFileContent == $remoteFileContent){
            \DB::table('log_dat_file')->insert(
                ['upload_check' => 1,
                    'local_file'    => 'RP_761404_'.$localDate.'.dat',
                    'remote_file' => $remoteFile,
                    'content' => "File comparison success."]
            );
            return "File comparison success.";
        }
        else{
            \DB::table('log_dat_file')->insert(
                ['upload_check' => 1,
                    'local_file'    => 'RP_761404_'.$localDate.'.dat',
                    'remote_file' => $remoteFile,
                    'content' => "File comparison failed."]
            );
            return "File comparison failed.";
        }
    }
    else{
        \DB::table('log_dat_file')->insert(
            ['upload_check' => 1,
                'local_file'    => 'RP_761404_'.$localDate.'.dat',
                'remote_file' => '',
                'content' => "Local file not found, check process didn't initiate."]
        );
        return "Local file not found, check process didn't initiate.";
    }
});
Route::get('/fingerprint', 'PagesController@fingerprint');
Route::post('/saveFingerprint', 'PagesController@saveFingerprint')->name('saveFingerprint');


/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::post('/Common/get_message', 'Common@get_message');
Route::post('/Common/checkcode_during', 'Common@checkcode_during');
Route::get('/Common/get_exif', 'Common@get_exif');
Route::post('/Common/upload_img', 'Common@upload_img');


/*
|--------------------------------------------------------------------------
| Error Handler Redirect Page
|--------------------------------------------------------------------------
*/

Route::get('/error', 'PagesController@error');

/*
 * cd1 cd2 ts1 ts2
 */
Route::get('/cd_1', 'PagesController@cd_1');
Route::get('/cd_2', 'PagesController@cd_2');
Route::get('/ts_1', 'PagesController@ts_1');
Route::get('/ts_2', 'PagesController@ts_2');

/*
|--------------------------------------------------------------------------
| Welcome Page
|--------------------------------------------------------------------------
*/
Route::get('/passwd', 'passwd@passwd');
Route::get('/', 'PagesController@home');
Route::get('/privacy', 'PagesController@privacy');
Route::get('/notification', 'PagesController@notification');
Route::get('/feature', 'PagesController@feature');
Route::get('/about', 'PagesController@about');
Route::get('/dashboard/browse', 'PagesController@browse');
Route::get('/terms', 'PagesController@terms');
Route::get('/contact', 'PagesController@contact');
Route::get('/buyAvip', function (){return view('dashboard.buyAvip');});
Route::get('/banned', 'PagesController@banned')->name('banned');

/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@showLoginForm2')->name('login');
Route::get('/login2', 'Auth\LoginController@showLoginForm')->name('login2');
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
Route::get('/checkAdult', 'Auth\RegisterController@checkAdult');
Route::get('/register', 'Auth\RegisterController@showRegistrationForm2')->name('register');
Route::get('/register2', 'Auth\RegisterController@showRegistrationForm')->name('register2');
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

Route::group(['middleware' => ['auth', 'active', 'femaleActive', 'vipCheck']], function () {


    Route::get('Fingerprint', 'Fingerprint@index');
    Route::post('Fingerprint/addFingerprint', 'Fingerprint@addFingerprint');

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    Route::get('/users/switch-back', 'Admin\UserController@switchUserBack')->name('escape');
    Route::post('/message/disableNotice', 'MessageController@disableNotice')->name('disableNotice');
    Route::post('/users/announceRead', 'MessageController@announceRead')->name('announceRead');

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

    Route::get('/user/view/{uid?}', 'PagesController@viewuser');
    //Route::get('/user/view2/{uid?}', 'PagesController@viewuser2'); //new route


    /*新切版*/
    Route::get('new/mem_member', 'PagesController@mem_member');
    Route::get('new/mem_member/{id}', 'PagesController@mem_member');
    Route::get('new/mem_search', 'PagesController@mem_search');
    Route::post('town_ajax', 'PagesController@town_ajax');
    Route::post('searchData', 'PagesController@searchData');
    Route::post('updateMemberData', 'PagesController@updateMemberData');
    
    Route::get('new/mem_updatevip', 'PagesController@mem_updatevip');
    Route::post('cancelVip', 'PagesController@cancelVip');
    Route::get('new/women_updatevip', 'PagesController@women_updatevip');
    Route::get('new/women_search', 'PagesController@women_search');


    Route::post('addReportAvatar', 'PagesController@addReportAvatar');
    Route::post('addMessage', 'PagesController@addMessage');
    Route::post('addCollection', 'PagesController@addCollection');
    Route::post('addReport', 'PagesController@addReport');
    Route::post('addBlock', 'PagesController@addBlock');

    /*會員驗證*/
    Route::get('member_auth_phone', 'PagesController@member_auth_phone');
    Route::post('member_auth_phone_process', 'PagesController@member_auth_phone_process');
    Route::get('member_auth_photo', 'PagesController@member_auth_photo');

    Route::get('hint_auth1', 'PagesController@hint_auth1');
    Route::get('hint_auth2', 'PagesController@hint_auth2');


    /*會員驗證END*/

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::post('/dashboard', 'PagesController@profileUpdate');
    Route::post('/dashboard2', 'PagesController@profileUpdate_ajax');
    Route::post('dashboard/settings', 'PagesController@settingsUpdate');
    Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');
    Route::get('/dashboard_img', 'PagesController@dashboard_img')->name('dashboard_img');
    Route::post('/dashboard/save_img','PagesController@save_img');
    Route::post('/dashboard/delPic', 'PagesController@delPic');
    // Route::get('/dashboard_img_new', 'PagesController@dashboard_img')->name('dashboard_img');
    Route::get('/dashboard/password', 'PagesController@view_changepassword'); //new route
    Route::post('/dashboard/changepassword', 'PagesController@changePassword'); //new route
    Route::get('/dashboard/vip', 'PagesController@view_vip'); //new route
    Route::get('/dashboard2', 'PagesController@dashboard2')->name('dashboard2');
    Route::get('/dashboard/cancel', 'PagesController@showCheckAccount');
    Route::post('/dashboard/chat', 'MessageController@postChat');
    Route::post('/dashboard/chatpay', 'PagesController@postChatpay')->name('chatpay');
    Route::post('/dashboard/chatpayLog', 'PagesController@postChatpayLog')->name('chatpayLog');
    Route::post('/dashboard/chatpaycomment', 'PagesController@postChatpayComment')->name('chatpaycomment');
    Route::post('/dashboard/header/{admin?}', 'ImageController@resizeImagePostHeader');
    Route::post('/dashboard/header2/{admin?}', 'ImageController@resizeImagePostHeader2');
    Route::post('/fileuploader_image_upload', 'ImageController@fileuploader_image_upload')->name('fileuploader_image_upload');
    Route::post('/dashboard/image/{admin?}', 'ImageController@resizeImagePost');
    Route::post('/dashboard/imagedel/{admin?}', 'ImageController@deleteImage');
    Route::post('/dashboard/block', 'PagesController@postBlock');
    Route::post('/dashboard/blockAJAX', 'PagesController@postBlockAJAX')->name('postBlockAJAX');//new route
    Route::post('/dashboard/unblock', 'PagesController@unblock');
    Route::post('/dashboard/unblockajax', 'PagesController@unblockAJAX')->name('unblockAJAX'); //new route
    Route::post('/dashboard/unblockAll', 'PagesController@unblockAll')->name('unblockAll'); //new route
    Route::post('/dashboard/fav', 'PagesController@postfav');
    Route::post('/dashboard/poatfavajax', 'PagesController@postfavAJAX')->name('postfavAJAX');//new route
    Route::post('/dashboard/fav_ajax', 'PagesController@fav_ajax')->name('showfav');//新樣板route
    Route::post('/dashboard/fav/remove', 'PagesController@removeFav')->name('fav/remove');
    Route::post('/dashboard/fav/remove_ajax', 'PagesController@removeFav_ajax')->name('fav/remove_ajax');//新樣板route
    Route::post('/dashboard/report', 'PagesController@report');
    Route::post('/dashboard/reportNext', 'PagesController@reportNext')->name('reportNext');
    Route::post('/dashboard/reportPost', 'PagesController@reportPost')->name('reportPost');
    Route::get('/dashboard/reportPic/{user}/{id}/{uid?}', 'PagesController@reportPic')->name('reportPic');
    Route::post('/dashboard/reportPicNext', 'PagesController@reportPicNext')->name('reportPicNext');
    Route::post('/dashboard/reportPicNextNew', 'PagesController@reportPicNextNew')->name('reportPicNextNew'); //new route
    Route::get('/dashboard/upgrade_ec', 'PagesController@upgrade_ec');
    Route::get('/dashboard/upgrade_esafe', 'PagesController@upgrade_esafe');
    Route::get('/dashboard/announcement', 'PagesController@showAnnouncement');
    Route::group(['middleware' => ['api']], function() {
        Route::post('/dashboard/upgradepay_ec', 'ECPayment@performPayment')->name('upgradepay_ec');
        Route::post('/dashboard/esafeCreditCard', 'EsafePayment@esafeCreditCard')->name('esafeCreditCard');
        Route::post('/dashboard/esafePayment', 'EsafePayment@esafePayment')->name('esafePayment');
        Route::post('/dashboard/esafePayCode', 'EsafePayment@esafePayCode')->name('esafePayCode');
        Route::post('/dashboard/esafeWebATM', 'EsafePayment@esafeWebATM')->name('esafeWebATM');
        Route::post('/dashboard/upgradepay', 'PagesController@upgradepay');
        Route::post('/dashboard/upgradepayEC', 'PagesController@upgradepayEC');
        Route::post('/dashboard/receive_esafe', 'PagesController@receive_esafe');
        Route::post('/dashboard/repaid_esafe', 'PagesController@repaid_esafe');
        Route::post('/dashboard/cancelpay', 'PagesController@cancelpay');
    });
    Route::group(['middleware' => ['tipApi']], function () {
        Route::post('/dashboard/chatpay_ec', 'ECPayment@performTipInvite')->name('chatpay_ec');
        Route::post('/dashboard/postChatpayEC', 'PagesController@postChatpayEC');
    });
    Route::post('/upgradepayLog', 'PagesController@upgradepayLog')->name('upgradepayLog');
    Route::post('/dashboard/deleteboard', 'BoardController@deleteBoard')->name('deleteBoard');

    Route::group(['middleware' => ['vipc']], function () {
        Route::post('/dashboard/board', 'PagesController@postBoard');
        Route::get('/dashboard/history', 'PagesController@history');
        Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/fav2', 'PagesController@fav2');
    });

    Route::group(['middleware' => ['filled']], function () {

        //新樣板
        Route::get('/dashboard/chat2/{randomNo?}', 'Message_newController@chatview')->name('chatView');
        Route::post('/dashboard/chat2/showMessages/{randomNo?}', 'Message_newController@chatviewMore')->name('showMessages');
        Route::get('/dashboard/chat2/chatShow/{cid}', 'PagesController@chat2')->name('chat2WithUser');
        Route::post('/dashboard/chat2/deletesingle', 'Message_newController@deleteSingle')->name('delete2Single');
        Route::post('/dashboard/chat2/{randomNo?}', 'Message_newController@postChat');
        Route::get('/dashboard/chat2/deleterow/{uid}/{sid}', 'Message_newController@deleteBetweenGET')->name('delete2BetweenGET');
        Route::post('/dashboard/chat2/deleteall', 'Message_newController@deleteAll')->name('delete2All');
        Route::post('/dashboard/chat2/chatSet', 'Message_newController@chatSet')->name('chatSet');
        Route::post('/dashboard/announcement_post', 'Message_newController@announcePost')->name('announcePost');

        Route::get('/dashboard/banned', 'PagesController@dashboard_banned');
        Route::get('/dashboard/visited', 'PagesController@visited');
        Route::get('/dashboard/viewuser/{uid?}', 'PagesController@viewuser2'); //new route

        Route::get('/dashboard/board', 'PagesController@board');
        //Route::get('/dashboard/history', 'PagesController@history');
        //Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/upgradesuccess', 'PagesController@upgradesuccess');
        //Route::get('/dashboard/search', 'PagesController@search');
        Route::get('/dashboard/search', 'PagesController@search2');//new route
        Route::get('/dashboard/search2', 'PagesController@search');
        Route::get('/dashboard/chat/{randomNo?}', 'MessageController@chatview')->name('chatView');
        Route::post('/dashboard/chat/showMoreMessages/{randomNo?}', 'MessageController@chatviewMore')->name('showMoreMessages');
        Route::post('/dashboard/chat/showAllMessages/{randomNo?}', 'MessageController@chatviewAll')->name('showAllMessages');
        Route::get('/dashboard/chatShow/{cid}', 'PagesController@chat')->name('chatWithUser');

        // delete message
        // Route::get('/dashboard/chat/deleteall/{uid}', ['uses' => 'MessageController@deleteAll', 'as' => 'deleteAll']);
        // Route::get('/dashboard/chat/deletesingle/{uid}/{sid}/{ct_time}/{content}', ['uses' => 'MessageController@deleteSingle', 'as' => 'deleteSingle']);
        Route::get('/dashboard/chat/deleterow/{uid}/{sid}', 'MessageController@deleteBetweenGET')->name('deleteBetweenGET');
        Route::post('/dashboard/chat/deleterow', 'MessageController@deleteBetween')->name('deleteBetween');
        Route::post('/dashboard/chat/deleteall', 'MessageController@deleteAll')->name('deleteAll');
        Route::get('/dashboard/chat/deleteall/{uid}', ['uses' => 'MessageController@deleteAll', 'as' => 'deleteAllGET']);
        Route::post('/dashboard/chat/deletesingle', 'MessageController@deleteSingle')->name('deleteSingle');
        Route::get('/dashboard/chat/deletesingle/{uid}/{sid}/{ct_time}/{content}', ['uses' => 'MessageController@deleteSingle', 'as' => 'deleteSingleGET']);
        Route::post('/dashboard/chat/reportMessage', 'MessageController@reportMessage')->name('reportMessage');
        Route::get('/dashboard/chat/reportMessage/{id}/{sid}', 'MessageController@showReportMessagePage')->name('reportMessagePage');
        //Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/upgrade', 'PagesController@upgrade');
   // Route::get('/dashboard/cancel', 'PagesController@cancel');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Admin', 'middleware' => 'ReadOnly'], function () {
        Route::match(['get', 'post'], 'users/VIP/ECCancellations/readOnly', 'PagesController@showECCancellations')->name('users/VIP/ECCancellations/readOnly');
    });
    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'Admin'], function () {
        Route::get('dashboard', 'DashboardController@index');
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        //Route::get('manualSQL', 'UserController@manualSQL');
        //Route::get('querier', 'UserController@querier')->name('querier');
        Route::resource('manager', 'UserController', ['except' => ['create', 'show']]);
        Route::post('users/search', 'UserController@search')->name('users/manager');
        Route::get('users/search', 'UserController@index')->name('users/manager');
        Route::post('users/advSearch', 'UserController@advSearch')->name('users/advSearch');
        Route::post('users/advSearchInfo', 'UserController@advSearchInfo')->name('users/advSearchInfo');
        Route::get('users/advSearch', 'UserController@advIndex');
        Route::get('users/advInfo/{id}', 'UserController@advInfo')->name('users/advInfo');
        Route::get('users/advInfo/edit/{id}', 'UserController@advInfo');
        Route::post('users/advInfo/edit/{id}', 'UserController@saveAdvInfo')->name('users/save');
        Route::post('users/toggleUserBlock', 'UserController@toggleUserBlock');
        Route::get('users/toggleUserBlock/{id}', 'UserController@toggleUserBlock_simple')->name('toggleUserBlock');
        Route::post('users/userUnblock', 'UserController@userUnblock');
        Route::get('users/banUserWithDayAndMessage/{user_id}/{msg_id}/{isReported?}', 'UserController@showBanUserDialog')->name('banUserWithDayAndMessage');
        Route::post('users/banUserWithDayAndMessage', 'UserController@banUserWithDayAndMessage');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures');
        Route::post('users/pictures', 'UserController@searchUserPictures')->name('users/pictures');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify');
        Route::get('users/reported/count', 'UserController@showReportedCountPage')->name('users/reported/count');
        Route::post('users/reported/count', 'UserController@showReportedCountList')->name('users/reported/count');
        Route::get('users/board', 'PagesController@board')->name('users/board');
        Route::post('users/board', 'PagesController@board')->name('users/board/search');
        Route::get('users/board/delete/{id}', 'UserController@deleteBoard')->name('users/board/delete');
        Route::get('users/message/showBetween/{id1}/{id2}', 'UserController@showMessagesBetween')->name('admin/showMessagesBetween');
        Route::get('users/message/to/{id}', 'UserController@showAdminMessenger');
        Route::get('users/message/to/{id}/{mid}', 'UserController@showAdminMessengerWithMessageId')->name('AdminMessengerWithMessageId');
        Route::get('users/message/unreported/to/{id}/{reported_id}/{pic_id?}/{isPic?}/{isReported?}', 'UserController@showAdminMessengerWithReportedId')->name('AdminMessengerWithReportedId');
        Route::post('users/message/send/{id}', 'UserController@sendAdminMessage')->name('admin/send');
        Route::post('users/message/multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple');
        Route::get('users/message/search', 'UserController@showMessageSearchPage')->name('users/message/search');
        Route::post('users/message/search', 'UserController@searchMessage');
        Route::post('users/message/modify', 'UserController@modifyMessage')->name('users/message/modify');
        Route::post('users/message/delete', 'UserController@deleteMessage')->name('users/message/delete');
        Route::post('users/message/edit', 'UserController@editMessage')->name('users/message/edit');
        Route::get('users/pics/reported', 'UserController@showReportedPicsPage')->name('users/pics/reported');
        Route::get('users/reported', 'UserController@showReportedUsersPage')->name('users/reported');
        Route::post('users/reported', 'UserController@showReportedUsersList')->name('users/reported');
        Route::post('users/reported/details/{reported_id}/{users?}/{reportedData?}', 'UserController@showReportedDetails')->name('users/reported/details');
        //曾被檢舉
        Route::get('users/pics/reported/{date_start?}/{date_end?}/{reported_id?}', 'UserController@searchReportedPics')->name('users/pics/reported');
        Route::get('users/reported/{date_start?}/{date_end?}/{reported_id?}', 'UserController@showReportedUsersList')->name('users/reported');
        Route::get('users/message/search/reported/{date_start?}/{date_end?}/{reported_id?}', 'UserController@showReportedMessages')->name('users/message/search/reported');

        Route::post('users/pics/reported', 'UserController@searchReportedPics')->name('users/pics/reported');
        Route::get('users/basic_setting', 'UserController@basicSetting')->name('users/basic_setting');
        Route::post('users/basic_setting', 'UserController@doBasicSetting')->name('users/basic_setting');
        Route::get('users/bannedList', 'UserController@showBannedList')->name('users/bannedList');
        Route::get('users/switch', 'UserController@showUserSwitch')->name('users/switch');
        Route::post('users/switch', 'UserController@switchSearch')->name('users/switch/search');
        Route::get('users/changePassword', 'UserController@changePassword')->name('users/changePassword');
        Route::post('users/changePassword', 'UserController@changePassword')->name('users/changePassword');
        Route::get('users/invite', 'UserController@getInvite');
        Route::get('users/switch/{id}', 'UserController@switchToUser')->name('users/switch/to');
        Route::post('users/invite', 'UserController@postInvite');
        Route::post('users/genderToggler', 'UserController@toggleGender');
        Route::post('users/VIPToggler', 'UserController@toggleVIP');
        Route::post('users/RecommendedToggler', 'UserController@toggleRecommendedUser');
        Route::get('users/customizeMigrationFiles', 'UserController@customizeMigrationFiles')->name('users/customize_migration_files');
        Route::post('users/customizeMigrationFiles', 'UserController@customizeMigrationFiles')->name('users/customize_migration_files');
        Route::match(['get', 'post'], 'users/VIP/ECCancellations', 'PagesController@showECCancellations')->name('users/VIP/ECCancellations');
        Route::get('announcement', 'UserController@showAdminAnnouncement')->name('admin/announcement');
        Route::get('announcement/edit/{id}', 'UserController@showAdminAnnouncementEdit')->name('admin/announcement/edit');
        Route::post('announcement/save', 'UserController@saveAdminAnnouncement')->name('admin/announcement/save');
        Route::get('announcement/delete/{id?}', 'UserController@deleteAdminAnnouncement')->name('admin/announcement/delete');
        Route::get('announcement/new', 'UserController@showNewAdminAnnouncement')->name('admin/announcement/new');
        Route::post('announcement/new', 'UserController@newAdminAnnouncement')->name('admin/announcement/new');
        Route::get('announcement/read/{id}', 'UserController@showReadAnnouncementUser')->name('admin/announcement/read');
        Route::get('web/announcement', 'UserController@showWebAnnouncement')->name('admin/web/announcement');
        Route::get('/chat', 'MessageController@chatview')->name('admin/chat');
        Route::get('/chat/{cid}', 'PagesController@chat');
        Route::post('/chat', 'MessageController@postChat');
        Route::get('commonetext', 'UserController@showAdminCommoneText')->name('admin/commonetext');
        Route::post('commonetext/save', 'UserController@saveAdminCommoneText')->name('admin/commonetext/save');
        Route::get('users/inactive', 'UserController@inactiveUsers')->name('inactive');
        Route::post('users/inactive', 'UserController@inactiveUsers')->name('inactive');
        Route::get('users/activate/token/{token}', 'UserController@activateUser')->name('activateUser');
        Route::get('stats/vip', 'StatController@vip')->name('stats/vip');
        Route::get('stats/vip_log/{id}', 'StatController@vipLog')->name('stats/vip_log');
        Route::get('stats/cron_log', 'StatController@cronLog')->name('stats/cron_log');
        Route::get('stats/date_file_log', 'StatController@datFileLog')->name('stats/date_file_log');

        /*新增、編輯訊息*/
        Route::post('users/getmsglib', 'UserController@getMessageLib');
        Route::post('users/updatemsglib', 'UserController@updateMessageLib');
        
        Route::post('users/delmsglib', 'UserController@delMessageLib');
        Route::get('users/message/msglib/create', 'UserController@addMessageLibPage');
        Route::get('users/message/msglib/create/reporter', 'UserController@addMessageLibPageReporter');
        Route::get('users/message/msglib/create/reported', 'UserController@addMessageLibPageReported');
        Route::get('users/message/msglib/create/{id}', 'UserController@addMessageLibPage');
        Route::get('users/message/msglib/create/reporter/{id}', 'UserController@addMessageLibPageReporter');
        Route::get('users/message/msglib/create/reported/{id}', 'UserController@addMessageLibPageReported');
        Route::post('users/addmsglib', 'UserController@addMessageLib');
        Route::post('users/block_user', 'UserController@blockUser');/*封鎖會員*/
        Route::post('users/unblock_user', 'UserController@unblockUser');/*封鎖會員*/
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
