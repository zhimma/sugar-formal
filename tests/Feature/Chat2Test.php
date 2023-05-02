<?php
    test('render_chat2', function ()
    {
        try{
            $this->withoutMiddleware(\App\Http\Middleware\FaqCheck::class);
            $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/chat2/chatShow/15600');

            $response->assertStatus(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });
