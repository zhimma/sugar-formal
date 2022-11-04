<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EssenceListTest extends TestCase
{
    public function test_render_essence_list()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/essence_list');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
