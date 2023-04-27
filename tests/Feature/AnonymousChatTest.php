<?php

uses(Illuminate\Foundation\Testing\WithoutMiddleware::class);

test('render_anonymous_chat', function () {

    try{
        $user = \App\Models\User::find('1049');//站長不會有被封鎖的問題

        $response = $this->actingAs($user)->get('/dashboard/anonymousChat');
        $response->assertStatus(200);
    }catch(Throwable $e){
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }    

});


