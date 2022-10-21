<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VipUpgradeTest extends TestCase
{
    public function test_render_vip_select()
    {
        try{
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/vipSelect');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
    public function test_render_new_vip()
    {
        try{
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/new_vip');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_vip_added_hide_online()
    {
        try{
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/valueAddedHideOnline');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
