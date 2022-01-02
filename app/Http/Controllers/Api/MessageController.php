<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Events\RoomUpdated;
use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Auth;

class MessageController extends Controller
{
    public function index(Room $room)
    {
        return Auth::user()->becomeParticipantOf($room)
            ->roomMessagesNotDeleted()->with(['participant', 'likedByParticipants'])
            ->latest()->latest('id')->cursorPaginate();
    }

    public function store(Request $request, Room $room)
    {
        switch ($request->content_type) {
            case 'text':
                $contentValidation = ['required', 'string', 'max:2048'];
                break;
            case 'like':
                $contentValidation = ['nullable'];
                break;
            case 'image':
                $contentValidation = ['image'];
                break;
            default:
                $contentValidation = ['required', 'string', 'max:2048'];
                break;
        }

        $request->validate([
            'content_type' => ['required', 'in:text,like,image'],
            'content' => $contentValidation
        ]);

        $p = Auth::user()->becomeParticipantOf($room);

        switch ($request->content_type) {
            case 'text':
                $message = $p->createTextMessage($request->content);
                break;
            case 'like':
                $message = $p->createLikeMessage();
                break;
            case 'image':
                $message = $p->createImageMessage($request->file('content'));
                break;
            default:
                break;
        }

        broadcast(new MessageSent($message))->toOthers();
        broadcast(new RoomUpdated($room));

        return response()->json($message, 201);
    }
}
