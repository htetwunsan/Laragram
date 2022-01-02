<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageLiked;
use App\Events\MessageUnliked;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantLikeController extends Controller
{
    public function like(Room $room, Message $message)
    {
        Auth::user()->becomeParticipantOf($room)->likeMessage($message);
        $message->load('likes');
        broadcast(new MessageLiked($message))->toOthers();
        return $message;
    }

    public function unlike(Room $room, Message $message)
    {
        Auth::user()->becomeParticipantOf($room)->unlikeMessage($message);
        $message->load('likes');
        broadcast(new MessageUnliked($message))->toOthers();
        return $message;
    }
}
