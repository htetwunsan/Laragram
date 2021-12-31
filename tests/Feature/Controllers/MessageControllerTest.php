<?php

namespace Tests\Feature\controllers;

use App\Models\Room;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MessageControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_store()
    {
        /** @var User */
        $user = User::factory()->createOne();
        $room = Room::create();

        $response = $this->actingAs($user)->postJson(route('api.room.message.store', ['room' => $room]), ['body' => 'Hello World']);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => [
                    'user_id' => $user->id,
                    'room_id' => $room->id,
                    'body' => 'Hello World'
                ],
                'success' => 'Message created.'
            ]);
    }
}
