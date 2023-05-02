<?php

uses(Illuminate\Foundation\Testing\WithoutMiddleware::class);

test('render_browse_index', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/browse');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_browse_announcement', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/announcement');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_browse_banned_warned_list', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/banned_warned_list');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_browse_banned_warned_list_type_1', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/banned_warned_list?type=1');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_newer_manual', function ()
{
    try{
        $user = \App\Models\User::find(15601);//TESTmaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/newer_manual');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_female_newer_manual', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/female_newer_manual');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_anti_fraud_manual', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/anti_fraud_manual');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_fav', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/fav');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_visited', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/visited');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_block', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/block');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_evaluation_self', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/evaluation_self');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

test('render_search_discard_list', function ()
{
    try{
        $user = \App\Models\User::find(15600);//TESTfemaleVIP@test.com

        $response = $this->actingAs($user)->get('/dashboard/search_discard/list');

        $response->assertStatus(200);
    }catch(Throwable $e){
        
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
});

