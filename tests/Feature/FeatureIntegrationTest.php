<?php
uses(
    Illuminate\Foundation\Testing\WithoutMiddleware::class
);

beforeEach(function () {

    return;  //feature功能疑似已棄用，故先停用

    $this->feature = App\Models\Feature::factory()->make([
        'id' => 1,
        'key' => 'signup'
    ]);
    $this->featureEdited = App\Models\Feature::factory()->make([
        'id' => 1,
        'key' => 'register',
    ]);

    $role = App\Models\Role::factory()->create();
    $user = App\Models\User::factory()->create();
    $user->roles()->attach($role);

    $this->actor = $this->actingAs($user);
});

test('Index', function ()
{
    $response = $this->actor->call('GET', 'admin/features');
    $this->assertEquals(200, $response->getStatusCode());
    $response->assertViewHas('features');
})->skip();

test('Create', function ()
{
    $response = $this->actor->call('GET', 'admin/features/create');
    $this->assertEquals(200, $response->getStatusCode());
})->skip();

test('Store', function ()
{
    $response = $this->actor->call('POST', 'admin/features', $this->feature->toArray());

    $this->assertEquals(302, $response->getStatusCode());
    $response->assertRedirect('admin/features/'.$this->feature->id.'/edit');
})->skip();

test('Edit', function ()
{
    $this->actor->call('POST', 'admin/features', $this->feature->toArray());

    $response = $this->actor->call('GET', 'admin/features/'.$this->feature->id.'/edit');
    $this->assertEquals(200, $response->getStatusCode());
    $response->assertViewHas('feature');
})->skip();

test('Update', function ()
{
    $this->actor->call('POST', 'admin/features', $this->feature->toArray());
    $response = $this->actor->call('PATCH', 'admin/features/1', $this->featureEdited->toArray());

    $this->assertEquals(302, $response->getStatusCode());
    $this->assertDatabaseHas('features', [
        'id' => 1,
        'key' => 'register',
    ]);
    $response->assertRedirect('/');
})->skip();

test('Delete', function ()
{
    $this->actor->call('POST', 'admin/features', $this->feature->toArray());

    $response = $this->call('DELETE', 'admin/features/'.$this->feature->id);
    $this->assertEquals(302, $response->getStatusCode());
    $response->assertRedirect('admin/features');
})->skip();

