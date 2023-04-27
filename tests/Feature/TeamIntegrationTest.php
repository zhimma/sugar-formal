<?php
uses(
    Illuminate\Foundation\Testing\WithoutMiddleware::class
);

    beforeEach(function () 
    {
        return;  //找不到Team相關的功能，先停用

        $this->user = App\Models\User::factory()->create([
            'id' => rand(1000, 9999)
        ]);
        $this->role = App\Models\Role::factory()->create([
            'name' => 'admin'
        ]);

        $this->team = App\Models\Team::factory()->make([
            'id' => 1,
            'user_id' => $this->user->id,
            'name' => 'Awesomeness'
        ]);
        $this->teamEdited = App\Models\Team::factory()->make([
            'id' => 1,
            'user_id' => $this->user->id,
            'name' => 'Hackers'
        ]);

        $this->user->roles()->attach($this->role);
        $this->actor = $this->actingAs($this->user);
        Config::set('minify.config.ignore_environments', ['local', 'testing']);
    });

    test('Index' ,function ()
    {
        
        $response = $this->actor->call('GET', '/teams');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('teams');
    })->skip();

    test('Create' ,function ()
    {
        
        $response = $this->actor->call('GET', '/teams/create');
        $this->assertEquals(200, $response->getStatusCode());
    })->skip();

    test('Store' ,function ()
    {
        
        $admin = App\Models\User::factory()->create([ 'id' => rand(1000, 9999) ]);
        $response = $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('teams/'.$this->team->id.'/edit');
    })->skip();

    test('Edit' ,function ()
    {
        
        $admin = App\Models\User::factory()->create([ 'id' => rand(1000, 9999) ]);
        $admin->roles()->attach($this->role);
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('GET', '/teams/'.$this->team->id.'/edit');
        $this->assertEquals(200, $response->getStatusCode());
        $response->assertViewHas('team');
    })->skip();

    test('Update' ,function ()
    {
        
        $admin = App\Models\User::factory()->create([ 'id' => rand(1000, 9999) ]);
        $admin->roles()->attach($this->role);
        $this->actingAs($admin)->call('POST', 'teams', $this->team->toArray());

        $response = $this->actingAs($admin)->call('PATCH', '/teams/1', $this->teamEdited->toArray());

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertDatabaseHas('teams', $this->teamEdited->toArray());
        $response->assertRedirect('/');
    })->skip();

    test('Delete' ,function ()
    {
        
        $admin = App\Models\User::factory()->create([ 'id' => rand(1000, 9999) ]);
        $team = App\Models\Team::factory()->create([
            'user_id' => $admin->id,
            'name' => 'Awesomeness'
        ]);
        $admin->roles()->attach($this->role);
        $admin->teams()->attach($team);

        $response = $this->actingAs($admin)->call('DELETE', '/teams/'.$team->id);
        $this->assertEquals(302, $response->getStatusCode());
        $response->assertRedirect('/teams');
    })->skip();

