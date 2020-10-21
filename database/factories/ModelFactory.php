<?php
/*
|--------------------------------------------------------------------------
| Feature Factory
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Feature::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'key' => 'user-signup',
        'is_active' => false,
    ];
});

/*
|--------------------------------------------------------------------------
| Activity Factory
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Activity::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'user_id' => 1,
        'description' => 'Standard User Activity',
        'request' => [],
    ];
});

/*
|--------------------------------------------------------------------------
| Notification Factory
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Notification::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'user_id' => 1,
        'flag' => 'info',
        'uuid' => 'lksjdflaskhdf',
        'title' => 'Testing',
        'details' => 'Your car has been impounded!',
        'is_read' => 0,
    ];
});

/*
|--------------------------------------------------------------------------
| Notification Factory
|--------------------------------------------------------------------------
*/

$factory->define(App\Models\Notification::class, function (Faker\Generator $faker) {
    return [
        'id' => 1,
        'user_id' => 1,
        'flag' => 'info',
        'uuid' => 'lksjdflaskhdf',
        'title' => 'Testing',
        'details' => 'Your car has been impounded!',
        'is_read' => 0,
    ];
});
?>
