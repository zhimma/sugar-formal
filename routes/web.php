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
Route::get('Fingerprint2', 'Fingerprint@index');
Route::post('Fingerprint2/addFingerprint', 'Fingerprint@addFingerprint');

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::post('/Common/get_message', 'Common@get_message');
Route::post('/Common/checkcode_during', 'Common@checkcode_during');
Route::get('/Common/get_exif', 'Common@get_exif');
Route::post('/Common/upload_img', 'Common@upload_img');
Route::post('/Common/save_img', 'Common@save_img');
Route::group(['middleware' => ['api']], function() {
    Route::post('/dashboard/upgradepayEC', 'PagesController@upgradepayEC');
    Route::post('/dashboard/paymentInfoEC', 'PagesController@paymentInfoEC');
});
Route::group(['middleware' => ['tipApi']], function () {
    Route::post('/dashboard/chatpay_ec', 'ECPayment@performTipInvite')->name('chatpay_ec');
    Route::post('/dashboard/postChatpayEC', 'PagesController@postChatpayEC');
});

Route::group(['middleware' => ['valueAddedServiceApi']], function () {
    Route::post('/dashboard/valueAddedService_ec', 'ECPayment@performValueAddedService')->name('valueAddedService_ec');
    Route::post('/dashboard/postValueAddedService', 'PagesController@postValueAddedService')->name('postValueAddedService');
    Route::post('/dashboard/cancelValueAddedService', 'PagesController@cancelValueAddedService');
});

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
Route::group(['middleware' => ['CheckAccountStatus']], function () {
    Route::get('/dashboard/browse', 'PagesController@browse');
});
Route::get('/terms', 'PagesController@terms');
Route::get('/contact', 'PagesController@contact');
Route::get('/buyAvip', function (){return view('dashboard.buyAvip');});
Route::get('/banned', 'PagesController@banned')->name('banned');

Route::get('/sms_add_view', 'PagesController@sms_add_view');
Route::get('/sms_add_list', 'PagesController@sms_add_list');
Route::post('/sms_add', 'PagesController@sms_add');
/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@showLoginForm2')->name('login');
Route::get('/loginIOS', function (){ return view('new.auth.loginIOS'); })->name('loginIOS');
Route::get('/login3ik3pIKe', 'Auth\LoginController@showLoginForm')->name('login2');
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
Route::post('/admin/api/aws-sns/ses', function(Request $request){
    info($request->getContent());
});
Route::group(['middleware' => ['auth']], function () {
    Route::get('/activate', 'Auth\ActivateController@showActivate');
    Route::get('/activate/send-token', 'Auth\ActivateController@sendToken');
});


/*
|--------------------------------------------------------------------------
| Sms
|--------------------------------------------------------------------------
*/

// Route::post('/sms/postAcceptor', 'SmsController@postAcceptor');


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
Route::group(['middleware' => ['auth']], function () {
    //新手教學
    Route::get('/dashboard/newer_manual', 'PagesController@newer_manual');
    Route::get('/dashboard/web_manual', 'PagesController@web_manual');
    Route::get('/dashboard/anti_fraud_manual', 'PagesController@anti_fraud_manual');
    Route::post('/dashboard/newer_manual/isRead', 'PagesController@is_read_manual');
    Route::get('/dashboard/openCloseAccount', 'PagesController@view_openCloseAccount');
    Route::post('/dashboard/closeAccountReason', 'PagesController@view_closeAccountReason');
    Route::post('/dashboard/updateAccountStatus', 'PagesController@updateAccountStatus');
});

Route::group(['middleware' => ['auth', 'active', 'femaleActive', 'vipCheck', 'newerManual','CheckIsWarned','CheckAccountStatus']], function () {

    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    Route::get('/users/switch-back', 'Admin\UserController@switchUserBack')->name('escape');
    Route::post('/message/disableNotice', 'MessageController@disableNotice')->name('disableNotice');
    Route::post('/users/announceRead', 'MessageController@announceRead')->name('announceRead');
    Route::post('/users/announceClose', 'MessageController@announceClose')->name('announceClose');

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
    Route::get('member_auth', 'PagesController@member_auth');
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
    
    Route::post('/dashboard/postAcceptor', 'PagesController@postAcceptor');/*投稿列表功能*/
    Route::get('/dashboard/posts_list', 'PagesController@posts_list');/*投稿列表功能*/
    // Route::get('/dashboard/post_detail/', 'PagesController@post_detail');
    Route::get('/dashboard/post_detail/{pid}', 'PagesController@post_detail');
    Route::post('/dashboard/getPosts', 'PagesController@getPosts');/*動態取得列表資料*/
    Route::get('/dashboard/posts', 'PagesController@posts');/*投稿功能*/
    Route::post('/dashboard/doPosts', 'PagesController@doPosts');/*投稿功能*/
    Route::post('/dashboard/post_views', 'PagesController@post_views');
    Route::post('/dashboard', 'PagesController@profileUpdate');
    Route::post('/dashboard2', 'PagesController@profileUpdate_ajax')->name('dashboard2');
    Route::post('dashboard/settings', 'PagesController@settingsUpdate');
    Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');

    // 大頭照和生活照
    Route::get('/dashboard_img', 'PagesController@dashboard_img')->name('dashboard_img');
    Route::get('/dashboard/pictures/{userId?}', 'ImageController@getPictures');
    Route::post('/dashboard/pictures/upload','ImageController@uploadPictures');
    Route::post('/dashboard/pictures/delete', 'ImageController@deletePictures');
    Route::get('/dashboard/avatar/{userId?}', 'ImageController@getAvatar');
    Route::post('/dashboard/avatar/upload', 'ImageController@uploadAvatar');
    Route::post('/dashboard/avatar/delete/{userId}', 'ImageController@deleteAvatar');
    Route::post('/dashboard/delPic', 'PagesController@delPic');

    Route::get('/dashboard/password', 'PagesController@view_changepassword'); //new route
    Route::post('/dashboard/changepassword', 'PagesController@changePassword'); //new route

    Route::get('/dashboard/vipForNewebPay', 'PagesController@viewVipForNewebPay'); //new route

    Route::get('/dashboard/account_manage', 'PagesController@view_account_manage'); //new route
    Route::get('/dashboard/account_name_modify', 'PagesController@view_name_modify'); //new route
    Route::post('/dashboard/changeName', 'PagesController@changeName'); //new route
    Route::get('/dashboard/account_gender_change', 'PagesController@view_gender_change'); //new route
    Route::post('/dashboard/changeGender', 'PagesController@changeGender'); //new route
    Route::get('/dashboard/account_consign_add', 'PagesController@view_consign_add'); //new route
    Route::post('/dashboard/consignAdd', 'PagesController@consignAdd'); //new route
    Route::get('/dashboard/account_consign_cancel', 'PagesController@view_consign_cancel'); //new route
    Route::post('/dashboard/consignCancel', 'PagesController@consignCancel'); //new route
    Route::get('/dashboard/account_exchange_period', 'PagesController@view_exchange_period'); //new route exchange_period_modify
    Route::post('/dashboard/exchangePeriodModify', 'PagesController@exchangePeriodModify'); //new route
    Route::get('/dashboard/account_hide_online', 'PagesController@view_account_hide_online'); //new route

    Route::get('/dashboard/vip', 'PagesController@view_new_vip'); //new route
    Route::get('/dashboard/new_vip', 'PagesController@view_new_vip'); //new route
    Route::get('/dashboard/vipSelect', 'PagesController@view_vipSelect'); //new route
    Route::get('/dashboard/valueAddedHideOnline', 'PagesController@view_valueAddedHideOnline'); //new route
    Route::post('/dashboard/hideOnlineSwitch', 'PagesController@hideOnlineSwitch')->name('hideOnlineSwitch'); //new route
    Route::get('/dashboard2', 'PagesController@dashboard2');
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
    Route::post('/dashboard/reportMsg', 'PagesController@reportMsg')->name('reportMsg');
    Route::get('/dashboard/reportPic/{user}/{id}/{uid?}', 'PagesController@reportPic')->name('reportPic');
    Route::post('/dashboard/reportPicNext', 'PagesController@reportPicNext')->name('reportPicNext');
    Route::post('/dashboard/reportPicNextNew', 'PagesController@reportPicNextNew')->name('reportPicNextNew'); //new route
    Route::get('/dashboard/upgrade_ec', 'PagesController@upgrade_ec');
    Route::get('/dashboard/upgrade_esafe', 'PagesController@upgrade_esafe');
    Route::get('/dashboard/announcement', 'PagesController@showAnnouncement');
    Route::group(['middleware' => ['api']], function() {
        Route::post('/dashboard/payback_ec', 'ECPayment@performPayBack')->name('payback_ec');
        Route::post('/dashboard/upgradepay_ec', 'ECPayment@performPayment')->name('upgradepay_ec');
        Route::post('/dashboard/esafeCreditCard', 'EsafePayment@esafeCreditCard')->name('esafeCreditCard');
        Route::post('/dashboard/esafePayment', 'EsafePayment@esafePayment')->name('esafePayment');
        Route::post('/dashboard/esafePayCode', 'EsafePayment@esafePayCode')->name('esafePayCode');
        Route::post('/dashboard/esafeWebATM', 'EsafePayment@esafeWebATM')->name('esafeWebATM');
        Route::post('/dashboard/upgradepay', 'PagesController@upgradepay');
        Route::post('/dashboard/receive_esafe', 'PagesController@receive_esafe');
        Route::post('/dashboard/repaid_esafe', 'PagesController@repaid_esafe');
        Route::post('/dashboard/cancelpay', 'PagesController@cancelpay');
    });
    Route::post('/upgradepayLog', 'PagesController@upgradepayLog')->name('upgradepayLog');
    Route::post('/dashboard/deleteboard', 'BoardController@deleteBoard')->name('deleteBoard');

    Route::group(['middleware' => ['vipc']], function () {
        Route::post('/dashboard/board', 'PagesController@postBoard');
        Route::get('/dashboard/history', 'PagesController@history');
        Route::get('/dashboard/block', 'PagesController@block');
        Route::get('/dashboard/block2', 'PagesController@block2');
        Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/fav2', 'PagesController@fav2');
    });

    Route::group(['middleware' => ['filled']], function () {

        //新樣板
        Route::get('/dashboard/chat2/{randomNo?}', 'Message_newController@chatview')->name('chat2View');
        Route::post('/dashboard/chat2/showMessages/{randomNo?}', 'Message_newController@chatviewMore')->name('showMessages');
        Route::get('/dashboard/chat2/chatShow/{cid}', 'PagesController@chat2')->name('chat2WithUser');
        Route::post('/dashboard/chat2/deletesingle', 'Message_newController@deleteSingle')->name('delete2Single');
        Route::post('/dashboard/chat2/{randomNo?}', 'Message_newController@postChat');
        Route::get('/dashboard/chat2/deleterow/{uid}/{sid}', 'Message_newController@deleteBetweenGET')->name('delete2BetweenGET');
        Route::get('/dashboard/chat2/deleterowall/{uid}/{sid}', 'Message_newController@deleteBetweenGetAll')->name('deleteBetweenGetAll');
        Route::post('/dashboard/chat2/deleteall', 'Message_newController@deleteAll')->name('delete2All');
        Route::post('/dashboard/chat2/chatSet', 'Message_newController@chatSet')->name('chatSet');
        Route::post('/dashboard/announcement_post', 'Message_newController@announcePost')->name('announcePost');
        Route::get('/dashboard/manual', 'PagesController@manual');

        Route::post('/dashboard/letTourRead', 'PagesController@letTourRead')->name('letTourRead');

        Route::get('/dashboard/evaluation/{uid}', 'PagesController@evaluation');
        Route::post('/dashboard/evaluation/save', 'PagesController@evaluation_save')->name('evaluation');
        Route::post('/dashboard/evaluation/re_content_save', 'PagesController@evaluation_re_content_save')->name('evaluation_re_content');
        Route::post('/dashboard/evaluation/re_content_delete', 'PagesController@evaluation_re_content_delete')->name('evaluation_re_content_delete');
        Route::post('/dashboard/evaluation/delete', 'PagesController@evaluation_delete')->name('evaluation_delete');
        Route::get('/dashboard/evaluation_self', 'PagesController@evaluation_self');
        Route::post('/dashboard/evaluation_self/deleteAll', 'PagesController@evaluation_self_deleteAll')->name('evaDeleteAll'); //new route


        Route::get('/dashboard/banned', 'PagesController@dashboard_banned');
        Route::get('/dashboard/visited', 'PagesController@visited');
        Route::get('/dashboard/viewuser/{uid?}', 'PagesController@viewuser2'); //new route

        Route::get('/dashboard/board', 'PagesController@board');
        //Route::get('/dashboard/history', 'PagesController@history');
        //Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/upgradesuccess', 'PagesController@upgradesuccess');
        //Route::get('/dashboard/search', 'PagesController@search');
        Route::get('/dashboard/search', 'PagesController@search2');//new route
        Route::post('/dashboard/search', 'PagesController@search2');//new route
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
        Route::get('stats/vip/paid/readOnly', 'StatController@vipPaid')->name('stats/vip/paid/readOnly');
        Route::post('users/VIPToggler/readOnly', 'UserController@toggleVIP')->name('VIPToggler/readOnly');
        Route::get('users/advInfo/{id}/readOnly', 'UserController@advInfo')->name('users/advInfo/readOnly');
        Route::get('to/{id}/readOnly', 'UserController@showAdminMessenger')->name('AdminMessage/readOnly');
        Route::post('send/{id}/readOnly', 'UserController@sendAdminMessage')->name('admin/send/readOnly');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures/readOnly');
        Route::post('users/pictures', 'UserController@searchUserPictures')->name('users/pictures/readOnly');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify/readOnly');
        Route::get('users/advInfo/editPic_sendMsg/{id}', 'UserController@editPic_sendMsg')->name('users/pictures/editPic_sendMsg/readOnly');
        Route::post('send/{id}', 'UserController@sendAdminMessage')->name('admin/send/readOnly');
        Route::group(['prefix'=>'users/message'], function() {
            Route::post('multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple/readOnly');
        });
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
        Route::get('users/advInfo/editPic_sendMsg/{id}', 'UserController@editPic_sendMsg');
        Route::post('users/toggleUserBlock', 'UserController@toggleUserBlock');
        Route::get('users/toggleUserBlock/{id}', 'UserController@toggleUserBlock_simple')->name('toggleUserBlock');
        Route::post('users/userUnblock', 'UserController@userUnblock');
        Route::get('users/banUserWithDayAndMessage/{user_id}/{msg_id}/{isReported?}', 'UserController@showBanUserDialog')->name('banUserWithDayAndMessage');
        Route::get('users/warnedUserWithDayAndMessage/{user_id}/{msg_id}', 'UserController@showWarnedUserDialog')->name('warnedUserWithDayAndMessage');

        Route::post('users/banUserWithDayAndMessage', 'UserController@banUserWithDayAndMessage');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures');
        Route::post('users/pictures', 'UserController@searchUserPictures')->name('users/pictures');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify');
        Route::get('users/reported/count', 'UserController@showReportedCountPage')->name('users/reported/count');
        Route::post('users/reported/count', 'UserController@showReportedCountList')->name('users/reported/count');
        Route::get('users/board', 'PagesController@board')->name('users/board');
        Route::post('users/board', 'PagesController@board')->name('users/board/search');
        Route::get('users/board/delete/{id}', 'UserController@deleteBoard')->name('users/board/delete');

        Route::get('users/memberList', 'UserController@memberList')->name('users/memberList');
        Route::post('users/memberList', 'UserController@searchMemberList')->name('searchMemberList');

        Route::post('users/toggleUserWarned', 'UserController@toggleUserWarned');
        Route::get('users/closeAccountReason', 'UserController@closeAccountReason')->name('users/closeAccountReasonList');
        Route::get('users/closeAccountDetail', 'UserController@closeAccountDetail');

        Route::group(['prefix'=>'users/message'], function(){
            Route::get('showBetween/{id1}/{id2}', 'UserController@showMessagesBetween')->name('admin/showMessagesBetween');
            Route::get('to/{id}', 'UserController@showAdminMessenger')->name('AdminMessage');
            Route::get('to/{id}/{mid}', 'UserController@showAdminMessengerWithMessageId')->name('AdminMessengerWithMessageId');
            Route::get('unreported/to/{id}/{reported_id}/{pic_id?}/{isPic?}/{isReported?}', 'UserController@showAdminMessengerWithReportedId')->name('AdminMessengerWithReportedId');
            Route::post('send/{id}', 'UserController@sendAdminMessage')->name('admin/send');
            Route::post('multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple');
            Route::get('search', 'UserController@showMessageSearchPage')->name('users/message/search');
            Route::get('search/reported/{date_start?}/{date_end?}', 'UserController@showReportedMessages')->name('search/reported');
            Route::post('search', 'UserController@searchMessage');
            Route::post('modify', 'UserController@modifyMessage')->name('users/message/modify');
            Route::post('delete', 'UserController@deleteMessage')->name('users/message/delete');
            Route::post('edit', 'UserController@editMessage')->name('users/message/edit');

            Route::get('sendUserMessage', 'UserController@showSendUserMessage')->name('admin/showSendUserMessage');
            Route::post('sendUserMessage', 'UserController@sendUserMessage')->name('admin/sendUserMessage');
            Route::post('sendUserMessageFindUserInfo', 'UserController@sendUserMessageFindUserInfo')->name('sendUserMessageFindUserInfo');
        });

        Route::group(['prefix'=>'users/spam_text_message'], function(){
            Route::get('search', 'UserController@showSpamTextMessage')->name('showSpamTextMessage');
            Route::post('search', 'UserController@searchSpamTextMessage')->name('searchSpamTextMessage');
        });

        
        Route::get('statistics', 'UserController@statisticsReply')->name("statistics");
        Route::post('statistics', 'UserController@statisticsReply');

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
        Route::post('users/genderToggler', 'UserController@toggleGender')->name('genderToggler');
        Route::post('users/VIPToggler', 'UserController@toggleVIP')->name('VIPToggler');
        Route::post('users/RecommendedToggler', 'UserController@toggleRecommendedUser');
        Route::post('users/reportedToggler', 'UserController@reportedToggler');
        Route::get('users/banned_implicitly', 'UserController@showImplicitlyBannedUsers')->name('implicitlyBanned');
        Route::post('users/bans_implicitly', 'UserController@banningUserImplicitly')->name('banningUserImplicitly');
        Route::post('users/bans_fingerprint', 'UserController@banningFingnerprint')->name('banFingerprint');
        Route::post('users/unbans_fingerprint', 'UserController@unbanningFingnerprint')->name('unbanFingerprint');
        Route::post('users/unbanAll', 'UserController@unbanAll')->name('unbanAll');
        Route::get('users/showFingerprint/{showFingerprint}', 'UserController@showFingerprint')->name('showFingerprint');
        Route::get('users/showLoginLog/{uid}/{date}', 'UserController@showLoginLog')->name('showLoginLog');
        Route::get('users/deleteFingerprintFromExpectedList/{fingerprint}', 'UserController@deleteFingerprintFromExpectedList')->name('deleteFingerprintFromExpectedList');
        Route::get('users/warning', 'UserController@showWarningUsers')->name('warningUsers');
        Route::get('users/suspectedMultiLogin', 'UserController@showSuspectedMultiLogin')->name('suspectedMultiLogin');
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

        Route::get('masterwords', 'UserController@showMasterwords')->name('admin/masterwords');
        Route::get('masterwords/edit/{id}', 'UserController@showAdminMasterWordsEdit')->name('admin/masterwords/edit');
        Route::post('masterwords/save', 'UserController@saveAdminMasterWords')->name('admin/masterwords/save');
        Route::get('masterwords/delete/{id?}', 'UserController@deleteAdminMasterWords')->name('admin/masterwords/delete');
        Route::get('masterwords/new', 'UserController@showNewAdminMasterWords')->name('admin/masterwords/new');
        Route::post('masterwords/new', 'UserController@newAdminMasterWords')->name('admin/masterwords/new');
        Route::get('masterwords/read/{id}', 'UserController@showReadMasterWords')->name('admin/masterwords/read');

        Route::get('web/announcement', 'UserController@showWebAnnouncement')->name('admin/web/announcement');
        Route::get('/chat', 'MessageController@chatview')->name('admin/chat');
        Route::get('/chat/{cid}', 'PagesController@chat');
        Route::post('/chat', 'MessageController@postChat');
        Route::get('commontext', 'UserController@showAdminCommonText')->name('admin/commontext');
        Route::post('commontext/save', 'UserController@saveAdminCommonText')->name('admin/commontext/save');
        Route::get('getAdminActionLog', 'UserController@adminActionLog')->name('admin/getAdminActionLog');
        Route::get('users/inactive', 'UserController@inactiveUsers')->name('inactive');
        Route::post('users/inactive', 'UserController@inactiveUsers')->name('inactive');
        Route::get('users/activate/token/{token}', 'UserController@activateUser')->name('activateUser');
        Route::get('stats/vip', 'StatController@vip')->name('stats/vip');
        Route::get('stats/other', 'StatController@other')->name('stats/vip/other');
        Route::post('stats/other', 'StatController@other')->name('stats/vip/other');
        Route::get('stats/vip/paid', 'StatController@vipPaid')->name('stats/vip/paid');
        Route::get('stats/vip_log/{id}', 'StatController@vipLog')->name('stats/vip_log');
        Route::get('stats/cron_log', 'StatController@cronLog')->name('stats/cron_log');
        Route::get('stats/date_file_log', 'StatController@datFileLog')->name('stats/date_file_log');
        Route::get('stats/set_autoBan', 'StatController@set_autoBan')->name('stats/set_autoBan');
        Route::post('stats/set_autoBan_add', 'StatController@set_autoBan_add')->name('stats/set_autoBan_add');
        Route::get('stats/set_autoBan_del/{id?}', 'StatController@set_autoBan_del')->name('stats/set_autoBan_del');
        Route::get('check', 'UserController@showAdminCheck')->name('admin/check');
        Route::get('checkNameChange', 'UserController@showAdminCheckNameChange')->name('admin/checkNameChange');
        Route::get('checkGenderChange', 'UserController@showAdminCheckGenderChange')->name('admin/checkGenderChange');
        Route::post('checkNameChange', 'UserController@AdminCheckNameChangeSave');
        Route::post('checkGenderChange', 'UserController@AdminCheckGenderChangeSave');
        Route::get('checkExchangePeriod', 'UserController@showAdminCheckExchangePeriod')->name('admin/checkExchangePeriod');
        Route::post('checkExchangePeriod', 'UserController@AdminCheckExchangePeriodSave');

        /*新增、編輯訊息*/
        Route::post('users/getmsglib', 'UserController@getMessageLib');
        Route::post('users/updatemsglib', 'UserController@updateMessageLib');
        
        Route::post('users/delmsglib', 'UserController@delMessageLib');
        Route::get('users/message/msglib/create', 'UserController@addMessageLibPage');
        Route::get('users/message/msglib/create/editPic_sendMsg', 'UserController@addMessageLibPageReporter');
        Route::get('users/message/msglib/create/reporter', 'UserController@addMessageLibPageReporter');
        Route::get('users/message/msglib/create/reported', 'UserController@addMessageLibPageReported');
        Route::get('users/message/msglib/create/delpic', 'UserController@addMessageLibPageReported');
        Route::get('users/message/msglib/create/{id}', 'UserController@addMessageLibPage');
        Route::get('users/message/msglib/create/editPic_sendMsg/{id}', 'UserController@addMessageLibPageReporter');
        Route::get('users/message/msglib/create/reporter/{id}', 'UserController@addMessageLibPageReporter');
        Route::get('users/message/msglib/create/reported/{id}', 'UserController@addMessageLibPageReported');
        Route::get('users/message/msglib/create/delpic/{id}', 'UserController@addMessageLibPageReported');
        Route::post('users/addmsglib', 'UserController@addMessageLib');
        Route::post('users/block_user', 'UserController@blockUser');/*封鎖會員*/
        Route::post('users/unblock_user', 'UserController@unblockUser');/*封鎖會員*/
        Route::post('users/isWarned_user', 'UserController@isWarnedUser');/*警示用戶*/
        Route::get('users/getBirthday', 'UserController@getBirthday');
        Route::post('users/unwarned_user', 'UserController@unwarnedUser');/*站方警示*/
        Route::post('users/changeExchangePeriod', 'UserController@changeExchangePeriod')->name('changeExchangePeriod');/*包養關係*/
        Route::get("sendFakeMail/{repeat?}/{str}", function(){
            $str = "";
            $repeat = request()->repeat ?? 1;
            $content = request()->str ?? "123";
            for ($i = 0; $i < $repeat; $i++){
                $address = 'lzong.tw+'. $i .'@gmail.com';
                \App\Jobs\SendFakeMail::dispatch($address, $content);
                $str .= $address . '<br>';
                $address = 'lzong.tw+1'. $i .'@gmail.com';
                \App\Jobs\SendFakeMail::dispatch($address, $content);
                $str .= $address . '<br>';
                $address = 'lzong.tw+2'. $i .'@gmail.com';
                \App\Jobs\SendFakeMail::dispatch($address, $content);
                $str .= $address . '<br>';
            }
            return $str;
        });

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', 'RoleController', ['except' => ['show']]);
        Route::post('roles/search', 'RoleController@search');
        Route::get('roles/search', 'RoleController@index');
    });


    /*果真有酵*/
    Route::get('/fruits', 'FruitController@index');
    Route::get('/fruits/shop', 'FruitController@shop');
    Route::get('/fruits/brand', 'FruitController@brand');
    Route::get('/fruits/contactus', 'FruitController@contactus');
    Route::get('/fruits/health_info', 'FruitController@health_info');
    Route::get('/fruits/health_info01', 'FruitController@health_info01');
    Route::get('/fruits/health_info02', 'FruitController@health_info02');
    Route::get('/fruits/health_info03', 'FruitController@health_info03');
    Route::get('/fruits/health_info04', 'FruitController@health_info04');
    Route::get('/fruits/health_info_detail', 'FruitController@health_info_detail');
    Route::get('/fruits/news01', 'FruitController@news01');
    Route::get('/fruits/news02', 'FruitController@news02');
    Route::get('/fruits/order_success', 'FruitController@order_success');
    Route::get('/fruits/order_confirm', 'FruitController@order_confirm');

    Route::get('/fruits/product_beauty', 'FruitController@product_beauty');
    Route::get('/fruits/product_berry', 'FruitController@product_berry');
    Route::get('/fruits/product_charantia', 'FruitController@product_charantia');
    Route::get('/fruits/product_key', 'FruitController@product_key');
    Route::get('/fruits/product_ferment', 'FruitController@product_ferment');

    Route::get('/fruits/product_beauty_more', 'FruitController@product_beauty_more');
    Route::get('/fruits/product_berry_more', 'FruitController@product_berry_more');
    Route::get('/fruits/product_charantia_more', 'FruitController@product_charantia_more');
    Route::get('/fruits/product_key_more', 'FruitController@product_key_more');
    Route::get('/fruits/product_ferment_more', 'FruitController@product_ferment_more');
});
Route::get('/test', 'ImageController@deletePictures');