<?php

namespace Tests\Feature\Models;

use App\Models\DeletedMessage;
use App\Models\Message;
use App\Models\Participant;
use App\Models\User;
use App\Models\Room;
use App\Models\SeenMessage;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParticipantTest extends TestCase
{
    use RefreshDatabase;

    private $users, $soloRoom, $soloParticipant, $directRoom, $directParticipants, $groupRoom, $groupParticipants;

    protected function setUp(): void
    {
        parent::setUp();

        $this->users = User::find(User::factory(3)->create()->pluck('id'));

        $this->soloRoom = Room::create(['type' => 'solo']);
        $this->soloParticipant = $this->soloRoom->participants()->create([
            'user_id' => $this->users[0]->id
        ])->fresh();

        $this->directRoom = Room::create(['type' => 'direct']);
        $this->directParticipants = Participant::find(
            $this->directRoom->participants()->createMany([
                ['user_id' => $this->users[0]->id],
                ['user_id' => $this->users[1]->id]
            ])->pluck('id')
        );

        $this->groupRoom = Room::create(['type' => 'group']);
        $this->groupParticipants = Participant::find(
            $this->groupRoom->participants()->createMany([
                ['user_id' => $this->users[0]->id],
                ['user_id' => $this->users[1]->id],
                ['user_id' => $this->users[2]->id]
            ])->pluck('id')
        );

        $this->soloRoom->fresh();
        $this->directRoom->fresh();
        $this->groupRoom->fresh();
    }

    public function test_user_return_correct_relation()
    {
        self::assertEquals($this->users[0], $this->soloRoom->participants()->first()->user);

        self::assertEquals($this->users[0], $this->directRoom->participants[0]->user);
        self::assertEquals($this->users[1], $this->directRoom->participants[1]->user);

        self::assertEquals($this->users[0], $this->groupRoom->participants[0]->user);
        self::assertEquals($this->users[1], $this->groupRoom->participants[1]->user);
        self::assertEquals($this->users[2], $this->groupRoom->participants[2]->user);
    }

    public function test_room_return_correct_relation()
    {
        self::assertEquals($this->soloRoom, $this->soloRoom->participants()->first()->room);
        self::assertEquals($this->directRoom, $this->directRoom->participants()->first()->room);
        self::assertEquals($this->groupRoom, $this->groupRoom->participants()->first()->room);
    }

    public function test_rooms_return_correct_relation()
    {

        self::assertCount(3, $this->soloParticipant->rooms);

        self::assertCount(3, $this->directParticipants[0]->rooms);
        self::assertCount(2, $this->directParticipants[1]->rooms);


        self::assertCount(3, $this->groupParticipants[0]->rooms);
        self::assertCount(2, $this->groupParticipants[1]->rooms);
        self::assertCount(1, $this->groupParticipants[2]->rooms);

        $this->soloParticipant->update(['room_deleted_at' => now()]);
        self::assertCount(2, $this->soloParticipant->fresh()->rooms);
    }

    public function test_deleted_rooms_return_correct_relation()
    {
        $this->soloParticipant->update(['room_deleted_at' => now()]);
        self::assertCount(2, $this->soloParticipant->fresh()->rooms);

        $this->directParticipants[1]->update(['room_deleted_at' => now()]);
        self::assertCount(1, $this->directParticipants[1]->fresh()->rooms);

        $this->groupParticipants[2]->update(['room_deleted_at' => now()]);
        self::assertCount(0, $this->groupParticipants[2]->fresh()->rooms);
    }

    public function test_delete_room_delete_old_room_messages_and_update_room_deleted_at_to_now()
    {
        Message::factory(5)->create(['participant_id' => $this->soloParticipant->id]);

        $result = $this->soloParticipant->deleteRoom();
        self::assertTrue($result);
        self::assertNotNull($this->soloParticipant->room_deleted_at);

        $deletedMessages = DeletedMessage::whereParticipantId($this->soloParticipant->id)
            ->whereRoomId($this->soloParticipant->room_id)
            ->get();

        self::assertCount(5, $deletedMessages);

        $roomMessages = Message::whereParticipantId($this->soloParticipant->id)
            ->whereRoomId($this->soloParticipant->room_id)
            ->whereNotIn('id', $deletedMessages->pluck('message_id'))->get();

        self::assertCount(0, $roomMessages);
    }

    public function test_restore_room_update_room_deleted_at_to_null()
    {
        self::assertTrue($this->soloParticipant->restoreRoom());
        self::assertNull($this->soloParticipant->room_deleted_at);
    }

    public function test_messages_return_correct_relation()
    {
        $messages = Message::find(
            Message::factory(5)->create(['participant_id' => $this->soloParticipant->id])->pluck('id')
        );

        self::assertEquals($messages, $this->soloParticipant->messages);
    }

    public function test_seen_messags_return_correct_relation()
    {
        $messages = Message::factory(5)->create(['participant_id' => $this->directParticipants[0]->id]);
        $seenMessages = $messages->map(function ($message) {
            return SeenMessage::create(
                [
                    'participant_id' => $this->directParticipants[1]->id,
                    'message_id' => $message->id,
                ]
            )->fresh();
        });

        self::assertEquals($seenMessages, $this->directParticipants[1]->seenMessages);
    }

    public function test_see_message_create_correct_relation()
    {
        $message = Message::factory()->create([
            'participant_id' => $this->directParticipants[0]->id
        ])->fresh();

        $seenMessage = $this->directParticipants[1]->seeMessage($message);

        self::assertEquals($seenMessage->message, $message);

        $seenMessage = $this->directParticipants[1]->seeMessage($message);

        self::assertEquals($seenMessage->message, $message);
    }

    public function test_see_messages_create_correct_relation()
    {
        $messages = Message::find(
            Message::factory(5)->create(['participant_id' => $this->soloParticipant->id])->pluck('id')
        );

        $seenMessages = $this->directParticipants[1]->seeMessages($messages);

        self::assertTrue($seenMessages->load('message')->pluck('message')->diff($messages)->isEmpty());
    }

    public function test_deleted_messages_return_correct_relation()
    {
        $messages = Message::factory(5)->create(['participant_id' => $this->directParticipants[0]->id]);

        $deletedMessages = $messages->map(function ($message) {
            return DeletedMessage::create([
                'message_id' => $message->id,
                'participant_id' => $this->directParticipants[1]->id
            ])->fresh();
        });

        self::assertEquals($deletedMessages, $this->directParticipants[1]->deletedMessages);
    }

    public function test_room_messages_return_correct_relation()
    {
        $messages0 = Message::find(
            Message::factory(5)->create(['participant_id' => $this->directParticipants[0]->id])->pluck('id')
        );

        $messages1 = Message::find(
            Message::factory(5)->create(['participant_id' => $this->directParticipants[1]->id])->pluck('id')
        );

        self::assertEquals(
            $messages0->concat($messages1),
            $this->directParticipants[0]->roomMessages
        );
    }

    public function test_room_messages_not_deleted_return_correct_relation()
    {
        $messages = Message::find(
            Message::factory(10)->create(['participant_id' => $this->directParticipants[0]->id])->pluck('id')
        );

        $deletedMessages = $messages->skip(5)->take(5)->map(function ($message) {
            return DeletedMessage::create([
                'message_id' => $message->id,
                'participant_id' => $this->directParticipants[1]->id
            ])->fresh();
        });

        self::assertEquals(
            $messages,
            $this->directParticipants[0]->roomMessagesNotDeleted
        );

        self::assertEquals(
            $messages->take(5),
            $this->directParticipants[1]->roomMessagesNotDeleted
        );
    }

    public function test_room_messages_not_mine_return_correct_relation()
    {
        $messages0 = Message::find(
            Message::factory(5)->create(['participant_id' => $this->directParticipants[0]->id])->pluck('id')
        );

        $messages1 = Message::find(
            Message::factory(5)->create(['participant_id' => $this->directParticipants[1]->id])->pluck('id')
        );

        self::assertEquals(
            $messages1,
            $this->directParticipants[0]->roomMessagesNotMine
        );
    }

    public function test_room_messages_not_mine_not_deleted_return_correct_relation()
    {
        $messages0 = Message::find(
            Message::factory(5)->create(['participant_id' => $this->directParticipants[0]->id])->pluck('id')
        );

        $messages1 = Message::find(
            Message::factory(10)->create(['participant_id' => $this->directParticipants[1]->id])->pluck('id')
        );

        $messages1->skip(5)->take(5)->each(function ($message) {
            return DeletedMessage::create([
                'message_id' => $message->id,
                'participant_id' => $this->directParticipants[0]->id
            ])->fresh();
        });

        self::assertEquals(
            $messages1->take(5),
            $this->directParticipants[0]->roomMessagesNotMineNotDeleted
        );
    }

    public function test_deleted_room_messages_return_correct_relation()
    {
        $messages0 = Message::factory(5)->create(['participant_id' => $this->directParticipants[0]->id]);

        $messages1 = Message::factory(5)->create(['participant_id' => $this->directParticipants[1]->id]);

        $deletedMessages0 = $messages0->map(function ($message) {
            return DeletedMessage::create([
                'message_id' => $message->id,
                'participant_id' => $this->directParticipants[1]->id
            ])->fresh();
        });

        $deletedMessages1 = $messages1->map(function ($message) {
            return DeletedMessage::create([
                'message_id' => $message->id,
                'participant_id' => $this->directParticipants[0]->id
            ])->fresh();
        });

        self::assertEquals(
            $deletedMessages0->concat($deletedMessages1),
            $this->directParticipants[0]->deletedRoomMessages
        );
    }

    public function test_create_message_create_correct_relation()
    {
        $message = $this->directParticipants[0]->createMessage('Hello World');

        self::assertInstanceOf(Message::class, $message);
        self::assertEquals('Hello World', $message->content);
        self::assertEquals($this->directRoom->id, $message->room_id);
        self::assertEquals($this->directParticipants[0]->id, $message->participant_id);
    }

    public function test_delete_message_create_correct_relation()
    {
        $message1 = Message::factory()->create(['participant_id' => $this->directParticipants[1]->id])->fresh();

        $deletedMessage = $this->directParticipants[0]->deleteMessage($message1);

        self::assertEquals($deletedMessage->message, $message1);
    }
}
