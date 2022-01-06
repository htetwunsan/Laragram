<?php

namespace App\Http\Controllers\Api;

use App\Events\RoomUpdated;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use App\Models\Room;
use App\Services\RoomService;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RoomParticipantController extends Controller
{
    public function store(Room $room, Request $request)
    {
        $request->validate([
            'user_ids' => ['required', 'array', 'between:1,10'],
            'user_ids.*' => ['exists:users,id', 'distinct']
        ]);

        if ($room->participants()->count() + count($request->user_ids) >= 10) {
            throw ValidationException::withMessages(['Group size limit exceeded. Currently support only 10 members.']);
        }

        $roomService = new RoomService;
        $room = $roomService->addParticipantsToGroupRoom($room, ...$request->user_ids);
        broadcast(new RoomUpdated($room));

        return $room;
    }

    public function update(Room $room, Participant $participant, Request $request)
    {
        Gate::authorize('update', $participant);


        $request->validate([
            'is_admin' => ['required', 'boolean']
        ]);

        $participant->update(['is_admin' => $request->is_admin]);
        return $participant->fresh();
    }

    public function destroy(Room $room, Participant $participant)
    {
        Gate::authorize('delete', $participant);

        $participant->delete();
        return response()->noContent();
    }
}
