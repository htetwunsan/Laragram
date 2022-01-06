<?php

namespace Tests\Feature\Services;

use App\Models\Participant;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Services\RoomService;

class RoomServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RoomService $service;

    protected function setUp(): void
    {
        parent::setup();

        $this->service = new RoomService;
    }

    public function test_solo_room_already_exists_return_correct_result()
    {
        $user = User::factory()->createOne();

        $user = User::find($user->id);

        self::assertNull($this->service->findSoloRoom($user));

        $room = Room::create(['type' => 'solo']);
        Participant::create(['user_id' => $user->id, 'room_id' => $room->id]);

        self::assertTrue($room->is($this->service->findSoloRoom($user)));
    }

    public function test_direct_room_already_exists_return_correct_result()
    {
        $users = User::factory(2)->create();

        $users = User::find($users->pluck('id'));

        self::assertNull($this->service->findDirectRoom($users[0], $users[1]));

        $room = Room::create(['type' => 'direct']);
        Participant::create(['user_id' => $users[0]->id, 'room_id' => $room->id]);
        Participant::create(['user_id' => $users[1]->id, 'room_id' => $room->id]);

        self::assertTrue($room->is($this->service->findDirectRoom($users[0], $users[1])));
    }

    public function test_group_room_already_exists_return_correct_result()
    {
        $users = User::factory(3)->create();

        $users = User::find($users->pluck('id'));

        self::assertNull($this->service->findGroupRoom(...$users));

        $room = Room::create(['type' => 'group']);
        Participant::create(['user_id' => $users[0]->id, 'room_id' => $room->id]);
        Participant::create(['user_id' => $users[1]->id, 'room_id' => $room->id]);
        Participant::create(['user_id' => $users[2]->id, 'room_id' => $room->id]);

        self::assertTrue($room->is($this->service->findGroupRoom(...$users)));
    }

    public function test_first_or_create_solo_room_return_correct_room_with_participants()
    {
        $user = User::factory()->createOne();

        $user = User::find($user->id);

        $room = $this->service->firstOrCreateSoloRoom($user);

        self::assertInstanceOf(Room::class, $room);
        self::assertTrue($room->isSolo);
        self::assertCount(1, $room->participants);
        self::assertTrue($room->participants->first()->is_admin);
        self::assertTrue($user->is($room->participants->first()->user));
        self::assertTrue($room->is($this->service->firstOrCreateSoloRoom($user)));
    }

    public function test_first_or_create_direct_room_return_correct_room_with_participants()
    {
        $users = User::factory(2)->create();

        $users = User::find($users->pluck('id'));

        $room = $this->service->firstOrCreateDirectRoom($users[0], $users[1]);

        self::assertInstanceOf(Room::class, $room);
        self::assertTrue($room->isDirect);
        self::assertEquals(2, $room->participants()->count());
        self::assertTrue($room->participants->first()->is_admin);
        self::assertTrue($room->participants()->with('user')->get()->pluck('user')
            ->diff($users)
            ->isEmpty());
        self::assertTrue($room->is($this->service->firstOrCreateDirectRoom($users[0], $users[1])));
    }

    public function test_first_or_create_group_room_return_correct_room_with_pariticipants()
    {
        $users = User::factory(3)->create();

        $users = User::find($users->pluck('id'));

        $room = $this->service->firstOrCreateGroupRoom(...$users);

        self::assertInstanceOf(Room::class, $room);
        self::assertTrue($room->isGroup);
        self::assertEquals(3, $room->participants()->count());
        self::assertTrue($room->participants->first()->is_admin);
        self::assertTrue($room->participants()->with('user')->get()->pluck('user')
            ->diff($users)
            ->isEmpty());
        self::assertTrue($room->is($this->service->firstOrCreateGroupRoom(...$users)));
    }

    public function test_first_or_create_room_with_auth_user_and_auth_user_return_solo_room()
    {
        $user = User::factory()->createOne();

        $user = User::find($user->id);

        $room = $this->service->firstOrCreateRoom($user, $user->id);

        self::assertInstanceOf(Room::class, $room);
        self::assertTrue($room->isSolo);
        self::assertCount(1, $room->participants);
        self::assertTrue($user->is($room->participants->first()->user));
        self::assertTrue($room->is($this->service->firstOrCreateRoom($user, $user->id)));
    }

    public function test_first_or_create_room_with_auth_user_and_user_return_direct_room()
    {
        $users = User::find(User::factory(2)->create()->pluck('id'));

        $room = $this->service->firstOrCreateRoom($users[0], $users[1]);

        self::assertInstanceOf(Room::class, $room);
        self::assertTrue($room->isDirect);
        self::assertEquals(2, $room->participants()->count());
        self::assertTrue($room->participants()->with('user')->get()->pluck('user')
            ->diff($users)
            ->isEmpty());
        self::assertTrue($room->is($this->service->firstOrCreateRoom($users[0], $users[1])));
    }

    public function test_first_or_create_room_with_auth_user_and_user_and_user_return_group_room()
    {
        $authUser = User::find(User::factory()->create()->id);
        $users = User::find(User::factory(2)->create()->pluck('id'));

        $room = $this->service->firstOrCreateRoom($authUser, ...$users);

        self::assertInstanceOf(Room::class, $room);
        self::assertTrue($room->isGroup);
        self::assertEquals(3, $room->participants()->count());
        self::assertTrue($room->participants()->with('user')->get()->pluck('user')
            ->diff([$authUser, ...$users])
            ->isEmpty());
        self::assertTrue($room->is($this->service->firstOrCreateRoom($authUser, ...$users)));
    }
}
