<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MessageBoardTest extends TestCase
{
    public function test_render_show_list()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/showList');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
