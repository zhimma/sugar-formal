<?php

use App\Services\FeatureService;

beforeEach(function () {
        return;  //FeatureService疑似已棄用，故先停用
        $role = App\Models\Role::factory()->create();
        $user = App\Models\User::factory()->create();
        $this->app->make(App\Services\UserService::class)->create($user, 'password');

        $this->service = $this->app->make(FeatureService::class);
        $this->originalArray = [
            'id' => 1,
            'key' => 'signup',
        ];
        $this->editedArray = [
            'id' => 1,
            'key' => 'registration',
        ];
        $this->searchTerm = '';   
});

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
    expect($response)->toBeInstanceOf('App\Models\Feature')
        ->id->toBe(1);
})->skip();

test('Find', function () {
    $item = $this->service->create($this->originalArray);
    $response = $this->service->find($item->id);
    expect($response)->id->toBe(1);
})->skip();
