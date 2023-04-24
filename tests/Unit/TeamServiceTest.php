<?php

use App\Services\TeamService;
use App\Services\UserService;

beforeEach(function () {
        return;  //找不到Team相關的功能，先停用
        $this->service = $this->app->make(TeamService::class);
        $this->userService = $this->app->make(UserService::class);

        $this->originalArray = [
            'user_id' => 1,
            'name' => 'Awesomeness',
        ];
        $this->editedArray = [
            'user_id' => 1,
            'name' => 'Hackers',
        ];
        $this->searchTerm = 'who';
});

test('All', function () {
    $user = App\Models\User::factory()->create();
    $this->userService->joinTeam($user->id, 1);
    $response = $this->service->all($user->id);
    expect($response)->toBeInstanceOf('Illuminate\Database\Eloquent\Collection')
        ->toArray()->toBeArray()->toArray()->toHaveCount(0);
})->skip();

test('Paginated', function () {
    $user = App\Models\User::factory()->create();
    $this->userService->joinTeam($user->id, 1);
    $response = $this->service->paginated(1, 25);
    expect($response)->toBeInstanceOf('Illuminate\Pagination\LengthAwarePaginator')
        ->total()->toBe(0);
})->skip();

test('Search', function () {
    $user = App\Models\User::factory()->create();
    $this->userService->joinTeam($user->id, 1);
    $response = $this->service->search(1, $this->searchTerm, 25);
    expect($response)->toBeInstanceOf('Illuminate\Pagination\LengthAwarePaginator')
        ->total()->toBe(0);
})->skip();

test('Create', function () {
    $user = App\Models\User::factory()->create();
    $response = $this->service->create($user->id, $this->originalArray);
    expect($response)->toBeInstanceOf('App\Models\Team')
        ->id->toBe(1);
})->skip();

test('Invite', function () {
    $admin = App\Models\User::factory()->create();
    $team = $this->service->create($admin->id, $this->originalArray);
    $user = App\Models\User::factory()->create();
    $response = $this->service->invite($admin, $team->id, $user->email);
    expect($response)->toBeTrue();
})->skip();

test('Remove', function () {
    $admin = App\Models\User::factory()->create();
    $team = $this->service->create($admin->id, $this->originalArray);
    $user = App\Models\User::factory()->create();
    $response = $this->service->remove($admin, $team->id, $user->id);
    expect($response)->toBeTrue();
})->skip();

test('Find', function () {
    $admin = App\Models\User::factory()->create();
    $team = $this->service->create($admin->id, $this->originalArray);
    $response = $this->service->find($team->id);
    
    expect($response)->id->toBe($team->id);
})->skip();

test('Update', function () {
    $admin = App\Models\User::factory()->create();
    $team = $this->service->create($admin->id, $this->originalArray);

    $response = $this->service->update($team->id, $this->editedArray);

    expect($response)->id->toBe($team->id);
    $this->assertDatabaseHas('teams', $this->editedArray);
})->skip();

test('Destroy', function () {
    $admin = App\Models\User::factory()->create();
    $team = $this->service->create($admin->id, $this->originalArray);

    $response = $this->service->destroy($admin, $team->id);
    expect($response)->toBeTrue();
})->skip();
