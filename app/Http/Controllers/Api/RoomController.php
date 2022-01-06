<?php

namespace App\Http\Controllers\Api;

use App\Events\RoomUpdated;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\User;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rooms =  $user->notDeletedRooms()->with([
            'participants' => function ($query) {
                $query->latest()->latest('id');
            }
        ])->latest('updated_at')->latest('id')->cursorPaginate();
        $rooms->each(function (Room $room) use ($user) {
            $room->setRelation('latestMessage', $room->latestMessageOfParticipant($user->becomeParticipantOf($room)));
            if (!is_null($room->latestMessage)) {
                $room->latestMessage->setAttribute(
                    'seen_by_auth_user',
                    $room->latestMessage->seenByParticipants()->where('participants.user_id', $user->id)->exists()
                );
            }
        });

        return $rooms;
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_ids' => ['required', 'array', 'between:1,10'],
            'user_ids.*' => ['exists:users,id', 'distinct']
        ]);

        $roomService = new RoomService;
        $room = $roomService->firstOrCreateRoom(Auth::user(), ...$request->user_ids);
        broadcast(new RoomUpdated($room));

        return response()->json($room, 201);
    }

    public function show(Room $room)
    {
        $room->load(['participants' => function ($query) {
            $query->latest()->latest('id');
        }]);
        $room->participants->each(function (Participant $participant) {
            $participant->user->addAuthRelatedAttributes(['is_blocked']);
        });
        return $room;
    }

    public function update(Room $room, Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255']
        ]);
        $room->update($request->all());

        broadcast(new RoomUpdated($room));

        return $room->fresh();
    }
}
