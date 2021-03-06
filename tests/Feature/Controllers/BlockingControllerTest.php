<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlockingControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    use RefreshDatabase;

    public function test_blocking_yourself_return_appropriate_errors()
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post(route('user.block', ['user' => $user]));

        $response->assertSessionHasErrors(['nonField']);


        $response = $this->actingAs($user)
            ->withHeader('accept', 'application/json')
            ->post(route('user.block', ['user' => $user]));


        $response->assertJson([
            'errors' => [
                'nonField' => ['You cannot block yourself.']
            ]
        ]);
    }
}
