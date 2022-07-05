<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;


class SearchTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */

    // use RefreshDatabase;

    public function test_search_return_correct_data_with_correct_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $user = \App\Models\User::orderBy('id', 'desc')->first();
        $user_meta = $user->id;
        $postJson = array(
            "agefrom" => "",
            "ageto" => "",
            "area" => "",
            "area2" => "",
            "area3" => "",
            "body" => "",
            "budget" => "",
            "city" => "",
            "city2" => "",
            "city3" => "",
            "cup" => "",
            "drinking" => "",
            "education" => "",
            "exchange_period" => "",
            "heightfrom" => "",
            "heightto" => "",
            "income" => "",
            "isAdvanceAuth" => "0",
            "isBlocked" => "1",
            "isPhoneAuth" => "",
            "isVip" => "",
            "isWarned" => "",
            "marriage" => "",
            "page" => "1",
            "perPageCount" => "12",
            "pic" => "",
            "prRange" => "",
            "prRange_none" => "",
            "seqtime" => "1",
            "situation" => "",
            "smoking" => "",
            "tattoo" => "",
            "userIsAdvanceAuth" => "0",
            "userIsVip" => "1",
            "weight" => "",
            "umeta" => $user_meta,
            "user" => $user
        );
        $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

        $result = '{"singlePageCount":0,"allPageDataCount":0,"dataList":[],"user":{"id":689,"name":"henry2","title":"\u627e\u5b89\u6170","engroup":2,"engroup_change":0,"is_hide_online":0,"enstatus":0,"email":"henry2@gmail.com","password_updated":0,"created_at":"2018-01-05T02:23:28.000000Z","updated_at":"2020-07-26T14:50:57.000000Z","last_login":"2020-07-26 22:50:57","vip_record":"2020-05-13 00:00:00","hide_online_time":null,"accountStatus":null,"account_status_admin":null,"noticeRead":0,"isReadIntro":null,"notice_has_new_evaluation":1,"advance_auth_status":0,"advance_auth_time":"0000-00-00 00:00:00","advance_auth_identity_no":"","advance_auth_birth":"0000-00-00","advance_auth_phone":"","login_times":0,"intro_login_times":0,"line_notify_alert":0,"registered_from_mobile":0,"user_meta":{"id":1,"user_id":689,"phone":null,"is_active":1,"activation_token":null,"marketing":0,"terms_and_cond":0,"created_at":"2018-01-05T02:24:00.000000Z","updated_at":"2018-01-05T02:24:00.000000Z","city":"\u81fa\u5317\u5e02","blockcity":null,"area":"\u4e2d\u6b63\u5340","blockarea":null,"budget":"\u53ef\u5546\u8b70","birthdate":"1979-09-11","height":180,"weight":null,"cup":null,"body":"\u7626","about":null,"style":"2-\u9577\u671f\u7a69\u5b9a\u70ba\u4e3b\uff0c\u5e0c\u671b\u96d9\u65b9\u5e73\u5e38\u9664\u4e86\u7d04\u6703\u6642\u9593\u5916\uff0c\u9084\u6703\u4e92\u76f8\u95dc\u5fc3\u804a\u5929\u3002","situation":null,"occupation":null,"education":null,"marriage":"\u5df2\u5a5a","drinking":"\u5e38\u559d","isWarned":null,"isWarnedType":null,"smoking":"\u4e0d\u62bd","isHideArea":"1","isHideCup":"0","isHideWeight":"0","isHideOccupation":"0","country":null,"memo":null,"pic":null,"pic_original_name":null,"isAvatarHidden":0,"domainType":null,"blockdomainType":null,"domain":null,"blockdomain":null,"job":null,"realName":null,"assets":null,"income":"300\u842c\u4ee5\u4e0a","notifmessage":"\u4e0d\u901a\u77e5","notifhistory":"\u986f\u793a\u666e\u901a\u6703\u54e1\u4fe1\u4ef6","age":"","date_mode":"","method":"","uri":"","ip":""},"vip":[]},"userIsVip":false}';
        $this->assertEquals($result, $response->getContent());
    }

    public function test_search_return_correct_data_with_wrong_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $user = \App\Models\User::orderBy('id', 'desc')->first();
        $user_meta = $user->id;
        $postJson = array(
            "agefrom" => "",
            "ageto" => "",
            "area" => "",
            "area2" => "",
            "area3" => "",
            "body" => "",
            "budget" => "",
            "city" => "",
            "city2" => "",
            "city3" => "",
            "cup" => "",
            "drinking" => "",
            "education" => "",
            "exchange_period" => "",
            "heightfrom" => "",
            "heightto" => "",
            "income" => "",
            "isAdvanceAuth" => "0",
            "isBlocked" => "1",
            "isPhoneAuth" => "",
            "isVip" => "",
            "isWarned" => "",
            "marriage" => "",
            "page" => "1",
            "perPageCount" => "12",
            "pic" => "",
            "prRange" => "wrong pr percent",
            "prRange_none" => "",
            "seqtime" => "1",
            "situation" => "",
            "smoking" => "",
            "tattoo" => "",
            "userIsAdvanceAuth" => "0",
            "userIsVip" => "1",
            "weight" => "",
            "umeta" => $user_meta,
            "user" => $user
        );
        $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

        $result = '{"singlePageCount":0,"allPageDataCount":0,"dataList":[],"user":{"id":689,"name":"henry2","title":"\u627e\u5b89\u6170","engroup":2,"engroup_change":0,"is_hide_online":0,"enstatus":0,"email":"henry2@gmail.com","password_updated":0,"created_at":"2018-01-05T02:23:28.000000Z","updated_at":"2020-07-26T14:50:57.000000Z","last_login":"2020-07-26 22:50:57","vip_record":"2020-05-13 00:00:00","hide_online_time":null,"accountStatus":null,"account_status_admin":null,"noticeRead":0,"isReadIntro":null,"notice_has_new_evaluation":1,"advance_auth_status":0,"advance_auth_time":"0000-00-00 00:00:00","advance_auth_identity_no":"","advance_auth_birth":"0000-00-00","advance_auth_phone":"","login_times":0,"intro_login_times":0,"line_notify_alert":0,"registered_from_mobile":0,"user_meta":{"id":1,"user_id":689,"phone":null,"is_active":1,"activation_token":null,"marketing":0,"terms_and_cond":0,"created_at":"2018-01-05T02:24:00.000000Z","updated_at":"2018-01-05T02:24:00.000000Z","city":"\u81fa\u5317\u5e02","blockcity":null,"area":"\u4e2d\u6b63\u5340","blockarea":null,"budget":"\u53ef\u5546\u8b70","birthdate":"1979-09-11","height":180,"weight":null,"cup":null,"body":"\u7626","about":null,"style":"2-\u9577\u671f\u7a69\u5b9a\u70ba\u4e3b\uff0c\u5e0c\u671b\u96d9\u65b9\u5e73\u5e38\u9664\u4e86\u7d04\u6703\u6642\u9593\u5916\uff0c\u9084\u6703\u4e92\u76f8\u95dc\u5fc3\u804a\u5929\u3002","situation":null,"occupation":null,"education":null,"marriage":"\u5df2\u5a5a","drinking":"\u5e38\u559d","isWarned":null,"isWarnedType":null,"smoking":"\u4e0d\u62bd","isHideArea":"1","isHideCup":"0","isHideWeight":"0","isHideOccupation":"0","country":null,"memo":null,"pic":null,"pic_original_name":null,"isAvatarHidden":0,"domainType":null,"blockdomainType":null,"domain":null,"blockdomain":null,"job":null,"realName":null,"assets":null,"income":"300\u842c\u4ee5\u4e0a","notifmessage":"\u4e0d\u901a\u77e5","notifhistory":"\u986f\u793a\u666e\u901a\u6703\u54e1\u4fe1\u4ef6","age":"","date_mode":"","method":"","uri":"","ip":""},"vip":[]},"userIsVip":false}';
        $this->assertEquals($result, $response->getContent());
    }

    public function test_search_return_wrong_data_with_correct_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $user = \App\Models\User::orderBy('id', 'desc')->first();
        $user_meta = $user->id;
        $postJson = array(
            "agefrom" => "",
            "ageto" => "",
            "area" => "",
            "area2" => "",
            "area3" => "",
            "body" => "",
            "budget" => "",
            "city" => "",
            "city2" => "",
            "city3" => "",
            "cup" => "",
            "drinking" => "",
            "education" => "",
            "exchange_period" => "",
            "heightfrom" => "",
            "heightto" => "",
            "income" => "",
            "isAdvanceAuth" => "0",
            "isBlocked" => "1",
            "isPhoneAuth" => "",
            "isVip" => "",
            "isWarned" => "",
            "marriage" => "",
            "page" => "1",
            "perPageCount" => "12",
            "pic" => "",
            "prRange" => "",
            "prRange_none" => "",
            "seqtime" => "1",
            "situation" => "",
            "smoking" => "",
            "tattoo" => "",
            "userIsAdvanceAuth" => "0",
            "userIsVip" => "1",
            "weight" => "",
            "umeta" => $user_meta,
            "user" => $user
        );
        $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

        $result = '{"singlePageCount":0,"allPageDataCount":0,"dataList":[],"user":{"id":689,"name":"henry2","title":"\u627e\u5b89\u6170","engroup":2,"engroup_change":0,"is_hide_online":0,"enstatus":0,"email":"henry2@gmail.com","password_updated":0,"created_at":"2018-01-05T02:23:28.000000Z","updated_at":"2020-07-26T14:50:57.000000Z","last_login":"2020-07-26 22:50:57","vip_record":"2020-05-13 00:00:00","hide_online_time":null,"accountStatus":null,"account_status_admin":null,"noticeRead":0,"isReadIntro":null,"notice_has_new_evaluation":1,"advance_auth_status":0,"advance_auth_time":"0000-00-00 00:00:00","advance_auth_identity_no":"","advance_auth_birth":"0000-00-00","advance_auth_phone":"","login_times":0,"intro_login_times":0,"line_notify_alert":0,"registered_from_mobile":0,"user_meta":{"id":1,"user_id":689,"phone":null,"is_active":1,"activation_token":null,"marketing":0,"terms_and_cond":0,"created_at":"2018-01-05T02:24:00.000000Z","updated_at":"2018-01-05T02:24:00.000000Z","city":"\u81fa\u5317\u5e02","blockcity":null,"area":"\u4e2d\u6b63\u5340","blockarea":null,"budget":"\u53ef\u5546\u8b70","birthdate":"1979-09-11","height":180,"weight":null,"cup":null,"body":"\u7626","about":null,"style":"2-\u9577\u671f\u7a69\u5b9a\u70ba\u4e3b\uff0c\u5e0c\u671b\u96d9\u65b9\u5e73\u5e38\u9664\u4e86\u7d04\u6703\u6642\u9593\u5916\uff0c\u9084\u6703\u4e92\u76f8\u95dc\u5fc3\u804a\u5929\u3002","situation":null,"occupation":null,"education":null,"marriage":"\u5df2\u5a5a","drinking":"\u5e38\u559d","isWarned":null,"isWarnedType":null,"smoking":"\u4e0d\u62bd","isHideArea":"1","isHideCup":"0","isHideWeight":"0","isHideOccupation":"0","country":null,"memo":null,"pic":null,"pic_original_name":null,"isAvatarHidden":0,"domainType":null,"blockdomainType":null,"domain":null,"blockdomain":null,"job":null,"realName":null,"assets":null,"income":"300\u842c\u4ee5\u4e0a","notifmessage":"\u4e0d\u901a\u77e5","notifhistory":"\u986f\u793a\u666e\u901a\u6703\u54e1\u4fe1\u4ef6","age":"","date_mode":"","method":"","uri":"","ip":""},"vip":[]},"userIsVip":false}';
        $this->assertNotEquals($result, $response->getContent());
    }

    public function test_search_return_wrong_data_with_wrong_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $user = \App\Models\User::orderBy('id', 'desc')->first();
        $user_meta = $user->id;
        $postJson = array(
            "agefrom" => "",
            "ageto" => "",
            "area" => "",
            "area2" => "",
            "area3" => "",
            "body" => "",
            "budget" => "",
            "city" => "",
            "city2" => "",
            "city3" => "",
            "cup" => "",
            "drinking" => "",
            "education" => "",
            "exchange_period" => "",
            "heightfrom" => "",
            "heightto" => "",
            "income" => "",
            "isAdvanceAuth" => "0",
            "isBlocked" => "1",
            "isPhoneAuth" => "",
            "isVip" => "",
            "isWarned" => "",
            "marriage" => "",
            "page" => "1",
            "perPageCount" => "12",
            "pic" => "",
            "prRange" => "wrong pr percent",
            "prRange_none" => "",
            "seqtime" => "1",
            "situation" => "",
            "smoking" => "",
            "tattoo" => "",
            "userIsAdvanceAuth" => "0",
            "userIsVip" => "1",
            "weight" => "",
            "umeta" => $user_meta,
            "user" => $user
        );
        $response = $this->actingAs($user)->postJson('/getSearchData', $postJson);

        $result = '{"singlePageCount":0,"allPageDataCount":0,"dataList":[],"user":{"id":689,"name":"henry2","title":"\u627e\u5b89\u6170","engroup":2,"engroup_change":0,"is_hide_online":0,"enstatus":0,"email":"henry2@gmail.com","password_updated":0,"created_at":"2018-01-05T02:23:28.000000Z","updated_at":"2020-07-26T14:50:57.000000Z","last_login":"2020-07-26 22:50:57","vip_record":"2020-05-13 00:00:00","hide_online_time":null,"accountStatus":null,"account_status_admin":null,"noticeRead":0,"isReadIntro":null,"notice_has_new_evaluation":1,"advance_auth_status":0,"advance_auth_time":"0000-00-00 00:00:00","advance_auth_identity_no":"","advance_auth_birth":"0000-00-00","advance_auth_phone":"","login_times":0,"intro_login_times":0,"line_notify_alert":0,"registered_from_mobile":0,"user_meta":{"id":1,"user_id":689,"phone":null,"is_active":1,"activation_token":null,"marketing":0,"terms_and_cond":0,"created_at":"2018-01-05T02:24:00.000000Z","updated_at":"2018-01-05T02:24:00.000000Z","city":"\u81fa\u5317\u5e02","blockcity":null,"area":"\u4e2d\u6b63\u5340","blockarea":null,"budget":"\u53ef\u5546\u8b70","birthdate":"1979-09-11","height":180,"weight":null,"cup":null,"body":"\u7626","about":null,"style":"2-\u9577\u671f\u7a69\u5b9a\u70ba\u4e3b\uff0c\u5e0c\u671b\u96d9\u65b9\u5e73\u5e38\u9664\u4e86\u7d04\u6703\u6642\u9593\u5916\uff0c\u9084\u6703\u4e92\u76f8\u95dc\u5fc3\u804a\u5929\u3002","situation":null,"occupation":null,"education":null,"marriage":"\u5df2\u5a5a","drinking":"\u5e38\u559d","isWarned":null,"isWarnedType":null,"smoking":"\u4e0d\u62bd","isHideArea":"1","isHideCup":"0","isHideWeight":"0","isHideOccupation":"0","country":null,"memo":null,"pic":null,"pic_original_name":null,"isAvatarHidden":0,"domainType":null,"blockdomainType":null,"domain":null,"blockdomain":null,"job":null,"realName":null,"assets":null,"income":"300\u842c\u4ee5\u4e0a","notifmessage":"\u4e0d\u901a\u77e5","notifhistory":"\u986f\u793a\u666e\u901a\u6703\u54e1\u4fe1\u4ef6","age":"","date_mode":"","method":"","uri":"","ip":""},"vip":[]},"userIsVip":false}';
        $this->assertNotEquals($result, $response->getContent());
    }
}
