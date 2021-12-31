<?php

namespace App\Services;

use App\Models\User;
use App\Models\Room;
use Illuminate\Database\Eloquent\Collection;

class RoomService
{
    public function findSoloRoom(User $user): Room|null
    {
        return $user->rooms()->solo()->first();
    }

    public function findDirectRoom(User $user1, User $user2): Room|null
    {
        return $user1->rooms()->direct()->get()->concat($user2->rooms()->direct()->get())->duplicates()->first();
    }

    public function findGroupRoom(User ...$users): Room|null
    {
        $rooms = $users[0]->rooms()->group(count($users))->get();
        foreach ($rooms as $room) {
            if ($room->participants()->with('user')->get()->pluck('user')->diff($users)->isEmpty()) {
                return $room;
            }
        }
        return null;
    }

    public function firstOrCreateSoloRoom(User $authuser): Room
    {
        $room = $this->findSoloRoom($authuser);
        if (is_null($room)) {
            $room = $authuser->rooms()->create(['type' => 'solo']);
        }
        return $room;
    }

    public function firstOrCreateDirectRoom(User $authuser, User $user): Room
    {
        $room = $this->findDirectRoom($authuser, $user);
        if (is_null($room)) {
            $room = $authuser->rooms()->create(['type' => 'direct']);
            $room->participants()->create(['user_id' => $user->id]);
        }
        return $room;
    }

    public function firstOrCreateGroupRoom(User $authUser, User ...$users): Room
    {
        $room = $this->findGroupRoom(...[$authUser, ...$users]);
        if (is_null($room)) {
            $room = $authUser->rooms()->create(['type' => 'group']);
            foreach ($users as $user) {
                $room->participants()->create(['user_id' => $user->id]);
            }
        }
        return $room;
    }

    public function firstOrCreateRoom(User $authUser, User|int ...$users): Room
    {
        foreach ($users as &$user) {
            if (is_int($user)) {
                $user = User::find($user);
            }
        }

        $users = Collection::make($users);

        if ($users->count() == 1) {
            $user = $users->first();
            if ($authUser->is($user)) {
                return $this->firstOrCreateSoloRoom($authUser);
            }

            return $this->firstOrCreateDirectRoom($authUser, $user);
        }

        return $this->firstOrCreateGroupRoom($authUser, ...$users);
    }
}
