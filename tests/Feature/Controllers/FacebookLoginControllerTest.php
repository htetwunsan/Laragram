<?php

namespace Tests\Feature\Controllers;

use App\Http\Controllers\FacebookLoginController;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FakeFacebookUser extends \Laravel\Socialite\Two\User
{

    public function __construct($id, $name, $email, $avatar, $token, $refreshToken)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->avatar = $avatar;
        $this->token = $token;
        $this->refreshToken = $refreshToken;
    }
}

class FacebookLoginControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function getFakeFacebookUser()
    {
        return new FakeFacebookUser(
            $this->faker->uuid(),
            $this->faker->name(),
            $this->faker->email(),
            $this->faker->imageUrl(),
            'fake_facebook_token',
            'fake_facebook_refresh_token'
        );
    }

    public function assertFacebookUserEqualsUser($facebookUser, $user)
    {
        self::assertEquals($facebookUser->id, $user->facebook_id);
        self::assertEquals($facebookUser->name, $user->name);
        self::assertEquals($facebookUser->email, $user->email);
        self::assertEquals($facebookUser->token, $user->facebook_token);
        self::assertEquals($facebookUser->refreshToken, $user->facebook_refresh_token);
        self::assertNotEmpty($user->username);
    }

    public function test_process_user_return_correct_user()
    {
        $controller = new FacebookLoginController;

        $fbUser = $this->getFakeFacebookUser();

        $user = $controller->processUser($fbUser);

        $this->assertFacebookUserEqualsUser($fbUser, $user);
    }

    public function test_process_user_without_email_return_correct_user()
    {
        $controller = new FacebookLoginController;

        $fbUser = $this->getFakeFacebookUser();
        $fbUser->email = null;

        $user = $controller->processUser($fbUser);

        $this->assertFacebookUserEqualsUser($fbUser, $user);
    }

    public function test_process_user_without_name_return_correct_user()
    {
        $controller = new FacebookLoginController;

        $fbUser = $this->getFakeFacebookUser();
        $fbUser->email = null;

        $user = $controller->processUser($fbUser);

        $this->assertFacebookUserEqualsUser($fbUser, $user);
    }

    public function test_process_user_without_email_and_name_return_correct_user()
    {
        $controller = new FacebookLoginController;

        $fbUser = $this->getFakeFacebookUser();
        $fbUser->email = null;
        $fbUser->name = null;

        $user = $controller->processUser($fbUser);

        $this->assertFacebookUserEqualsUser($fbUser, $user);
    }

    public function test_process_user_throw_exception_when_email_duplicates()
    {
        $user = User::factory()->createOne(['email' => 'test@gmail.com']);

        $controller = new FacebookLoginController;

        $fbUser = $this->getFakeFacebookUser();
        $fbUser->email = $user->email;

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Account may be already created.');

        $controller->processUser($fbUser);
    }

    public function test_process_user_return_updated_token_and_refresh_token_user_if_user_already_exists()
    {
        $controller = new FacebookLoginController;

        $controller->processUser($this->getFakeFacebookUser());


        $fbUser = $this->getFakeFacebookUser();
        $fbUser->token = 'updated_token';
        $fbUser->refreshToken = 'updated_refresh_token';

        $user = $controller->processUser($fbUser);

        $this->assertFacebookUserEqualsUser($fbUser, $user);
    }
}
