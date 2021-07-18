<?php

use App\Models\User;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id == (int) $id;
});

Broadcast::channel('Chat.{from_user}.{to_user}', function (User $cur_user, User $to_user, User $from_user) {
    return $cur_user->id == $from_user->id || $cur_user->id == $to_user->id;
});

Broadcast::channel('ChatRead.{from_user}.{to_user}', function (User $cur_user, User $to_user, User $from_user) {
    return $cur_user->id == $from_user->id || $cur_user->id == $to_user->id;
});

Broadcast::channel('NewMessage.{id}', function (User $user, $id) {
    return (int) $user->id == (int) $id;
});

Broadcast::channel('Online', function (User $user) {
    return ['id' => $user->id, 'name' => $user->name, 'engroup' => $user->engroup];
});
