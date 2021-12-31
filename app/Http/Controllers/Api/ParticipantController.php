<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ParticipantController extends Controller
{
    public function muteRoom(Room $room)
    {
        $p = Auth::user()->becomeParticipantOf($room);
        if (!is_null($p)) {
            $p->muteRoom();
            return $room->fresh()->load('participants');
        }
        throw ValidationException::withMessages(['You are not a paritcipant of this room']);
    }

    public function unmuteRoom(Room $room)
    {
        $p = Auth::user()->becomeParticipantOf($room);
        if (!is_null($p)) {
            $p->unmuteRoom();
            return $room->fresh()->load('participants');
        }
        throw ValidationException::withMessages(['You are not a paritcipant of this room']);
    }

    public function deleteRoom(Room $room)
    {
        $p = Auth::user()->becomeParticipantOf($room);
        if (!is_null($p)) {
            $p->deleteRoom();
            return response()->json([], 204);
        }
        throw ValidationException::withMessages(['You are not a paritcipant of this room']);
    }
}
