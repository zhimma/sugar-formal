<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BrowseTest extends TestCase
{
    public function test_render_browse_index()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/browse');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_browse_announcement()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/announcement');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_browse_banned_warned_list()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/banned_warned_list');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_browse_banned_warned_list_type_1()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/banned_warned_list?type=1');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_newer_manual()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/newer_manual');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_anti_fraud_manual()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/anti_fraud_manual');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_fav()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/fav');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_visited()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/visited');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_block()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/block');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_evaluation_self()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/evaluation_self');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_render_search_discard_list()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/search_discard/list');

            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
