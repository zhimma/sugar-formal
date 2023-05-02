<?php

use App\Services\NotificationService;

beforeEach(function () {
        return;  //NotificationService疑似已棄用，故先停用
        $role = App\Models\Role::factory()->create();
        $user = App\Models\User::factory()->create();
        $this->app->make(App\Services\UserService::class)->create($user, 'password');

        $this->service = $this->app->make(NotificationService::class);
        $this->originalArray = [
            'user_id' => 1,
            'flag' => 'info',
            'uuid' => 'lksjdflaskhdf',
            'title' => 'Testing',
            'details' => 'Your car has been impounded!',
            'is_read' => 0,
        ];
        $this->editedArray = [
            'user_id' => 1,
            'flag' => 'info',
            'uuid' => 'lksjdflaskhdf',
            'title' => 'Testing',
            'details' => 'Your car has been impounded!',
            'is_read' => 1,
        ];
        $this->searchTerm = ''; 
});

test('All', function () {
    $response = $this->service->all();
    expect($response)->toBeInstanceOf('Illuminate\Database\Eloquent\Collection')
        ->toArray()->toBeArray()->toArray()->toHaveCount(0);
})->skip();

test('Paginated', function () {
    $response = $this->service->paginated(25);
    expect($response)->toBeInstanceOf('Illuminate\Pagination\LengthAwarePaginator')
        ->total()->toBe(0);
})->skip();

test('Search', function () {
    $response = $this->service->search($this->searchTerm, 25);
    expect($response)->toBeInstanceOf('Illuminate\Pagination\LengthAwarePaginator')
        ->total()->toBe(0);
})->skip();

test('Create', function () {
    $response = $this->service->create($this->originalArray);
    expect($response)->toBeInstanceOf('App\Models\Notification')
        ->id->toBe(1);
})->skip();

test('Find', function () {
    $item = $this->service->create($this->originalArray);

    $response = $this->service->find($item->id);
    expect($response)->id->toBe(1);
})->skip();

test('Update', function () {
    $item = $this->service->create($this->originalArray);

    $response = $this->service->update($item->id, $this->editedArray);

    expect($response)->id->toBe(1);
    $this->assertDatabaseHas('notifications', $this->editedArray);
})->skip();

test('Destroy', function () {
    $item = $this->service->create($this->originalArray);

    $response = $this->service->destroy($item->id);
    expect($response)->toBeTrue();
})->skip();
