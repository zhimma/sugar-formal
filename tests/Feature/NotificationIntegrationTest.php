<?php

uses(
    Illuminate\Foundation\Testing\WithoutMiddleware::class
);

beforeEach(function () {

    return;  //notifications疑似已棄用，故先停用
    $this->notification = App\Models\Notification::factory()->make([
        'id' => 1,
        'user_id' => 1,
        'flag' => 'info',
        'uuid' => 'lksjdflaskhdf',
        'title' => 'Testing',
        'details' => 'Your car has been impounded!',
        'is_read' => 0,
    ]);
    $this->notificationEdited = App\Models\Notification::factory()->make([
        'id' => 1,
        'user_id' => 1,
        'flag' => 'info',
        'uuid' => 'lksjdflaskhdf',
        'title' => 'Testing',
        'details' => 'Your car has been impounded!',
        'is_read' => 1,
    ]);

    $role = App\Models\Role::factory()->create();
    $user = App\Models\User::factory()->create();
    $user->roles()->attach($role);

    $this->actor = $this->actingAs($user);
});

    test('Index' ,function ()
    {
        
        $response = $this->actor->call('GET', 'admin/notifications');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('notifications');
    })->skip();

    test('Create' ,function ()
    {
        
        $response = $this->actor->call('GET', 'admin/notifications/create');
        $this->assertEquals(200, $response->getStatusCode());
    })->skip();

    test('Store()' ,function ()
    {
        
        $response = $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('admin/notifications/'.$this->notification->id.'/edit');
    })->skip();

    test('Edit' ,function ()
    {
        
        $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());

        $response = $this->actor->call('GET', 'admin/notifications/'.$this->notification->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('notification');
    })->skip();

    test('Update' ,function ()
    {
        
        $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());
        $response = $this->actor->call('PATCH', 'admin/notifications/1', $this->notificationEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('notifications', $this->notificationEdited->toArray());
        $response->assertRedirect('/');
    })->skip();

    test('Delete' ,function ()
    {
        
        $this->actor->call('POST', 'admin/notifications', $this->notification->toArray());

        $response = $this->call('DELETE', 'admin/notifications/'.$this->notification->id);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('admin/notifications');
    })->skip();

