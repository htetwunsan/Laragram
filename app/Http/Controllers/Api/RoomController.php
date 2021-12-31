<?php

namespace App\Http\Controllers\Api;

use App\Events\RoomUpdated;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Room;
use App\Services\RoomService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $rooms =  $user->rooms()->with([
            'latestMessage' => function ($query) use ($user) {
                $query->whereNotIn('user_id', [$user->id]);
            },
            'participants'
        ])->latest('updated_at')->latest('id')->cursorPaginate();

        $rooms->each(function ($room) use ($user) {
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
            'user_ids.*' => ['exists:users,id']
        ]);

        if (count($request->user_ids) > 1) {
            throw ValidationException::withMessages(['Group chat currently not supported.']);
        }

        $roomService = new RoomService;
        $room = $roomService->firstOrCreateRoom(Auth::user(), ...$request->user_ids);
        broadcast(new RoomUpdated($room));

        return response()->json($room, 201);
    }

    public function show(Room $room)
    {
        return $room->load('participants');
    }
}
