<?php

    test('render_vip_select',function ()
    {
        try{
            $this->withoutMiddleware(\App\Http\Middleware\FaqCheck::class);
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/vipSelect');

            $response->assertStatus(200);
        }catch(Throwable $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });
    
    test('render_new_vip',function ()
    {
        try{
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/new_vip');

            $response->assertStatus(200);
        }catch(Throwable $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

    test('render_vip_added_hide_online',function ()
    {
        try{
            $this->withoutMiddleware(\App\Http\Middleware\FaqCheck::class);
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/valueAddedHideOnline');

            $response->assertStatus(200);
        }catch(Throwable $e){
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });
