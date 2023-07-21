<?php


    test('login_screen_can_be_rendered' ,function ()
    {
        try{
            $response = $this->get('/login');
            $response->assertStatus(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('users_can_authenticate_using_the_login_screen', function ()
    { 
        // 用 XAMPP 測的時候會出錯，但在測試站上沒問題
        try{
            $user = \App\Models\User::factory()->create(['password'=>bcrypt('123123')]);
            \App\Models\UserMeta::factory()->create(['user_id'=>$user->id]);
            $response = $this->postJson('/login', ['email' => $user->email, 'password'=>'123123']);
            $this->assertAuthenticated();   
        }catch(Throwable $e){
            logger($response->getContent());
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('users_can_not_authenticate_with_invalid_password' ,function ()
    {
        try{
            $response = $this->postJson('/login', ['email' => 'TESTmal@test.com', 'password'=>'123123']);
            $this->assertGuest();
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

