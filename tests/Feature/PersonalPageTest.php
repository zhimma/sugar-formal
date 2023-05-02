<?php

    test('render_personalPage' ,function ()
    {
        try{
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/personalPage');

            $response->assertStatus(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

