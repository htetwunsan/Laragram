<?php

use App\Models\Room;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

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

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('users.{user}.rooms', function (User $authUser, User $user) {
    return $authUser->is($user);
});

Broadcast::channel('rooms.{room}', function (User $authUser, Room $room) {
    $p = $authUser->becomeParticipantOf($room);
    if (!is_null($p)) {
        return $p;
    }
});
