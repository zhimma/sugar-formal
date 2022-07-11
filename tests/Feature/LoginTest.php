<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Services\LineNotifyService as LineNotify;
class LoginTest extends TestCase
{
    public function setUp():void
    {
        parent::setUp();
    }

    public function test_basic_test()
    {
        $this->assertTrue(true);
    }

    public function test_basic_dump_test()
    {
        $response = $this->get('/');
 
        $response->dumpHeaders();
 
        $response->dumpSession();
 
        $response->dump();
    }
    

    public function test_example(){
        $response = $this->get('/');
        // dd($response);
        $response->assertStatus(200);    
    }

    public function test_login_screen_can_be_rendered()
    {
        try{
            $response = $this->get('/login');
            $response->assertStatus(200);
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_users_can_authenticate_using_the_login_screen()
    { 
        try{
            $user = \App\Models\User::factory()->create();
            $response = $this->postJson('/login', ['email' => $user->email, 'password'=>$user->password]);
            $this->assertAuthenticated();   
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }

    public function test_users_can_not_authenticate_with_invalid_password()
    {
        try{
            $response = $this->postJson('/login', ['email' => 'TESTmal@test.com', 'password'=>'123123']);
            $this->assertGuest();
        }catch(\Exception $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__);
            
            $lineNotify = new LineNotify;
            $lineNotify->sendLineNotifyMessage($notification_string);
        }
    }
}
