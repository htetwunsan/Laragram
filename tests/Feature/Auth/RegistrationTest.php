<?php

namespace Tests\Feature\Auth;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        $response = $this->get('auth/signup');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register()
    {
        $validDate = now()->sub('year', 5);

        $response = $this->post('auth/signup', [
            'email' => 'test@yahoo.com',
            'name' => 'test',
            'password' => 'password',
            'birthday' => $validDate
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);
    }
}
