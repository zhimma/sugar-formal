<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\LineNotifyService as LineNotify;
class InboxTest extends TestCase
{
    public function test_inbox_get_data_with_input()
    {
        try{
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

            $result = env("INBOX_NO_VALUE_RESULT");
            $this->assertEquals($result, $response->getContent());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_inbox_get_no_data_with_input()
    {
        try{
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
            $result = env("INBOX_HAS_VALUE_RESULT");
            $this->assertEquals($result, $response->getContent());
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
