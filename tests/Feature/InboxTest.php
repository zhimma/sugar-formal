<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class InboxTest extends TestCase
{
    public function test_inbox_get_data_with_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $postJson = array(
            'date'=> 7,
            'uid'=> $user->id,
            'isVip'=> 1,
        );
        $response = $this->actingAs($user)->postJson('/dashboard/chat2/showMessages', $postJson);

        $result = '{"status":1,"msg":["No data"],"noVipCount":10}';
        $this->assertEquals($result, $response->getContent());
    }

    public function test_inbox_get_no_data_with_input()
    {
        /** @var \Illuminate\Contracts\Auth\Authenticatable $user */
        $user = \App\Models\User::factory()->create();

        $userMeta = \App\Models\UserMeta::factory()->create();

        $hasUser = $user ? true : false;

        $postJson = array(
            'date'=> 7,
            'uid'=> 15601,//TESTmaleVIP@test.com as user
            'isVip'=> 1,
        );
        $response = $this->actingAs($user)->postJson('/dashboard/chat2/showMessages', $postJson);
        $result = '{"status":1,"msg":[{"to_id":127337,"from_id":15601,"temp_id":0,"all_delete_count":0,"is_row_delete_1":0,"is_row_delete_2":0,"is_single_delete_1":0,"is_single_delete_2":0,"content":"555","read":"N","created_at":"2022-07-05 22:01:27","isAdminMessage":0,"cntr":0,"user_id":127337,"user_name":"jilllulu","isAvatarHidden":0,"blurry_avatar":"VIP,general,","blurry_life_photo":"VIP,general,","pic":"\/new\/images\/female.png","read_n":0,"isVip":false,"isWarned":0,"isBanned":0,"exchange_period":1,"mCount":17},{"to_id":68955,"from_id":15601,"temp_id":0,"all_delete_count":0,"is_row_delete_1":0,"is_row_delete_2":0,"is_single_delete_1":0,"is_single_delete_2":0,"content":"6","read":"N","created_at":"2022-07-05 21:48:06","isAdminMessage":0,"cntr":0,"user_id":68955,"user_name":"\u6e2c\u8a66\u5e33\u865f1010","isAvatarHidden":0,"blurry_avatar":"VIP,","blurry_life_photo":"VIP,","pic":"\/new\/images\/female.png","read_n":0,"isVip":true,"isWarned":0,"isBanned":0,"exchange_period":1,"mCount":17}],"noVipCount":10}';
        $this->assertEquals($result, $response->getContent());
    }
}
