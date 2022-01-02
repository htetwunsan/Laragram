<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSeen;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class SeenMessageController extends Controller
{
    public function seeMessage(Message $message)
    {
        $seenMessage = Auth::user()->becomeParticipantOf($message->room)->seeMessage($message);
        if ($seenMessage->wasRecentlyCreated) {
            broadcast(new MessageSeen($message))->toOthers();
        }
        return $seenMessage;
    }

    public function seeRoomMessages(Room $room)
    {
        $participant = Auth::user()->becomeParticipantOf($room);
        $messages = $participant->roomMessagesNotMineNotDeletedNotSeen()->latest()->latest('id')->get();
        $seenMessages = $participant->seeMessages($messages);

        $latestSeenMessage = $seenMessages->first();
        $latestMessage = $messages->first();
        if (!is_null($latestMessage) && !is_null($latestSeenMessage) && $latestSeenMessage->wasRecentlyCreated) {
            broadcast(new MessageSeen($latestMessage))->toOthers();
        }
        return $seenMessages;
    }

    public function seenByParticipants(Message $message)
    {
        return $message->load('seenByParticipants');
    }
}
