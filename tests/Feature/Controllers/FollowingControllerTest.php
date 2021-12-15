<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FollowingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::factory(5)->hasPosts(1)->create();
    }

    public function test_user_follow_another_user_return_success_response()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $response = $this->actingAs($user1)->post(route('user.follow', ['user' => $user2]));

        $response->assertSessionHas('success', 'Followed');
    }

    public function test_user_follow_already_followed_user_return_success_response()
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        $user1->follow($user2);

        $response = $this->actingAs($user1)->post(route('user.follow', ['user' => $user2]));

        $response->assertSessionHas('success', 'Followed');
    }

}
