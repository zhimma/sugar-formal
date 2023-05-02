<?php

use App\Services\RoleService;

beforeEach(function () {
    $this->service = $this->app->make(RoleService::class);
    
    $this->originalArray = [
        'name' => 'coders',
        'label' => 'Coders',
        'permissions' => ['super' => 'on'],
    ];
    $this->modifiedArray = [
        'name' => 'hackers',
        'label' => 'Hackers',
        'permissions' => [],
    ];
    $this->editedArray = [
        'name' => 'hackers',
        'label' => 'Hackers',
        'permissions' => '',
    ];
    $this->searchTerm = 'who';    
});

test('All', function () {
    try{
        $response = $this->service->all();
        expect($response)->toBeInstanceOf('Illuminate\Database\Eloquent\Collection')
            ->toArray()->toBeArray()->toArray()->toHaveCount(3);
    }catch(Throwable $e){
        $notification_string = test_notification(__CLASS__, __FUNCTION__, __LINE__,__FILE__);
        $this->handleCatchedException($e,$notification_string);
    }        
});
