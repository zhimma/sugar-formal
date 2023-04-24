<?php
    test('render_essence_list', function ()
    {
        try{
            $this->withoutMiddleware(\App\Http\Middleware\FaqCheck::class);
            $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

            $response = $this->actingAs($user)->get('/dashboard/essence_list');

            $response->assertStatus(200);
        }catch(Throwable $e){
            
            $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
            $this->handleCatchedException($e,$notification_string);
        }
    });

