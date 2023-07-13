<?php

use App\Services\UserService;

beforeEach(function () {
    $this->service = $this->app->make(UserService::class);  
});

test('GetUsers', function () {
    try{
        $response = $this->service->all();
        expect($response)->toBeInstanceOf('Illuminate\Database\Eloquent\Collection');
    }catch(Throwable $e){
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }
})->skip();

test('GetUser', function () {
    try{
        $user = App\Models\User::factory()->create();
        App\Models\UserMeta::factory()->create(['user_id' => $user->id]);
        $response = $this->service->find($user->id);
        
        expect($response)->toBeObject()->name->toBe($user->name);
    }catch(Throwable $e){
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }    
});

test('CreateUser', function() {
    try{
        $role = App\Models\Role::factory()->create();
        $user = App\Models\User::factory()->create();
        $email_config = \DB::table("queue_global_variables")->where("name", 'send-email')->first();
        if($email_config) {
            \DB::table("queue_global_variables")->where("name", 'send-email')->update(['value'=>0]);
        }
        else {
            \DB::table("queue_global_variables")->create(['name'=>'send-email','value'=>0]);
        }
        $response = $this->service->create($user, 'password');
        expect($response)->toBeObject()->name->toBe($user->name);
    } catch (Throwable $e){
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }          
        
    
});

test('UpdateUser', function () {
    try{
        $user = App\Models\User::factory()->create();
        App\Models\UserMeta::factory()->create(['user_id' => $user->id]);

        $response = $this->service->update($user->id, [
            'email' => $user->email,
            'name' => 'jim',
            'role' => 'member',
            'meta' => [
                'phone' => '666',
                'marketing' => 1,
                'terms_and_cond' => 1,
            ],
        ]);
        
        $this->assertDatabaseHas('user_meta', ['phone' => '666']);
        $this->assertDatabaseHas('users', ['name' => 'jim']);
    }catch(Throwable $e){
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }    
});
