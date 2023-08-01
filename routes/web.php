<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\CfpController;
use App\Http\Controllers\DeployController;

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
// Route::group(['middleware' => ['feature:site-maintenance-mode']], function () {

Route::get('/fingerprint', 'PagesController@fingerprint');
Route::post('/saveFingerprint', 'PagesController@saveFingerprint')->name('saveFingerprint');
Route::get('Fingerprint2', 'Fingerprint@index');
Route::post('Fingerprint2/addFingerprint', 'Fingerprint@addFingerprint');
Route::post('deploy', 'DeployController@deploy');
Route::post('staging', 'DeployController@staging');

/*
|--------------------------------------------------------------------------
| API
|--------------------------------------------------------------------------
*/
Route::post('/LocalMachineReceive/BanAndWarn', 'LocalMachineReceiveController@BanAndWarn');
Route::post('/LocalMachineReceive/BanSetIPUpdate', 'LocalMachineReceiveController@BanSetIPUpdate');

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
Route::group(['middleware' => ['mobileVerifyApi']], function () {
    Route::post('/dashboard/mobileVerifyPay_ec', 'ECPayment@performMobileVerify')->name('mobileAutoVerify_ec');
    Route::post('/dashboard/postMobileVerifyPayEC', 'PagesController@postMobileVerifyPayEC');
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
Route::get('/', 'PagesController@home');
//紀錄上線時間
Route::post('/stay_online_time', 'PagesController@stay_online_time')->name('stay_online_time');
Route::get('/advertise_record', 'PagesController@advertise_record')->name('advertise_record');
Route::get('/advertise_record_change', 'PagesController@advertise_record_change')->name('advertise_record_change');
Route::get('/vue_test', 'PagesController@vue_test');
Route::get('/getAllData', 'PagesController@getAllData');
Route::get('/getCollectionData', 'PagesController@getCollectionData');
Route::post('/getSearchData', 'PagesController@getSearchData');
Route::post('/getSingleSearchData', 'PagesController@getSingleSearchData');

Route::post('/getHideData', 'PagesController@getHideData');
Route::post('/getFavCount', 'PagesController@getFavCount');
Route::post('/getBlockUser', 'PagesController@getBlockUser');

Route::get('/privacy', 'PagesController@privacy');
Route::get('/notification', 'PagesController@notification');
Route::get('/feature', 'PagesController@feature');
Route::get('/about', 'PagesController@about');
Route::get('/terms', 'PagesController@terms');
Route::get('/contact', 'PagesController@contact');
Route::get('/buyAvip', function (){return view('dashboard.buyAvip');});
Route::get('/banned', 'PagesController@banned')->name('banned');

Route::get('/sms_add_view', 'PagesController@sms_add_view');
Route::get('/sms_add_list', 'PagesController@sms_add_list');
Route::post('/sms_add', 'PagesController@sms_add');

Route::get('/refresh-csrf', function(){
    return csrf_token();
});
/*
|--------------------------------------------------------------------------
| Login/ Logout/ Password
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@showLoginForm2')->name('login');
Route::get('/loginIOS', function (){ return view('new.auth.loginIOS'); })->name('loginIOS');
Route::get('/login3ik3pIKe', 'Auth\LoginController@showLoginForm2')->name('login2');
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
Route::post('/admin/api/aws-sns/ses', 'Api\MailController@mailLog');
Route::group(['middleware' => ['auth', 'global','SessionExpired']], function () {
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
Route::get('/unread/{user_id}', 'Message_newController@getUnread')->middleware('auth')->name('getUnread');
Route::group(['middleware' => ['auth', 'global','SessionExpired']], function () {
    //新手教學
    Route::get('/dashboard/newer_manual', 'PagesController@newer_manual');
    Route::get('/dashboard/web_manual', 'PagesController@web_manual');
    Route::get('/dashboard/anti_fraud_manual', 'PagesController@anti_fraud_manual');
    Route::post('/dashboard/newer_manual/isRead', 'PagesController@is_read_manual');
    Route::get('/dashboard/female_newer_manual', 'PagesController@female_newer_manual');
    Route::post('/dashboard/female_newer_manual/isRead', 'PagesController@is_read_female_manual');
    Route::get('/dashboard/openCloseAccount', 'PagesController@view_openCloseAccount');
    Route::post('/dashboard/closeAccountReason', 'PagesController@view_closeAccountReason');
    Route::post('/dashboard/updateAccountStatus', 'PagesController@updateAccountStatus');
    Route::post('/multiple-login', 'PagesController@multipleLogin')->name('multipleLogin');
    Route::post('/save-cfp', 'PagesController@savecfp')->name('savecfp');
    Route::post('/check-cfp', 'PagesController@checkcfp')->name('checkcfp');
});
Route::post('/dashboard/faq_reply', 'PagesController@checkFaqAnswer')->middleware('auth')->name('checkFaqAnswer');
Route::get('/dashboard/faq_check', 'PagesController@checkIsForceShowFaq')->middleware('auth')->name('checkIsForceShowFaq');
Route::get('/advance_auth_activate/token/{token}', 'PagesController@advance_auth_email_activate')->name('advance_auth_email_activate');
Route::get('/dashboard/faq_save_reply_error_state', 'PagesController@saveFaqReplyErrorState')->middleware('auth')->name('saveFaqReplyErrorState');
Route::get('/dashboard/faq_read_reply_error_state', 'PagesController@readFaqReplyErrorState')->middleware('auth')->name('readFaqReplyErrorState');

Route::group(['middleware' => ['auth', 'global', 'active', 'femaleActive', 'vipCheck', 'VvipCheck', 'newerManual', 'CheckAccountStatus', 'AdjustedPeriodCheck', 'SessionExpired','FaqCheck','RealAuthMiddleware']], function () {

    Route::get('/dashboard/browse', 'PagesController@browse');
    /*
    |--------------------------------------------------------------------------
    | General
    |--------------------------------------------------------------------------
    */

    Route::get('/users/switch-back', 'Admin\UserController@switchUserBack')->name('escape');
    Route::post('/message/disableNotice', 'MessageController@disableNotice')->name('disableNotice');
    Route::post('/users/announceRead', 'MessageController@announceRead')->name('announceRead');
    Route::post('/users/announceClose', 'MessageController@announceClose')->name('announceClose');
    Route::post('/users/commonTextRead', 'MessageController@commonTextRead')->name('commonTextRead');
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

    Route::get('/user/view/{uid?}', function ($uid) { return redirect(route('viewuser', [$uid])); });
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
    Route::get('member_auth', 'PagesController@member_auth')->name('member_auth');
    Route::get('goto_member_auth', 'PagesController@goto_member_auth');
    Route::get('goto_advance_auth_email', 'PagesController@goto_advance_auth_email');    
    Route::post('member_auth_phone_process', 'PagesController@member_auth_phone_process');
    Route::post('advance_auth_email_process', 'PagesController@advance_auth_email_process');
    Route::get('member_auth_photo', 'PagesController@member_auth_photo');

    Route::get('hint_auth1', 'PagesController@hint_auth1');
    Route::get('hint_auth2', 'PagesController@hint_auth2');


    /*會員驗證END*/


    /*進階驗證*/
    Route::get('advance_auth', 'PagesController@advance_auth');
    Route::get('advance_auth_email', 'PagesController@advance_auth_email');
    Route::post('advance_auth_process', 'PagesController@advance_auth_process');

    Route::get('is_advance_auth', 'PagesController@is_advance_auth');
    Route::post('is_advance_auth', 'PagesController@is_advance_auth');

    Route::get('advance_auth_result', 'PagesController@advance_auth_result');
    Route::post('advance_auth_result', 'PagesController@advance_auth_result');

    Route::get('advance_auth_query', 'PagesController@advance_auth_query');
    Route::post('advance_auth_query', 'PagesController@advance_auth_query');

    Route::post('advance_auth_back', 'PagesController@advance_auth_back');
    Route::get('advance_auth_midclause', 'PagesController@advance_auth_midclause');
    /*進階驗證END*/

    //視訊驗證頁面
    Route::get('user_video_chat_verify', 'VideoChatController@user_video_chat_verify');

    Route::get('user_video_chat_verify_allow_check', 'VideoChatController@user_video_chat_verify_allow_check')->name('user_video_chat_verify_allow_check');

    //錄影驗證頁面
    Route::get('video_record_verify', 'VideoChatController@video_record_verify')->name('video_record_verify');
    Route::post('video_record_verify_upload', 'VideoChatController@video_record_verify_upload')->name('video_record_verify_upload');
    Route::get('apply_video_record_verify', 'VideoChatController@apply_video_record_verify')->name('apply_video_record_verify');
    Route::get('hint_to_video_record_verify', 'VideoChatController@hint_to_video_record_verify')->name('hint_to_video_record_verify');
    Route::post('reset_cancel_video_verify', 'VideoChatController@reset_cancel_video_verify')->name('reset_cancel_video_verify');
    Route::post('video_record_verify_reverify', 'VideoChatController@video_record_verify_reverify')->name('video_record_verify_reverify');
    Route::post('video_record_verify_reverify_success', 'VideoChatController@video_record_verify_reverify_success')->name('video_record_verify_reverify_success');
    Route::get('restart_video_verify_record', 'VideoChatController@restart_video_verify_record')->name('restart_video_verify_record');

    //視訊功能測試
    Route::get('/video-chat-test', 'VideoChatController@videoChatTest');

    //視訊功能
    Route::post('/video/call-user', 'VideoChatController@callUser');
    Route::post('/video/loading-video-page', 'VideoChatController@loadingVideoPage');    
    Route::post('/video/unloading-video-page', 'VideoChatController@unloadingVideoPage');     
    Route::post('/video/accept-call', 'VideoChatController@acceptCall');
    Route::post('/video/decline-call', 'VideoChatController@declineCall');
    Route::post('/video/abort-dial-call', 'VideoChatController@abortDialCall');
    Route::get('/video/receive-call-user-signal-data', 'VideoChatController@receiveCallUserSignalData');
    Route::get('/video/receive-accept-call-signal-data', 'VideoChatController@receiveAcceptCallSignalData');
    Route::any('/video/log_video_chat_process', 'VideoChatController@log_video_chat_process')->name('log_video_chat_process');

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::group(['middleware' => ['CheckDiscussPermissions']], function () {

        //個人討論區
        Route::get('/dashboard/forum', 'PagesController@forum')->name('forum');
        Route::get('/dashboard/ForumEdit/{uid}', 'PagesController@ForumEdit');
        Route::post('/dashboard/doForum', 'PagesController@doForum');


        Route::post('/dashboard/doForumPosts', 'PagesController@doForumPosts');
        Route::get('/dashboard/forum_personal/{fid}', 'PagesController@forum_personal');
        Route::get('/dashboard/forum_manage/{fid}', 'PagesController@forum_manage');
        Route::post('/dashboard/forum_manage_toggle', 'PagesController@forum_manage_toggle')->name('forum_manage_toggle');
        Route::post('/dashboard/forum_status_toggle', 'PagesController@forum_status_toggle')->name('forum_status_toggle');
        Route::get('/dashboard/forum_manage_chat/{auid}/{uid}/{fm_id}', 'PagesController@forum_manage_chat');
        Route::get('/dashboard/forum_posts/{fid}', 'PagesController@forum_posts');/*投稿功能*/
        Route::get('/dashboard/forum_post_detail/{pid}', 'PagesController@forum_post_detail');
        Route::get('/dashboard/forumPostsEdit/{id}/{editType}', 'PagesController@forumPostsEdit');/*投稿修改功能*/
        Route::post('/dashboard/forum_posts_reply', 'PagesController@forum_posts_reply');/*討論區留言回覆*/
        Route::post('/dashboard/forum_posts_delete', 'PagesController@forum_posts_delete');/*討論區留言刪除*/
        Route::post('/dashboard/forum_posts_recover', 'PagesController@forum_posts_recover');/*討論區留言恢復*/

        Route::post('/dashboard/postAcceptor', 'PagesController@postAcceptor');/*投稿列表功能*/
        Route::get('/dashboard/posts_list', 'PagesController@posts_list')->name('posts_list');/*投稿列表功能*/
        Route::get('/dashboard/post_detail/{pid}', 'PagesController@post_detail');
        Route::post('/dashboard/getPosts', 'PagesController@getPosts');/*動態取得列表資料*/
        Route::get('/dashboard/posts', 'PagesController@posts');/*投稿功能*/
        Route::get('/dashboard/postsEdit/{id}/{editType}', 'PagesController@postsEdit');/*投稿修改功能*/
        Route::post('/dashboard/doPosts', 'PagesController@doPosts');/*投稿功能*/
        Route::post('/dashboard/posts_reply', 'PagesController@posts_reply');/*討論區留言回覆*/
        Route::post('/dashboard/posts_delete', 'PagesController@posts_delete');/*討論區留言刪除*/
        Route::post('/dashboard/posts_recover', 'PagesController@posts_recover');/*討論區留言恢復*/
        Route::post('/dashboard/post_views', 'PagesController@post_views');

        //VVIP官方討論區
        Route::get('/dashboard/posts_list_VVIP', 'PagesController@posts_list_VVIP')->name('posts_list_VVIP');/*投稿列表功能*/
        Route::get('/dashboard/post_detail_VVIP/{pid}', 'PagesController@post_detail_VVIP');
        Route::post('/dashboard/getPosts_VVIP', 'PagesController@getPosts_VVIP');/*動態取得列表資料*/
        Route::get('/dashboard/posts_VVIP', 'PagesController@posts_VVIP');/*投稿功能*/
        Route::get('/dashboard/postsEdit_VVIP/{id}/{editType}', 'PagesController@postsEdit_VVIP');/*投稿修改功能*/
        Route::post('/dashboard/doPosts_VVIP', 'PagesController@doPosts_VVIP');/*投稿功能*/
        Route::post('/dashboard/posts_reply_VVIP', 'PagesController@posts_reply_VVIP');/*討論區留言回覆*/
        Route::post('/dashboard/posts_delete_VVIP', 'PagesController@posts_delete_VVIP');/*討論區留言刪除*/
        Route::post('/dashboard/posts_recover_VVIP', 'PagesController@posts_recover_VVIP');/*討論區留言恢復*/
        Route::post('/dashboard/post_views_VVIP', 'PagesController@post_views_VVIP');

        //精華討論區
        Route::get('/dashboard/essence_enter_intro', 'PagesController@essence_enter_intro');
        Route::get('/dashboard/essence_main', 'PagesController@essence_main');
        Route::get('/dashboard/essence_list', 'PagesController@essence_list');
        Route::get('/dashboard/essence_posts', 'PagesController@essence_posts');
        Route::post('/dashboard/essence_doPosts', 'PagesController@essence_doPosts');
        Route::get('/dashboard/essence_post_detail/{pid}', 'PagesController@essence_post_detail');
        Route::get('/dashboard/essence_postsEdit/{id}/{editType}', 'PagesController@essence_postsEdit');/*投稿修改功能*/
        Route::post('/dashboard/essence_posts_delete', 'PagesController@essence_posts_delete');/*討論區留言刪除*/
        Route::post('/dashboard/essence_posts_recover', 'PagesController@essence_posts_recover');/*討論區留言恢復*/
        Route::post('/dashboard/essence_verify_status', 'PagesController@essence_verify_status');/*討論區留言審核*/

    });

    //心情文章
    Route::group(['prefix' => 'mood'], function () {
        //Route::get('/posts_list', 'PagesController@posts_list_mood')->name('posts_list_mood');/*投稿列表功能*/
        Route::get('/post_detail/{pid}', 'PagesController@post_detail_mood');
        Route::post('/getPosts', 'PagesController@getPosts_mood');/*動態取得列表資料*/
        Route::get('/posts', 'PagesController@posts_mood');/*投稿功能*/
        Route::get('/postsEdit/{id}/{editType}', 'PagesController@postsEdit_mood');/*投稿修改功能*/
        Route::post('/doPosts', 'PagesController@doPosts_mood');/*投稿功能*/
        Route::post('/posts_reply', 'PagesController@posts_reply_mood');/*討論區留言回覆*/
        Route::post('/posts_delete', 'PagesController@posts_delete_mood');/*討論區留言刪除*/
        Route::post('/posts_recover', 'PagesController@posts_recover_mood');/*討論區留言恢復*/
        Route::post('/post_views', 'PagesController@post_views_mood');
    });

    //留言板
    Route::group(['prefix' => 'MessageBoard'], function () {
        Route::get('/showList', 'PagesController@messageBoard_showList')->name('messageBoard_list');
        Route::get('/posts', 'PagesController@messageBoard_posts');
        Route::get('/edit/{id}', 'PagesController@messageBoard_edit');
        Route::get('/post_detail/{pid}', 'PagesController@messageBoard_post_detail');
        Route::post('/doPosts', 'PagesController@messageBoard_doPosts');
        Route::post('/delete/{mid}', 'PagesController@messageBoard_delete');

        Route::post('/showListMyself', 'PagesController@messageBoard_showList_myself')->name('messageBoard_list_myself');
        Route::post('/showListOther', 'PagesController@messageBoard_showList_other')->name('messageBoard_list_other');
        Route::post('/getItemHeader', 'PagesController@messageBoard_itemHeader')->name('messageBoard_itemHeader');
        Route::post('/getItemContent', 'PagesController@messageBoard_itemContent')->name('messageBoard_itemContent');
    });

    Route::post('/dashboard', 'PagesController@profileUpdate');
    Route::post('/dashboard2', 'PagesController@profileUpdate_ajax')->name('dashboard2');
    Route::post('dashboard/settings', 'PagesController@settingsUpdate');
    Route::get('/dashboard', 'PagesController@dashboard')->name('dashboard');

    //使用者註冊時間
    Route::post('/dashboard/regist_time', 'PagesController@regist_time')->name('regist_time');

    // 大頭照和生活照
    Route::get('/dashboard_img', 'PagesController@dashboard_img')->name('dashboard_img');
    Route::get('/dashboard/pictures/{userId?}', 'ImageController@getPictures');
    Route::post('/dashboard/pictures/upload','ImageController@uploadPictures')->name('dashboard/pictures/upload');
    Route::post('/dashboard/pictures/delete', 'ImageController@deletePictures');
    Route::get('/dashboard/avatar/{userId?}', 'ImageController@getAvatar');
    Route::get('/dashboard/avatar/blurry/{userId?}', 'PagesController@getBlurryAvatar');
    Route::post('/dashboard/avatar/blurry/{userId?}', 'PagesController@blurryAvatar');
    Route::post('/dashboard/lifephoto/blurry/{userId?}', 'PagesController@blurryLifePhoto');
    Route::post('/dashboard/avatar/upload', 'ImageController@uploadAvatar')->name('dashboard/avatar/upload');
    Route::post('/dashboard/avatar/delete/{userId}', 'ImageController@deleteAvatar');
    Route::post('/dashboard/delPic', 'PagesController@delPic');
    Route::get('/dashboard/password', 'PagesController@view_changepassword'); //new route
    Route::post('/dashboard/changepassword', 'PagesController@changePassword'); //new route

    Route::get('/dashboard/vipForNewebPay', 'PagesController@viewVipForNewebPay'); //new route
    Route::get('/dashboard/vipForPaid', 'PagesController@viewVipForPaid'); //new route
    Route::get('/dashboard/suspicious', 'PagesController@viewSuspicious'); //new route
    Route::get('/dashboard/suspicious_list', 'PagesController@suspicious_list');
    Route::get('/dashboard/suspicious_posts', 'PagesController@suspicious_posts');
    Route::post('/dashboard/suspicious_doPosts', 'PagesController@suspicious_doPosts');
    Route::get('/dashboard/view_suspicious_edit/{id}', 'PagesController@view_suspicious_edit');
    Route::post('/dashboard/suspicious_delete/{id}', 'PagesController@suspicious_delete');
    Route::get('/dashboard/suspicious_count/{id}', 'PagesController@suspicious_count');
    Route::post('/dashboard/suspicious_u_account', 'PagesController@suspiciousUserAccount')->name('suspicious_u_account'); //new route

    Route::get('/dashboard/account_manage', 'PagesController@view_account_manage'); //new route
    Route::get('/dashboard/account_name_modify', 'PagesController@view_name_modify'); //new route
    Route::post('/dashboard/changeName', 'PagesController@changeName'); //new route
    Route::get('/dashboard/account_gender_change', 'PagesController@view_gender_change'); //new route
    Route::post('/dashboard/changeGender', 'PagesController@changeGender'); //new route
    Route::get('/dashboard/account_consign_add', 'PagesController@view_consign_add'); //new route
    Route::post('/dashboard/consignAdd', 'PagesController@consignAdd'); //new route
    Route::get('/dashboard/account_consign_cancel', 'PagesController@view_consign_cancel'); //new route
    Route::post('/dashboard/consignCancel', 'PagesController@consignCancel'); //new route
    Route::get('/dashboard/account_exchange_period', 'PagesController@view_exchange_period')->withoutMiddleware(['AdjustedPeriodCheck']); //new route exchange_period_modify
    Route::post('/dashboard/exchangePeriodModify', 'PagesController@exchangePeriodModify'); //new route
    Route::post('/dashboard/first_exchange_period_modify', 'PagesController@first_exchange_period_modify')->withoutMiddleware(['AdjustedPeriodCheck']);
    Route::get('/dashboard/first_exchange_period_modify_next_time', 'PagesController@first_exchange_period_modify_next_time')->withoutMiddleware(['AdjustedPeriodCheck']);
    Route::get('/dashboard/account_hide_online', 'PagesController@view_account_hide_online'); //new route

    Route::get('/dashboard/vip', 'PagesController@view_new_vip'); //new route
    Route::get('/dashboard/new_vip', 'PagesController@view_new_vip'); //new route
    Route::get('/dashboard/vipSelect', 'PagesController@view_vipSelect'); //new route
    Route::get('/dashboard/valueAddedHideOnline', 'PagesController@view_valueAddedHideOnline'); //new route
    Route::post('/dashboard/hideOnlineSwitch', 'PagesController@hideOnlineSwitch')->name('hideOnlineSwitch'); //new route

    //--vvip--//
    Route::get('/dashboard/vvipSelect', 'PagesController@view_vvipSelect')->name('vvipUpgradePortal'); //new route
    Route::get('/dashboard/vvipSelectA', 'PagesController@view_vvipSelect_a')->name('vvipSelectA'); //new route
    Route::get('/dashboard/vvipSelectB', 'PagesController@view_vvipSelect_b'); //new route
    Route::post('/dashboard/vvipImages/upload','ImageController@uploadImages_VVIP')->name('uploadImages_VVIP');
    Route::get('/dashboard/vvipPassSelect', 'PagesController@view_vvipPassSelect'); //new route
    Route::get('/dashboard/vvipPassPay', 'PagesController@view_vvipPassPay'); //new route
    Route::get('/dashboard/vvipExclusivePre', 'PagesController@view_vvipExclusivePre'); //new route
    Route::get('/dashboard/vvipExclusive', 'PagesController@view_vvipExclusive'); //new route
    Route::get('/dashboard/vvipCancel', 'PagesController@view_vvipCancel'); //new route
    Route::post('/dashboard/vvipCancel', 'PagesController@vvipCancel'); //new route
    Route::post('/dashboard/vvipUserNoteEdit', 'PagesController@vvipUserNoteEdit'); //new route
    Route::get('/dashboard/vvipInfo', 'PagesController@view_vvipInfo')->name('vvipInfo')->withoutMiddleware('VvipCheck'); //new route
    Route::post('/dashboard/vvipInfoEdit', 'PagesController@edit_vvipInfo')->name('vvipInfoEdit')->withoutMiddleware('VvipCheck'); //new route
//    Route::post('/dashboard/VVIPisInvitedUpdateStatus', 'PagesController@VVIPisInvitedUpdateStatus')->name('VVIPisInvitedUpdateStatus');
    Route::get('/dashboard/vvipSelectionReward', 'PagesController@view_vvipSelectionReward');
    Route::get('/dashboard/vvipSelectionRewardApply', 'PagesController@view_vvipSelectionRewardApply');
    Route::post('/dashboard/vvipSelectionRewardApply', 'PagesController@vvipSelectionRewardApply')->name('vvipSelectionRewardApply');
    Route::post('/dashboard/vvipSelectionRewardIgnore', 'PagesController@vvipSelectionRewardIgnore')->name('vvipSelectionRewardIgnore');
    Route::post('/dashboard/vvipSelectionRewardGirlApply', 'PagesController@vvipSelectionRewardGirlApply')->name('vvipSelectionRewardGirlApply');
    Route::post('/dashboard/vvipSelectionRewardUserNoteEdit', 'PagesController@vvipSelectionRewardUserNoteEdit');

    //--vvip end--//

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
    Route::post('/dashboard/imagedel/batch/{admin?}', 'ImageController@deleteImageBatch');
    Route::post('/dashboard/block', 'PagesController@postBlock');
    Route::post('/dashboard/blockAJAX', 'PagesController@postBlockAJAX')->name('postBlockAJAX');//new route
    Route::post('/dashboard/messageUserNoteAJAX', 'PagesController@messageUserNoteAJAX')->name('messageUserNoteAJAX');//new route
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
    Route::post('/dashboard/reportMessageBoardAJAX', 'PagesController@reportMessageBoardAJAX')->name('reportMessageBoardAJAX');

    Route::get('/dashboard/reportPic/{user}/{id}/{uid?}', 'PagesController@reportPic')->name('reportPic');
    Route::post('/dashboard/reportPicNext', 'PagesController@reportPicNext')->name('reportPicNext');
    Route::post('/dashboard/reportPicNextNew', 'PagesController@reportPicNextNew')->name('reportPicNextNew'); //new route
    Route::get('/dashboard/upgrade_ec', 'PagesController@upgrade_ec');
    Route::get('/dashboard/upgrade_esafe', 'PagesController@upgrade_esafe');
    Route::get('/dashboard/announcement', 'PagesController@showAnnouncement');
    Route::group(['middleware' => ['api']], function() {
        Route::post('/dashboard/payback_ec', 'ECPayment@performPayBack')->name('payback_ec');
        Route::post('/dashboard/upgradepay_ec', 'ECPayment@performPayment')->name('upgradepay_ec');
        Route::post('/dashboard/new_upgradepay_ec', 'ECPayment@commonPayment')->name('new_upgradepay_ec');
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
        Route::get('/dashboard/search_discard/list', 'PagesController@listSearchIgnore');
        Route::post('/dashboard/search_discard/add', 'PagesController@addSearchIgnore');
        Route::post('/dashboard/search_discard/edit', 'PagesController@editSearchIgnore');
        Route::get('/dashboard/search_discard/del', 'PagesController@delSearchIgnore');        
    });
    Route::post('/dashboard/chat2/showMessages/{randomNo?}', 'Message_newController@chatviewMore')->name('showMessages');

    Route::get('/dashboard/chat2/deleteMutipleMessages', 'Message_newController@deleteMutipleMessages')->name('deleteMutipleMessages');
    Route::post('/dashboard/chat2/deleteBetweenMsg_multiple', 'Message_newController@deleteBetweenMsg_multiple')->name('deleteBetweenMsg_multiple');

    Route::group(['middleware' => ['filled']], function () {
        //新樣板
        Route::get('/dashboard/chat2/{randomNo?}', 'Message_newController@chatview')->name('chat2View');
        Route::get('/dashboard/chat2/chatShow/{cid}', 'PagesController@chat2')->name('chat2WithUser');
        Route::post('/dashboard/chat2/deletesingle', 'Message_newController@deleteSingle')->name('delete2Single');
        Route::post('/dashboard/chat2/unsend', 'Message_newController@unsendChat')->name('unsendChat');
        Route::post('/dashboard/chat2/increase_views', 'Message_newController@increaseViewsCount')->name('increase_views'); 
        Route::post('/dashboard/chat2/{randomNo?}', 'Message_newController@postChat');
        Route::post('postMsg', 'Chat')->name('realTimeChat');
        Route::post('readMsg', 'ChatRead')->name('realTimeChatRead');
        Route::post('/dashboard/chat2/deleteMsgByUser/{msgid}', 'Message_newController@deleteMsgByUser')->name('deleteMsgByUser');
        Route::get('/dashboard/chat2/deleterow/{uid}/{sid}', 'Message_newController@deleteBetweenGET')->name('delete2BetweenGET');
        Route::get('/dashboard/chat2/deleterowall/{uid}/{sid}', 'Message_newController@deleteBetweenGetAll')->name('deleteBetweenGetAll');
        Route::post('/dashboard/chat2/deleteall', 'Message_newController@deleteAll')->name('delete2All');
        Route::post('/dashboard/chat2/chatSet', 'Message_newController@chatSet')->name('chatSet');
        Route::get('/dashboard/chat/chatNotice', 'Message_newController@viewChatNoticeSet')->name('viewChatNotice');
        Route::post('/dashboard/chat/chatNoticeSet', 'Message_newController@chatNoticeSet')->name('chatNoticeSet');
        Route::post('/dashboard/announcement_post', 'Message_newController@announcePost')->name('announcePost');
        Route::get('/dashboard/manual', 'PagesController@manual');
        Route::get('/dashboard/chat2/is_truth/get_remain', 'PagesController@getChatIsTruthRemainQuota')->name('getChatIsTruthRemainQuota');
        Route::post('/dashboard/toggleShowCanMessage', 'Message_newController@ToggleShowCanMessage')->name('toggleShowCanMessage');
        Route::post('/dashboard/logChatWithError', 'PagesController@logChatWithError')->name('logChatWithError');

        Route::post('/dashboard/letTourRead', 'PagesController@letTourRead')->name('letTourRead');

        Route::get('/dashboard/anonymousEvaluationChat/chats', 'EvaluationController@getActiveChats')->name('getAnonymousEvaluationChats');
        Route::post('dashboard/anonymousEvaluationChat/closeChatRoom/{chatid}', 'EvaluationController@closeChatRoom')->name('closeChatRoom');
        Route::get('/dashboard/anonymousEvaluationChat/{evaluationid}', 'EvaluationController@anonymousEvaluationChat')->name('getAnonymousEvaluationChat');
        Route::get('/dashboard/anonymousEvaluationChat/Room/{chatid}', 'EvaluationController@anonymousEvaluationChatRoom')->name('getAnonymousEvaluationChatRoom');
        Route::get('/dashboard/anonymousEvaluationChat/message/{chatid}/{messageid?}', 'EvaluationController@getAnonymousEvaluationChatMessage')->name('getAnonymousEvaluationChatMessage');
        Route::patch('/dashboard/anonymousEvaluationChat/message/{chatid}', 'EvaluationController@readAnonymousEvaluationChatMessage')->name('readAnonymousEvaluationChatMessage');
        Route::post('/dashboard/anonymousEvaluationChat/message/{chatid}', 'EvaluationController@sendAnonymousEvaluationChatMessage')->name('sendAnonymousEvaluationChatMessage');
        Route::patch('/dashboard/anonymousEvaluationChat/revoke/{chatid}', 'EvaluationController@revokeAnonymousEvaluationChatMessage')->name('revokeAnonymousEvaluationChatMessage');
        Route::post('/dashboard/anonymousEvaluationChat/accusation/{chatid}', 'EvaluationController@accusationAnonymousEvaluationChatMessage')->name('accusationAnonymousEvaluationChatMessage');
        Route::get('/dashboard/anonymousEvaluationChat/deleterow/{chatid}/{uid}', 'EvaluationController@deleteBetweenGET')->name('delete2BetweenGETAnonymousEvaluationChatMessage');
        Route::get('/dashboard/anonymousEvaluationChat/deleterowall/{uid}', 'EvaluationController@deleteBetweenGetAll')->name('deleteBetweenGetAllAnonymousEvaluationChatMessage');

        Route::get('/dashboard/evaluation/{uid}', 'PagesController@evaluation');
        Route::post('/dashboard/evaluation/save', 'PagesController@evaluation_save')->name('evaluation');
        Route::post('/dashboard/evaluation/re_content_save', 'PagesController@evaluation_re_content_save')->name('evaluation_re_content');
        Route::post('/dashboard/evaluation/re_content_delete', 'PagesController@evaluation_re_content_delete')->name('evaluation_re_content_delete');
        Route::post('/dashboard/evaluation/delete', 'PagesController@evaluation_delete')->name('evaluation_delete');
        Route::get('/dashboard/evaluation_self', 'PagesController@evaluation_self');
        Route::post('/dashboard/evaluation_self/deleteAll', 'PagesController@evaluation_self_deleteAll')->name('evaDeleteAll'); //new route

        Route::get('/dashboard/evaluation/{uid}', 'PagesController@evaluation');
        Route::post('/dashboard/evaluation/save', 'PagesController@evaluation_save')->name('evaluation');
        Route::post('/dashboard/evaluation/re_content_save', 'PagesController@evaluation_re_content_save')->name('evaluation_re_content');
        Route::post('/dashboard/evaluation/re_content_delete', 'PagesController@evaluation_re_content_delete')->name('evaluation_re_content_delete');
        Route::post('/dashboard/evaluation/delete', 'PagesController@evaluation_delete')->name('evaluation_delete');

        Route::get('/dashboard/evaluation/{uid}', 'PagesController@evaluation');
        Route::post('/dashboard/evaluation/save', 'PagesController@evaluation_save')->name('evaluation');
        Route::post('/dashboard/evaluation/re_content_save', 'PagesController@evaluation_re_content_save')->name('evaluation_re_content');
        Route::post('/dashboard/evaluation/re_content_delete', 'PagesController@evaluation_re_content_delete')->name('evaluation_re_content_delete');
        Route::post('/dashboard/evaluation/delete', 'PagesController@evaluation_delete')->name('evaluation_delete');

        Route::get('/dashboard/banned_warned_list', 'PagesController@banned_warned_list');
        Route::get('/dashboard/visited', 'PagesController@visited');

        //更新拜訪時間
        Route::post('/dashboard/viewuser/update_visited_time', 'PagesController@update_visited_time')->name('update_visited_time');

        Route::middleware("HasReferer:listSeatch2")->group(function (){
            Route::get('/dashboard/viewuser/{uid?}', 'PagesController@viewuser2')->name('viewuser'); //new route
            Route::get('/dashboard/viewuser_re/{uid?}', 'PagesController@viewuser_re')->name('viewuser_re');
        });
        Route::get('/dashboard/viewuser_vvip/{uid?}', 'PagesController@viewuser_vvip')->name('viewuser_vvip'); //new route

		Route::get('/dashboard/switch_other_engroup', 'PagesController@switchOtherEngroup')->name('switch_other_engroup');
		Route::get('/dashboard/switch_engroup_back', 'PagesController@switchEngroupBack')->name('switch_engroup_back');
        Route::get('/dashboard/personalPage', 'PagesController@personalPage'); //new route
        Route::post('/dashboard/personalPage/reportDelete', 'PagesController@report_delete')->name('report_delete');
        Route::post('/dashboard/closeNoticeNewEvaluation', 'PagesController@closeNoticeNewEvaluation')->name('closeNoticeNewEvaluation');
        Route::post('/dashboard/personalPageHideRecordLog', 'PagesController@personalPageHideRecordLog')->name('personalPageHideRecordLog');
        Route::get('/dashboard/adminMsgPage', 'PagesController@adminMsgPage')->name('adminMsgPage');
        Route::post('/dashboard/adminMsgRead/{msgid}', 'PagesController@adminMsgRead');
        Route::post('/dashboard/orderPayFailNotifyIgnore', 'PagesController@orderPayFailNotifyIgnore')->name('orderPayFailNotifyIgnore');


        Route::get('/dashboard/board', 'PagesController@board');
        //Route::get('/dashboard/history', 'PagesController@history');
        //Route::get('/dashboard/fav', 'PagesController@fav');
        Route::get('/dashboard/upgradesuccess', 'PagesController@upgradesuccess');
        //Route::get('/dashboard/search', 'PagesController@search');
        Route::get('/dashboard/search', 'PagesController@search2')->name('listSeatch2');//new route
        Route::post('/dashboard/search', 'PagesController@search2');//new route
        Route::get('/dashboard/search2', 'PagesController@search');
        Route::post('/dashboard/search_key_reset', 'PagesController@search_key_reset');
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

        Route::get('/dashboard/anonymousChat', 'PagesController@anonymousChat');
        Route::post('/dashboard/anonymousChatReport', 'PagesController@anonymous_chat_report')->name('anonymous_chat_report');
        Route::post('/dashboard/anonymousChatMessage', 'PagesController@anonymous_chat_message')->name('anonymous_chat_message');
        Route::post('/dashboard/anonymousChatSave', 'PagesController@anonymous_chat_save')->name('anonymous_chat_save');
        Route::get('/dashboard/anonymous_chat_forbid_list', 'PagesController@anonymous_chat_forbid_list')->name('anonymous_chat_forbid_list');
        /*
        |--------------------------------------------------------------------------
        | Real Auth
        |--------------------------------------------------------------------------
        */
        Route::get('/dashboard/real_auth', 'PagesController@showRealAuth')->name('real_auth');
        Route::get('/dashboard/real_auth_break', 'PagesController@forgetRealAuthType')->name('forget_real_auth');
        Route::get('/dashboard/real_auth_process_check', 'PagesController@checkIsInRealAuthProcess')->name('check_is_in_real_auth_process');
        Route::post('/dashboard/real_auth_forward', 'PagesController@forwardRealAuth')->name('real_auth_forward');
        Route::get('/dashboard/famous_auth', 'PagesController@showFamousAuth')->name('famous_auth');
        Route::post('/dashboard/famous_auth/save', 'PagesController@saveFamousAuth')->name('famous_auth_save');
        Route::get('/dashboard/famous_auth/delete_pic', 'PagesController@deleteFamousAuthPic')->name('famous_auth_pic_delete');
        Route::get('/dashboard/beauty_auth', 'PagesController@showBeautyAuth')->name('beauty_auth');
        Route::post('/dashboard/beauty_auth/save', 'PagesController@saveBeautyAuth')->name('beauty_auth_save');
        Route::get('/dashboard/beauty_auth/delete_pic', 'PagesController@deleteBeautyAuthPic')->name('beauty_auth_pic_delete');        
        Route::post('/dashboard/real_auth_update_profile','PagesController@savePassedRealAuthModify')->name('real_auth_update_profile');
        Route::get('/dashboard/tag_display_settings', 'PagesController@showTagDisplaySettings')->name('tag_display_settings');
        Route::post('/dashboard/tag_display_settings', 'PagesController@tagDisplaySet')->name('tagDisplaySet');
        /*
        |--------------------------------------------------------------------------
        | LINE
        |--------------------------------------------------------------------------
        */
        Route::post('/dashboard/line/callback', 'LineNotify@lineNotifyCallback')->name('lineNotifyCallback');
        Route::get('/dashboard/line/notifyCancel', 'LineNotify@lineNotifyCancel')->name('lineNotifyCancel');

        Route::get('/dashboard/setTinySetting','PagesController@setTinySetting')->name('setTinySetting');
        Route::get('/dashboard/getTinySetting','PagesController@getTinySetting')->name('getTinySetting');
        Route::post('/dashboard/setBlurryToUser','PagesController@setBlurryToUser')->name('setBlurryToUser');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin
    |--------------------------------------------------------------------------
    */
    Route::group(['namespace' => 'Admin', 'middleware' => ['ReadOnly', 'Admin']], function () {
        Route::match(['get', 'post'], 'users/VIP/ECCancellations/readOnly', 'PagesController@showECCancellations')->name('users/VIP/ECCancellations/readOnly');
        Route::get('stats/vip/paid/readOnly', 'StatController@vipPaid')->name('stats/vip/paid/readOnly');
        Route::post('users/VIPToggler/readOnly', 'UserController@toggleVIP')->name('VIPToggler/readOnly');
        Route::post('users/toggleHidden/readOnly', 'UserController@toggleHidden')->name('toggleHidden/readOnly');        
        Route::get('users/advInfo/{id}/readOnly', 'UserController@advInfo')->name('users/advInfo/readOnly');
        Route::get('to/{id}/readOnly', 'UserController@showAdminMessenger')->name('AdminMessage/readOnly');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures/readOnly/GET');
        Route::post('users/pictures', 'UserController@searchUserPictures')->name('users/pictures/readOnly');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify/readOnly');
        Route::get('users/advInfo/editPic_sendMsg/{id}', 'UserController@editPic_sendMsg')->name('users/pictures/editPic_sendMsg/readOnly');
        Route::post('send/{id}', 'UserController@sendAdminMessage')->name('admin/send/readOnly');
        Route::group(['prefix'=>'users/message'], function() {
            Route::post('multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple/readOnly');
        });
    });    
        
    Route::group(['prefix' => 'admin/queue', 'middleware' => 'Admin', 'namespace' => 'Admin\Queue'], function () {
        Route::get('/', ShowQueueMonitorController::class)->name('queue-monitor-index');
        Route::delete('monitors/{monitor}', DeleteMonitorController::class)->name('queue-monitor-destroy');
        Route::patch('monitors/retry/{monitor}', RetryMonitorController::class)->name('queue-monitor-retry');
        Route::delete('purge', PurgeMonitorsController::class)->name('queue-monitor-purge');
    });

    Route::group(['prefix' => 'admin', 'namespace' => 'Admin', 'middleware' => 'Admin'], function () {
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


        Route::get('dashboard/accessPermission', 'DashboardController@accessPermission')->name('accessPermission');
        Route::get('dashboard/accessPermission/show', 'DashboardController@showJuniorAdmin')->name('showJuniorAdmin');
        Route::post('dashboard/accessPermission/create', 'DashboardController@juniorAdminCreate')->name('juniorAdminCreate');
        Route::post('dashboard/accessPermission/edit', 'DashboardController@juniorAdminEdit')->name('juniorAdminEdit');
        Route::post('dashboard/accessPermission/delete/{userid}', 'DashboardController@juniorAdminDelete')->name('juniorAdminDelete');

        Route::get('dashboard/juniorAdminCheckRecord', 'DashboardController@juniorAdminCheckRecord')->name('juniorAdminCheckRecord');
        Route::post('dashboard/juniorAdminCheckRecordShow', 'DashboardController@juniorAdminCheckRecordShow')->name('juniorAdminCheckRecordShow');

        Route::get('dashboard/paymentFlowChoose', 'DashboardController@paymentFlowChoose')->name('paymentFlowChoose');
        Route::get('dashboard/paymentFlowChoose/show', 'DashboardController@showPaymentFlowChoose')->name('showPaymentFlowChoose');
        Route::post('dashboard/paymentFlowChoose/edit', 'DashboardController@paymentFlowChooseEdit')->name('paymentFlowChooseEdit');

        Route::get('dashboard', 'DashboardController@index');

        Route::get('opcacheStatus', 'PagesController@opcacheStatus');
        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */
        //Route::get('manualSQL', 'UserController@manualSQL');
        //Route::get('querier', 'UserController@querier')->name('querier');
        Route::get('manualDeploy', [DeployController::class, 'manualDeploy']);
        Route::resource('manager', 'UserController', ['except' => ['create', 'show']]);
        Route::post('users/search', 'UserController@search')->name('users/manager');
        Route::get('users/search', 'UserController@index')->name('users/manager/GET');
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
        Route::get('users/getMessageFromRoomId', 'UserController@getMessageFromRoomId')->name('users/getMessageFromRoomId');
        Route::get('users/getAdminMessageRecordDetailFromRoomId', 'UserController@getAdminMessageRecordDetailFromRoomId')->name('users/getAdminMessageRecordDetailFromRoomId');

        Route::post('users/banUserWithDayAndMessage', 'UserController@banUserWithDayAndMessage');
        Route::get('users/pictures', 'UserController@showUserPictures')->name('users/pictures');
        Route::get('users/pictures', 'UserController@searchUserPictures')->name('users/pictures');
        Route::post('users/pictures/modify', 'UserController@modifyUserPictures')->name('users/pictures/modify');
        Route::get('users/reported/count', 'UserController@showReportedCountPage')->name('users/reported/count/GET');
        Route::post('users/reported/count', 'UserController@showReportedCountList')->name('users/reported/count');
        Route::get('users/filterByInfo', 'UserController@showFilterByInfoList')->name('users/filterByInfo');
        Route::post('users/filterByInfo', 'UserController@showFilterByInfoList');
        Route::get('users/filterByInfoIgnore', 'UserController@switchFilterByInfoIgnore')->name('users/filterByInfoIgnore');
        Route::get('users/board', 'PagesController@board')->name('users/board');
        Route::post('users/board', 'PagesController@board')->name('users/board/search');
        Route::get('users/board/delete/{id}', 'UserController@deleteBoard')->name('users/board/delete');
        Route::get('users/posts', 'UserController@postsList')->name('users/posts');
        Route::get('users/posts/delete/{id}', 'UserController@postsDelete')->name('users/posts/delete');
        Route::post('users/posts/prohibit', 'UserController@toggleUser_prohibit_posts');
        Route::post('users/posts/access', 'UserController@toggleUser_access_posts');
        Route::post('users/accountStatus_admin', 'UserController@accountStatus_admin');
        Route::post('users/accountStatus_user', 'UserController@accountStatus_user');
        Route::get('users/messageBoard', 'UserController@messageBoardList')->name('users/messageBoardList');
        Route::post('users/messageBoard/delete/{id}', 'UserController@deleteMessageBoard');
        Route::post('users/messageBoard/hideMsg/{id}', 'UserController@hideMessageBoard');
        Route::post('users/messageBoard/edit/{id}', 'UserController@editMessageBoard');
        Route::get('users/memberList', 'UserController@memberList')->name('users/memberList');
        Route::post('users/memberList', 'UserController@searchMemberList')->name('searchMemberList');
        Route::get('users/picMemberList', 'UserController@picMemberList')->name('users/picMemberList');
        Route::post('users/picMemberList', 'UserController@searchPicMemberList')->name('searchPicMemberList');
        Route::post('users/applyPicMemberList', 'UserController@applyPicMemberList')->name('applyPicMemberList');
        
        Route::post('users/toggleUserWarned', 'UserController@toggleUserWarned');

        //預算及車馬費警示警示
        Route::post('users/warnBudget', 'UserController@warnBudget');
        //預算及車馬費警示警示

        Route::get('users/closeAccountReason', 'UserController@closeAccountReason')->name('users/closeAccountReasonList');
        Route::get('users/closeAccountDetail', 'UserController@closeAccountDetail');

        Route::post('users/forum_toggle', 'UserController@forum_toggle')->name('forum_toggle');
        Route::post('users/check_extend', 'UserController@check_extend')->name('check_extend');
        Route::post('users/check_extend_by_login_time', 'UserController@check_extend_by_login_time')->name('check_extend_by_login_time');

        Route::get('users/anonymousChat', 'UserController@showAnonymousChatPage')->name('users/showAnonymousChatPage');
        Route::get('users/searchAnonymousChat', 'UserController@searchAnonymousChatPage')->name('users/searchAnonymousChatPage');
        Route::get('users/searchAnonymousChatReport', 'UserController@searchAnonymousChatReport')->name('users/searchAnonymousChatReport');
        Route::post('users/deleteAnonymousChatRow', 'UserController@deleteAnonymousChatRow')->name('users/deleteAnonymousChatRow');
        Route::post('users/deleteAnonymousChatReportRow', 'UserController@deleteAnonymousChatReportRow')->name('users/deleteAnonymousChatReportRow');
        Route::post('users/deleteAnonymousChatReportAll', 'UserController@deleteAnonymousChatReportAll')->name('users/deleteAnonymousChatReportAll');

        Route::post('users/userBlock', 'UserController@userBlock')->name('users/userBlock'); //共用式
        Route::post('users/userBlockRemove', 'UserController@userBlockRemove')->name('users/userBlockRemove'); //共用式

        Route::get('users/ip/{ip}', 'UserController@getIpUsers')->name('getIpUsers');
		Route::get('users/getLog', 'UserController@getUsersLog')->name('getUsersLog');
        Route::post('users/logUserLoginHide', 'UserController@logUserLoginHide')->name('logUserLoginHide');
        Route::post('users/observe_user', 'UserController@observe_user')->name('observe_user');
        Route::post('users/observe_user_remove', 'UserController@observe_user_remove')->name('observe_user_remove');
        Route::get('users/observe_user_list', 'UserController@observe_user_list')->name('observe_user_list');
        Route::post('users/track_user', 'UserController@track_user')->name('track_user');
        Route::post('users/track_user_remove', 'UserController@track_user_remove')->name('track_user_remove');
        Route::get('users/track_user_list', 'UserController@track_user_list')->name('track_user_list');

        Route::group(['prefix'=>'users/message'], function(){
            Route::get('record/all', 'UserController@showAdminMessageAllRecord')->name('AdminMessageAllRecord');
            Route::get('record/{id}', 'UserController@showAdminMessageRecord')->name('AdminMessageRecord');            
            Route::get('showBetween/{id1}/{id2}', 'UserController@showMessagesBetween')->name('admin/showMessagesBetween');
            Route::get('to/{id}', 'UserController@showAdminMessenger')->name('AdminMessage');
            Route::get('to/{id}/{mid}', 'UserController@showAdminMessengerWithMessageId')->name('AdminMessengerWithMessageId');
            Route::get('unreported/to/{id}/{reported_id}/{pic_id?}/{isPic?}/{isReported?}', 'UserController@showAdminMessengerWithReportedId')->name('AdminMessengerWithReportedId');
            Route::get('anonymous-checked/to/{id}/{evaluation_id}', 'UserController@showAdminMessengerAfterAnonymousContentChecked');
            Route::post('send/{id}', 'UserController@sendAdminMessage')->name('admin/send');
            Route::post('multiple/send', 'UserController@sendAdminMessageMultiple')->name('admin/send/multiple');
            Route::get('search', 'UserController@showMessageSearchPage')->name('users/message/search');
            Route::get('search/reported/{date_start?}/{date_end?}', 'UserController@showReportedMessages')->name('search/reported');
            Route::post('search', 'UserController@searchMessage');
            Route::post('modify', 'UserController@modifyMessage')->name('users/message/modify');
            Route::post('delete', 'UserController@deleteMessage')->name('users/message/delete');
            Route::post('edit', 'UserController@editMessage')->name('users/message/edit');
            Route::post('handle', 'UserController@handleMessage')->name('users/message/handle');

            Route::get('sendUserMessage', 'UserController@showSendUserMessage')->name('admin/showSendUserMessage');
            Route::post('sendUserMessage', 'UserController@sendUserMessage')->name('admin/sendUserMessage');
            Route::post('sendUserMessageFindUserInfo', 'UserController@sendUserMessageFindUserInfo')->name('sendUserMessageFindUserInfo');
        });

        Route::group(['prefix'=>'users/spam_text_message'], function(){
            Route::get('search', 'UserController@showSpamTextMessage')->name('showSpamTextMessage');
            Route::post('search', 'UserController@searchSpamTextMessage')->name('searchSpamTextMessage');
        });

        Route::group(['prefix'=>'users/evaluation'], function(){
            Route::post('modify', 'UserController@modifyContent')->name('evaluationModifyContent');
            Route::post('adminComment', 'UserController@adminComment')->name('evaluationAdminComment');
            Route::post('delete', 'UserController@evaluationDelete')->name('evaluationDelete');
            Route::post('check', 'UserController@evaluationCheck')->name('evaluationCheck');
            Route::get('showPic/{eid}/{uid}', 'UserController@showEvaluationPic')->name('showEvaluationPic');
            Route::post('picDelete/{picID}', 'UserController@evaluationPicDelete')->name('evaluationPicDelete');
            Route::post('picAdd', 'UserController@evaluationPicAdd')->name('evaluationPicAdd');
        });

        Route::group(['prefix'=>'users/phone'], function(){
            Route::post('modify', 'UserController@modifyPhone')->name('phoneModify');
            Route::post('delete', 'UserController@deletePhone')->name('phoneDelete');
            Route::post('search', 'UserController@searchPhone');
        });
        Route::group(['prefix'=>'users/email'], function(){
            Route::post('modify', 'UserController@modifyEmail')->name('emailModify');
            Route::post('search', 'UserController@searchEmail');
        });
        
        Route::post('users/bannedLog/delete', 'UserController@deleteBannedLog')->name('bannedLogDelete');
        Route::post('users/warnedLog/delete', 'UserController@deleteWarnedLog')->name('warnedLogDelete');

        Route::post('advanceVerify', 'UserController@advanceVerify')->name('advanceVerify');

        Route::get('statistics', 'UserController@statisticsReply')->name("statistics");
        Route::post('statistics', 'UserController@statisticsReply');
        
        Route::get('greetingRate/show', 'UserController@showAvgMedian');
        Route::post('greetingRate/modify', 'UserController@modifyGreetingRateCalculations')->name('admin/greetingRate/modify');
        
        Route::get('users/pics/reported', 'UserController@showReportedPicsPage')->name('users/pics/reported/GET');
        Route::get('users/reported', 'UserController@showReportedUsersPage')->name('users/reported/GET');
        Route::post('users/reported', 'UserController@showReportedUsersList')->name('users/reported');
        Route::post('users/reported/details/{reported_id}/{users?}/{reportedData?}', 'UserController@showReportedDetails')->name('users/reported/details');
        //曾被檢舉
        Route::get('users/pics/reported/{date_start?}/{date_end?}/{reported_id?}', 'UserController@searchReportedPics')->name('users/pics/reported/EXTRA');
        Route::get('users/reported/{date_start?}/{date_end?}/{reported_id?}', 'UserController@showReportedUsersList')->name('users/reported/EXTRA');
        Route::get('users/message/search/reported/{date_start?}/{date_end?}/{reported_id?}', 'UserController@showReportedMessages')->name('users/message/search/reported');
        Route::post('users/reported/handle/status', 'UserController@reportedIsWrite')->name('users.reported.isWrite');
        Route::post('users/message/handle/status', 'UserController@messageIsWrite')->name('users.message.isWrite');

        Route::post('users/pics/reported', 'UserController@searchReportedPics')->name('users/pics/reported');
        Route::get('users/basic_setting', 'UserController@basicSetting')->name('users/basic_setting/GET');
        Route::post('users/basic_setting', 'UserController@doBasicSetting')->name('users/basic_setting');
        Route::get('users/bannedList', 'UserController@showBannedList')->name('users/bannedList');
        Route::get('users/switch', 'UserController@showUserSwitch')->name('users/switch');
        Route::post('users/switch', 'UserController@switchSearch')->name('users/switch/search');
        Route::get('users/changePassword', 'UserController@changePassword')->name('users/changePassword/GET');
        Route::post('users/changePassword', 'UserController@changePassword')->name('users/changePassword');
        Route::get('users/invite', 'UserController@getInvite');
        Route::get('users/switch/{id}', 'UserController@switchToUser')->name('users/switch/to');
        Route::post('users/invite', 'UserController@postInvite');
        Route::post('users/genderToggler', 'UserController@toggleGender')->name('genderToggler');
        Route::post('users/isRealToggler', 'UserController@TogglerIsReal')->name('isRealToggler');
        Route::post('users/isChatToggler', 'UserController@TogglerIsChat')->name('isChatToggler');
        Route::post('users/VIPToggler', 'UserController@toggleVIP')->name('VIPToggler');
        Route::post('users/toggleHidden', 'UserController@toggleHidden')->name('toggleHidden');        
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
        Route::get('users/multiple-login', 'UserController@multipleLogin')->name('users/multipleLogin/GET');
        Route::post('users/multiple-login', 'UserController@multipleLogin')->name('users/multipleLogin');
        Route::get('users/customizeMigrationFiles', 'UserController@customizeMigrationFiles')->name('users/customize_migration_files/GET');
        Route::post('users/customizeMigrationFiles', 'UserController@customizeMigrationFiles')->name('users/customize_migration_files');
        Route::match(['get', 'post'], 'users/VIP/ECCancellations', 'PagesController@showECCancellations')->name('users/VIP/ECCancellations');
        Route::get('announcement', 'UserController@showAdminAnnouncement')->name('admin/announcement');
        Route::get('announcement/edit/{id}', 'UserController@showAdminAnnouncementEdit')->name('admin/announcement/edit');
        Route::post('announcement/save', 'UserController@saveAdminAnnouncement')->name('admin/announcement/save');
        Route::get('announcement/delete/{id?}', 'UserController@deleteAdminAnnouncement')->name('admin/announcement/delete');
        Route::get('announcement/new', 'UserController@showNewAdminAnnouncement')->name('admin/announcement/new/GET');
        Route::post('announcement/new', 'UserController@newAdminAnnouncement')->name('admin/announcement/new');
        Route::get('announcement/read/{id}', 'UserController@showReadAnnouncementUser')->name('admin/announcement/read');

        //vvip
        Route::get('users/VVIP', 'UserController@viewVvipApplication')->name('users/VVIP');
        Route::post('users/VVIP_edit', 'UserController@editVvipApplication')->name('users/VVIP_edit');
        Route::post('users/get_prove_img', 'UserController@vvip_get_prove_img')->name('get_prove_img');
        Route::post('users/vvipInfo_admin_edit', 'UserController@vvipInfo_admin_edit')->name('users/vvipInfo_admin_edit');
        Route::post('users/vvipInfo_status_toggle', 'UserController@vvipInfo_status_toggle')->name('users/vvipInfo_status_toggle');
        Route::get('users/VVIP_margin_deposit', 'VvipController@viewVvipMarginDeposit')->name('users/VVIP_margin_deposit');
        Route::get('users/VVIP_margin_deposit/edit/{user_id}', 'VvipController@editVvipMarginDeposit')->name('users/VVIP_margin_deposit/edit');
        Route::post('users/VVIP_margin_deposit/save/{user_id}', 'VvipController@updateVvipMarginDeposit')->name('users/VVIP_margin_deposit/save');
        Route::get('users/VVIP_cancellation_list', 'VvipController@viewVvipCancellationList')->name('users/VVIP_cancellation_list');
        Route::post('users/VVIP_cancellation/save', 'VvipController@updateVvipCancellation')->name('users/VVIP_cancellation/save');
        //Route::get('users/VVIP_invite', 'UserController@viewVvipInvite')->name('users/VVIP_invite');
        Route::get('users/VvipSelectionReward', 'UserController@viewVvipSelectionRewardApply');
        Route::post('users/VvipSelectionRewardUpdate', 'UserController@vvipSelectionRewardApplyUpdate')->name('vvipSelectionRewardApplyUpdate');
        Route::post('users/vvipSelectionRewardDeleteKey', 'UserController@vvipSelectionRewardApplyDeleteKey')->name('vvipSelectionRewardApplyDeleteKey');
        Route::get('users/vvipSelectionRewardList', 'UserController@getVvipSelectionRewardData')->name('vvipSelectionReward/list');
        Route::post('users/vvipSelectionRewardApplyKeyUpdate', 'UserController@vvipSelectionRewardApplyKeyUpdate')->name('vvipSelectionRewardApplyKeyUpdate');
        Route::post('users/vvipSelectionRewardApplyAddData', 'UserController@vvipSelectionRewardApplyAddData')->name('vvipSelectionRewardApplyAddData');
        Route::get('users/vvipSelectionRewardApplyList/{id}', 'UserController@viewVvipSelectionRewardApplyList');
        Route::post('users/vvipSelectionRewardApplyListUpdate', 'UserController@vvipSelectionRewardApplyListUpdate')->name('vvipSelectionRewardApplyListUpdate');


        Route::get('users/vip/search', 'UserController@vipIndex')->name('users/vip');
        Route::post('users/vip/search', 'UserController@vipSearch')->name('users/vip/search');        
        Route::post('users/short_message/search', 'UserController@short_message_search')->name('users/short_message/search');
        Route::post('users/vip/period/extend', 'UserController@periodExtend')->name('users/vip/period/extend');
        Route::post('users/vip/period/transfer', 'UserController@periodTransfer')->name('users/vip/period/transfer');
        Route::post('users/vip/adv_auth_count/save', 'UserController@updateVipAdvandceAuthCount')->name('users/vip/adv_auth_count/save');

        Route::get('faq', 'UserController@showFaq')->name('admin/faq');
        Route::get('faq/edit/{id}', 'UserController@showFaqEdit')->name('admin/faq/edit');
        Route::post('faq/save', 'UserController@saveFaq')->name('admin/faq/save');
        Route::post('faq/answer/save', 'UserController@saveAnsFromFaq')->name('admin/faq/answer/save');
        Route::post('faq/setting/save', 'UserController@saveSettingFromFaq')->name('admin/faq/setting/save');
        Route::get('faq/delete/{id?}', 'UserController@deleteFaq')->name('admin/faq/delete');
        Route::get('faq/new', 'UserController@showNewFaq')->name('admin/faq/new/GET');
        Route::post('faq/new', 'UserController@newFaq')->name('admin/faq/new');

        Route::get('faq_group', 'UserController@showFaqGroup')->name('admin/faq_group');
        Route::get('faq_group/edit/{id}', 'UserController@showFaqGroupEdit')->name('admin/faq_group/edit');
        Route::post('faq_group/save', 'UserController@saveFaqGroup')->name('admin/faq_group/save');
        Route::get('faq_group/delete/{id?}', 'UserController@deleteFaqGroup')->name('admin/faq_group/delete');
        Route::get('faq_group/new', 'UserController@showNewFaqGroup')->name('admin/faq_group/new/GET');
        Route::post('faq_group/new', 'UserController@newFaqGroup')->name('admin/faq_group/new');
        Route::post('faq_group/save_act', 'UserController@saveFaqGroupAct')->name('admin/faq_group/save_act');

        Route::get('faq_choice/{id}', 'UserController@showFaqChoice')->name('admin/faq_choice');
        Route::get('faq_choice/edit/{id}', 'UserController@showFaqChoiceEdit')->name('admin/faq_choice/edit');
        Route::post('faq_choice/save/{id}', 'UserController@saveFaqChoice')->name('admin/faq_choice/save');
        Route::get('faq_choice/delete/{id?}', 'UserController@deleteFaqChoice')->name('admin/faq_choice/delete');
        Route::get('faq_choice/new/{id}', 'UserController@showNewFaqChoice')->name('admin/faq_choice/new/GET');
        Route::post('faq_choice/new/{id}', 'UserController@newFaqChoice')->name('admin/faq_choice/new');

        Route::get('masterwords', 'UserController@showMasterwords')->name('admin/masterwords');
        Route::get('masterwords/edit/{id}', 'UserController@showAdminMasterWordsEdit')->name('admin/masterwords/edit');
        Route::post('masterwords/save', 'UserController@saveAdminMasterWords')->name('admin/masterwords/save');
        Route::get('masterwords/delete/{id?}', 'UserController@deleteAdminMasterWords')->name('admin/masterwords/delete');
        Route::get('masterwords/new', 'UserController@showNewAdminMasterWords')->name('admin/masterwords/new/GET');
        Route::post('masterwords/new', 'UserController@newAdminMasterWords')->name('admin/masterwords/new');
        Route::get('masterwords/read/{id}', 'UserController@showReadMasterWords')->name('admin/masterwords/read');

        Route::get('web/announcement', 'UserController@showWebAnnouncement')->name('admin/web/announcement');
        Route::get('/chat', 'MessageController@chatview')->name('admin/chat');
        Route::get('/chat/{cid}', 'PagesController@chat');
        Route::post('/chat', 'MessageController@postChat');
        Route::get('commontext', 'UserController@showAdminCommonText')->name('admin/commontext');
        Route::post('commontext/save', 'UserController@saveAdminCommonText')->name('admin/commontext/save');
        Route::get('getAdminActionLog', 'UserController@adminActionLog')->name('admin/getAdminActionLog');
        Route::get('getEssenceStatisticsRecord', 'UserController@getEssenceStatisticsRecord')->name('admin/getEssenceStatisticsRecord');

        Route::get('users/inactive', 'UserController@inactiveUsers')->name('inactive/GET');
        Route::post('users/inactive', 'UserController@inactiveUsers')->name('inactive');
        Route::get('users/activate/token/{token}', 'UserController@activateUser')->name('activateUser');
        Route::get('stats/vip', 'StatController@vip')->name('stats/vip');
        Route::get('stats/other', 'StatController@other')->name('stats/vip/other/GET');
        Route::post('stats/other', 'StatController@other')->name('stats/vip/other');
        Route::get('stats/vip/paid', 'StatController@vipPaid')->name('stats/vip/paid');
        Route::get('stats/vip_log/{id}', 'StatController@vipLog')->name('stats/vip_log');
        Route::get('stats/cron_log', 'StatController@cronLog')->name('stats/cron_log');
        Route::get('stats/date_file_log', 'StatController@datFileLog')->name('stats/date_file_log');
        Route::get('stats/schedulerLog', 'StatController@schedulerLog')->name('schedulerLog');
        Route::get('stats/set_autoBan', 'StatController@set_autoBan')->name('stats/set_autoBan');
        Route::post('stats/set_autoBan_add', 'StatController@set_autoBan_add')->name('stats/set_autoBan_add');
        Route::get('stats/set_autoBan_del/{id?}', 'StatController@set_autoBan_del')->name('stats/set_autoBan_del');
        Route::get('check', 'UserController@showAdminCheck')->name('admin/check');
        Route::get('checkNameChange', 'UserController@showAdminCheckNameChange')->name('admin/checkNameChange');
        Route::get('checkGenderChange', 'UserController@showAdminCheckGenderChange')->name('admin/checkGenderChange');
        Route::post('checkNameChange', 'UserController@AdminCheckNameChangeSave');
        Route::post('checkGenderChange', 'UserController@AdminCheckGenderChangeSave');
        Route::post('checkPicUpload', 'UserController@AdminCheckPicUploadSave');
        Route::get('checkExchangePeriod', 'UserController@showAdminCheckExchangePeriod')->name('admin/checkExchangePeriod');
		Route::get('editRealAuth_sendMsg/{id}', 'UserController@editRealAuth_sendMsg')->name('admin/editRealAuth_sendMsg');
        Route::get('real_auth/msglib/create/editRealAuth_sendMsg/{id?}', 'UserController@addMessageLibRealAuth')->name('admin/addMessageLibRealAuth');
        Route::get('checkRealAuth', 'UserController@showAdminCheckRealAuth')->name('admin/checkRealAuth');
        Route::get('checkFamousAuthForm/{user_id}', 'UserController@showAdminCheckFamousAuthForm')->name('admin/checkFamousAuthForm');
        Route::get('checkBeautyAuthForm/{user_id}', 'UserController@showAdminCheckBeautyAuthForm')->name('admin/checkBeautyAuthForm');
        Route::post('passRealAuth', 'UserController@passRealAuth')->name('admin/passRealAuth');
        Route::post('cancelPassRealAuth', 'UserController@cancelPassRealAuth')->name('admin/cancelPassRealAuth');
        Route::post('passRealAuthModify', 'UserController@passRealAuthModify')->name('admin/passRealAuthModify');
        Route::get('checkPicUpload', 'UserController@showAdminCheckPicUpload')->name('admin/checkPicUpload');
        Route::post('checkExchangePeriod', 'UserController@AdminCheckExchangePeriodSave');
        Route::get('checkAnonymousContent', 'UserController@showAdminCheckAnonymousContent')->name('admin/checkAnonymousContent');
        Route::post('checkAnonymousContent', 'UserController@AdminCheckAnonymousContentSave');
        Route::get('anonymous/showChatMessage/{chat_id}', 'UserController@showAnonymousChatMessage')->name('admin/showAnonymousChatMessage');
        Route::get('anonymous/showChatMessageBetweeInAdminCheck/{evaluate_from}/{evaluate_to}', 'UserController@showAdminCheckAnonymousBetweenMessages')->name('admin/showAdminCheckAnonymousBetweenMessages');
        Route::get('roleManage', 'UserController@adminRole')->name('admin/role');
        Route::post('roleEdit', 'UserController@adminRoleEdit')->name('admin/role/edit');
        Route::get('users/picturesSimple', 'UserController@showUserPicturesSimple')->name('users/picturesSimple');
        Route::get('users/picturesSimpleSearch', 'UserController@searchUserPicturesSimple')->name('users/picturesSimpleSearch');
        Route::post('users/suspicious_user_toggle', 'UserController@suspicious_user_toggle')->name('users/suspicious_user_toggle');
        Route::get('users/suspiciousUser', 'UserController@suspiciousUser')->name('users/suspiciousUser');
        Route::get('users/WarnedOrBannedLog/{logType}/{user_id}', 'UserController@isEverWarnedOrBannedLog');
        Route::get('users/commitUser', 'UserController@commitUser')->name('users/commitUser');

        Route::post('users/suspicious_list_count_set_change', 'UserController@suspicious_list_count_set_change')->name('users/suspicious_list_count_set_change');

        //訂單
        Route::get('order', 'OrderController@index')->name('order');
        Route::get('order/list', 'OrderController@getOrderData')->name('order/list');
//        Route::post('order/orderGeneratorById', 'OrderController@orderGeneratorById')->name('order/orderGeneratorById');
        Route::get('order/orderEcPayCheck', 'OrderController@orderEcPayCheck')->name('order/orderEcPayCheck');
        Route::get('order/orderFunPointPayCheck', 'OrderController@orderFunPointPayCheck')->name('order/orderFunPointPayCheck');
        Route::post('order/order_log/list', 'OrderController@getOrderLogListByOrderId')->name('order/order_log/list');
        Route::get('order/orderCheckByServiceNameOrOrderId', 'OrderController@orderCheckByServiceNameOrOrderId')->name('order/orderCheckByServiceNameOrOrderId');
        Route::get('order/list/{user_id}', 'OrderController@getOrderDataByUserId');

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
        Route::get('users/ignoreDuplicate', 'FindPuppetController@switchIgnore')->name('ignoreDuplicate');
		Route::get('users/showDuplicate', 'FindPuppetController@display');
        Route::get('users/checkDuplicate', 'FindPuppetController@entrance');
        Route::get('users/showLogBk', 'FindPuppetController@displayDetail');
        Route::get('users/compare_login_time', 'FindPuppetController@compare_login_time_show');
        Route::post('users/compare_login_time', 'FindPuppetController@compare_login_time');
        Route::get('users/get_multi_account_mail_num_list','FindPuppetController@get_multi_account_mail_num_list')->name('showDuplicate_get_multi_account_mail_num_list');
        Route::get('users/get_newer_manual_stay_online_time_list','FindPuppetController@get_newer_manual_stay_online_time_list')->name('showDuplicate_get_newer_manual_stay_online_time_list');
        Route::get('too_many_requests', 'PagesController@tooManyRequests')->name('tooMantRequests');


        Route::get('users/picturesSimilar', 'UserController@UserPicturesSimilar')->name('users/picturesSimilar');
        Route::get('users/picturesSimilarLog', 'UserController@UserPicturesSimilarLog')->name('users/picturesSimilarLog');
        Route::get('users/picturesSimilar/job:create', 'UserController@UserPicturesSimilarJobCreate');
        Route::get('users/picturesCompare/job:create', 'UserController@UserImagesCompareJobCreate');
        Route::post('users/picturesSimilar/block:toggle', 'UserController@admin_user_block_toggle')->withoutMiddleware('Admin');
        Route::post('users/picturesSimilar/suspicious:toggle', 'UserController@admin_user_suspicious_toggle')->withoutMiddleware('Admin');
        Route::post('users/picturesSimilar/image:delete', [\App\Http\Controllers\ImageController::class, 'admin_user_image_delete'])->withoutMiddleware('Admin');
        Route::post('users/picturesSimilar/avatar:delete', [\App\Http\Controllers\ImageController::class, 'admin_user_avatar_delete'])->withoutMiddleware('Admin');
        Route::post('users/picturesSimilar/pictures:delete/all', [\App\Http\Controllers\ImageController::class, 'admin_user_pictures_all_delete'])->withoutMiddleware('Admin');
        Route::get('users/message/check', 'UserController@messageCheck')->name("users.message.check");

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */
        Route::resource('roles', 'RoleController', ['except' => ['show']]);
        Route::post('roles/search', 'RoleController@search');
        Route::get('roles/search', 'RoleController@index');

        Route::post('check/step1', 'UserController@member_profile_check_over')->name('admin/member_profile_check_over');
        Route::get('ban_information', 'UserController@ban_information');
        Route::post('users/little_update_profile', 'UserController@little_update_profile');
        Route::get('admin_item_folder_manage', 'AdminController@admin_item_folder_manage')->name('admin_item_folder_manage');
        Route::post('admin_item_folder_create', 'AdminController@admin_item_folder_create')->name('admin_item_folder_create');
        Route::post('admin_item_folder_delete', 'AdminController@admin_item_folder_delete')->name('admin_item_folder_delete');
        Route::post('admin_item_folder_update', 'AdminController@admin_item_folder_update')->name('admin_item_folder_update');

        //進階資訊統計工具
        Route::get('users/informationStatistics', 'UserController@informationStatistics')->name('users/informationStatistics');

        //中長期會員管理列表
        Route::get('users/medium_long_term_without_adv_verification_list', 'UserController@medium_long_term_without_adv_verification_list')->name('users/medium_long_term_without_adv_verification_list');
        Route::post('users/medium_long_term_without_adv_verification_user_remove', 'UserController@medium_long_term_without_adv_verification_user_remove')->name('medium_long_term_without_adv_verification_user_remove');
        Route::post('users/medium_long_term_without_adv_verification_communication_count_set_change', 'UserController@medium_long_term_without_adv_verification_communication_count_set_change')->name('medium_long_term_without_adv_verification_communication_count_set_change');


        //廣告紀錄統計
        Route::get('admin/advertiseStatistics', 'UserController@advertiseStatistics')->name('admin/advertiseStatistics');

        //使用者停留時間查看
        Route::get('admin/user_record_view', 'UserController@user_record_view')->name('admin/user_record_view');
        Route::get('admin/user_regist_time_view', 'UserController@user_regist_time_view')->name('admin/user_regist_time_view');
        Route::get('admin/user_visited_time_view', 'UserController@user_visited_time_view')->name('admin/user_visited_time_view');
        Route::get('admin/user_online_time_view', 'UserController@user_online_time_view')->name('admin/user_online_time_view');
        Route::get('admin/user_page_online_time_view', 'UserController@user_page_online_time_view')->name('admin/user_page_online_time_view');
        Route::get('admin/user_page_online_time_view_user_paginate', 'UserController@user_page_online_time_view_user_paginate')->name('admin/user_page_online_time_view_user_paginate');
        Route::get('admin/stay_online_record_page_name_view', 'UserController@stay_online_record_page_name_view')->name('admin/stay_online_record_page_name_view');
        Route::get('admin/stay_online_record_page_name_form', 'UserController@stay_online_record_page_name_form')->name('admin/stay_online_record_page_name_form');
        Route::post('admin/stay_online_record_page_name_form', 'UserController@stay_online_record_page_name_save')->name('admin/stay_online_record_page_name_save');
        Route::get('admin/stay_online_record_page_name_delete/{id}', 'UserController@stay_online_record_page_name_delete')->name('admin/stay_online_record_page_name_delete');
        Route::get('admin/stay_online_record_page_name_switch', 'UserController@stay_online_record_page_name_switch')->name('admin/stay_online_record_page_name_switch');
        
        Route::get('global/feature_flags', 'UserController@feature_flags')->name('admin/feature_flags');
        Route::get('global/feature_flags/create', 'UserController@feature_flags_create');
        Route::post('global/feature_flags/create', 'UserController@feature_flags_create');
        Route::get('global/feature_flags/edit/{feature_key}', 'UserController@feature_flags_edit');
        Route::post('global/feature_flags/edit', 'UserController@feature_flags_edit');
        Route::post('global/feature_flags/update', 'UserController@feature_flags_update');
        Route::post('global/feature_flags/delete', 'UserController@feature_flags_delete');

        Route::get('special_industries_judgment_training_setup', 'AdminController@special_industries_judgment_training_setup')->name('admin/special_industries_judgment_training_setup');
        Route::post('special_industries_judgment_training_setup_set', 'AdminController@special_industries_judgment_training_setup_set')->name('admin/special_industries_judgment_training_setup_set');
        Route::get('special_industries_judgment_training_select', 'AdminController@special_industries_judgment_training_select')->name('admin/special_industries_judgment_training_select');
        Route::get('special_industries_judgment_training_test', 'AdminController@special_industries_judgment_training_test')->name('admin/special_industries_judgment_training_test');
        Route::post('special_industries_judgment_answer_send', 'AdminController@special_industries_judgment_answer_send')->name('admin/special_industries_judgment_answer_send');
        Route::get('special_industries_judgment_result', 'AdminController@special_industries_judgment_result')->name('admin/special_industries_judgment_result');
        Route::get('special_industries_judgment_training_hide', 'AdminController@special_industries_judgment_training_hide')->name('admin/special_industries_judgment_training_hide');

        Route::get('users/wait_for_more_data_list', 'UserController@wait_for_more_data_list')->name('users/wait_for_more_data_list');
        Route::get('users/wait_for_more_data_login_time_list', 'UserController@wait_for_more_data_login_time_list')->name('users/wait_for_more_data_login_time_list');
    });
    Route::group(['prefix' => 'admin', 'middleware' => 'Admin'], function () {
        //寄退信Log查詢
        Route::get('maillog', 'Api\MailController@viewMailLog')->name('maillog');
        Route::get("fakeMail", 'Api\MailController@fakeMail')->name('fakeMail');
        Route::post("sendFakeMail", 'Api\MailController@sendFakeMail')->name('sendFakeMail');

        //視訊驗證功能
        Route::get('users/video_chat_verify', 'VideoChatController@video_chat_verify')->name('users/video_chat_verify');
        //視訊驗證上傳影片功能初始化
        Route::post('users/video_chat_verify_upload_init', 'VideoChatController@video_chat_verify_upload_init')->name('users/video_chat_verify_upload_init');
        //視訊驗證上傳影片功能
        Route::post('users/video_chat_verify_upload', 'VideoChatController@video_chat_verify_upload')->name('users/video_chat_verify_upload');
        //視訊驗證影片紀錄
        Route::get('users/video_chat_verify_record_list', 'VideoChatController@video_chat_verify_record_list')->name('users/video_chat_verify_record_list');
        Route::get('users/video_chat_verify_record', 'VideoChatController@video_chat_verify_record')->name('users/video_chat_verify_record');

        Route::get('users/video_chat_get_users', 'VideoChatController@video_chat_get_users')->name('users/video_chat_get_users');
        
        Route::post('users/video_chat_verify_record_save', 'VideoChatController@video_chat_verify_record_save')->name('users/video_chat_verify_record_save');

        //視訊錄影影片紀錄
        Route::get('users/video_verify_record_list', 'VideoChatController@video_verify_record_list')->name('users/video_verify_record_list');
        Route::get('users/video_verify_record', 'VideoChatController@video_verify_record')->name('users/video_verify_record');
        Route::post('users/video_verify_record_pass', 'VideoChatController@video_verify_record_pass')->name('users/video_verify_record_pass');
        Route::post('users/video_verify_record_fail', 'VideoChatController@video_verify_record_fail')->name('users/video_verify_record_fail');
        
        Route::post('users/video_chat_memo_save', 'VideoChatController@video_chat_memo_save')->name('users/video_chat_memo_save');
        Route::post('users/user_question_into_chat_time_save', 'VideoChatController@user_question_into_chat_time_save')->name('users/user_question_into_chat_time_save');
        Route::get("stat_test", 'Api\MailController@test_stat');

        Route::get("jsfp_pro_validation", function() {
            ini_set("max_execution_time",'0');
            // 計算 CFP 和 Visitor 的對應關係：一對一及一對多
            // 計算 CFP ID 總數
            $cfp_has_one = 0;
            $cfp_has_many = 0;
            $cfp_user_has_one = 0;
            $cfp_user_has_many = 0;
            $base = \App\Models\LogUserLogin::with('cfp', 'cfp.login_logs')
                        ->where([["id", ">", 6305459], ["cfp_id", "!=", NULL], ["visitor_id", "!=", NULL]]);
            $the_cfps = clone $base->groupBy("cfp_id")->get()->pluck("cfp");
            $the_cfp_users = clone $base->groupBy("cfp_id", "user_id")->get()->pluck("cfp");
            foreach(["cfp" => $the_cfps, "cfp_user" => $the_cfp_users] as $type => $data_sets) { 
                foreach($data_sets as $data_set) {
                    $first_visitor = null;
                    $caught_many = false;
                    foreach($data_set[0]->login_logs as $logs) {
                        if(!$first_visitor) { $first_visitor = $logs->visitor_id; }
                        if($first_visitor != $logs->visitor_id) {
                            $caught_many = true;
                            break;
                        }
                    }
                    if($caught_many) {
                        ${$type . "_has_many"}++;
                    }
                    else {
                        ${$type . "_has_one"}++;
                    }
                }
            }

            return [
                "cfpid 總數: " . $the_cfps->count(),
                "cfpid <-> custom id 一對一的數量: " . $cfp_has_one,
                "cfpid <-> custom id 一對多的數量: " . $cfp_has_many,
                "cfpid_userid <-> custom id 一對一的數量: " . $cfp_user_has_one,
                "cfpid_userid <-> custom id 一對多的數量: " . $cfp_user_has_many,
            ];
        });
        Route::get("simpleStat", function() {
	    ini_set("max_execution_time",'0');
            ini_set('memory_limit','-1');
            ini_set("request_terminate_timeout",'0');
            set_time_limit(0);
            $data1 = collect(\DB::select(\DB::raw('SELECT
					    u.id,
                                            u.email,
                                            u.created_at,
                                            (
                                                select
                                                    count(id)
                                                from
                                                    log_user_login
                                                WHERE
                                                    user_id = u.id
                                            ) as "login_times"
                                        FROM
                                            users u
                                        WHERE
                                            engroup = 2
                                            and created_at > "2023-02-02"
                                            AND id not in (
                                                select
                                                    member_id
                                                from
                                                    banned_users
                                                where
                                                    expire_date is null
                                                    or expire_date > now()
                                            )
                                            AND id not in (
                                                select
                                                    target
                                                from
                                                    banned_users_implicitly
                                            )
                                            ORDER BY u.id ASC')));
            echo "<table border='1'>";
            echo "<tr><td>email</td><td>created_at</td><td>login_times</td><td>通訊人數</td></tr>";
            foreach($data1 as $data) {
                $messages_all = \App\Models\Message::select(
                    'id',
                    'room_id',
                    'to_id',
                    'from_id',
                    'read',
                    'created_at'
                )
                    ->where(function ($query) use ($data) {
                        $query->where('to_id', $data->id)->orwhere('from_id', $data->id);
                    })
                    ->where('from_id', '!=', 1049)
                    ->where('to_id', '!=', 1049)
                    ->orderBy('id')
                    ->withTrashed()
                    ->get();
                /*總房間數*/
                $first_messages_all = $messages_all->unique('room_id');
                $first_send_room = $first_messages_all
                    ->where('from_id', $data->id)
                    ->pluck('room_id');
                /*第一則訊息為收訊的房間*/
                $first_reply_room = $first_messages_all
                    ->where('to_id', $data->id)
                    ->pluck('room_id');
                $send_message_all = \App\Models\Message::withTrashed()
                    ->select('id', 'room_id', 'to_id', 'from_id', 'read', 'created_at')
                    ->whereIn('room_id', $first_send_room)
                    ->where('from_id', $data->id)
                    ->orderByDesc('id')
                    ->get();
                $reply_message_all = \App\Models\Message::withTrashed()
                    ->select('id', 'room_id', 'to_id', 'from_id', 'read', 'created_at')
                    ->whereIn('room_id', $first_reply_room)
                    ->where('from_id', $data->id)
                    ->orderByDesc('id')
                    ->get();

                $data->mesasge_people_count = count($send_message_all->unique('room_id')) + count($reply_message_all->unique('room_id'));
                echo "<tr><td>{$data->email}</td><td>{$data->created_at}</td><td>{$data->login_times}</td><td>{$data->mesasge_people_count}</td></tr>";
            }
            echo "</table>";
        });
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

Route::get('/cfp', [CfpController::class, 'cfp']);
