<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Chat2Test extends TestCase
{
    public function test_render_chat2()
    {
        try{
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/chat2/chatShow/15600');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
