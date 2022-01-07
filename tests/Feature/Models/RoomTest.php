<?php

namespace Tests\Feature\Models;

use App\Models\DeletedMessage;
use App\Models\Message;
use App\Models\Participant;
use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoomTest extends TestCase
{
    use RefreshDatabase;

    public function test_latest_message_of_participant_return_correct_relation()
    {
        $users = User::find(User::factory(3)->create()->pluck('id'));

        $directRoom = Room::create(['type' => 'direct'])->fresh();
        $directParticipants = Participant::find(
            $directRoom->participants()->createMany([
                ['user_id' => $users[0]->id],
                ['user_id' => $users[1]->id]
            ])->pluck('id')
        );

        $messages0 = Message::factory(5)->create(['participant_id' => $directParticipants[0]])->map->fresh();
        $messages1 = Message::factory(5)->create(['participant_id' => $directParticipants[1]])->map->fresh();

        $deletedMessages0 = $messages1->skip(3)->take(2)->map(function ($message) use ($directParticipants) {
            return DeletedMessage::create([
                'message_id' => $message->id,
                'participant_id' => $directParticipants[0]->id
            ])->fresh();
        });

        $latestMessage = $directRoom->latestMessageOfParticipant($directParticipants[0]);
        self::assertEquals(
            $messages1[2]->load('participant'),
            $latestMessage
        );
    }
}
