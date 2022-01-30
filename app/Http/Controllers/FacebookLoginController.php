<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Auth;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Log;
use Str;

class FacebookLoginController extends Controller
{

    public function login()
    {
        return Socialite::driver('facebook')->redirect();
    }


    public function handleLoginDone()
    {
        try {
            $facebookUser = Socialite::driver('facebook')->user();
        } catch (InvalidStateException $e) {
            Log::debug($e);
            return redirect()->route('login')
                ->withErrors(['nonField' => 'Please login again from here.']);
        }

        try {
            $user = $this->processUser($facebookUser);
        } catch (Exception $e) {
            return redirect()->route('login')
                ->withErrors(['nonField' => $e->getMessage()])
                ->withInput(['email' => $facebookUser->email]);
        }

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }

    public function processUser($facebookUser)
    {
        $user = User::whereFacebookId($facebookUser->id)->first();

        if ($user) {
            $user->update([
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken
            ]);
        } else {
            if (!is_null($facebookUser->email) && User::whereEmail($facebookUser->email)->exists()) {
                throw new Exception('Account may be already created.');
            }
            $user = User::create([
                'name' => $facebookUser->name,
                'email' => $facebookUser->email,
                'password' => Hash::make(Str::random()),
                'profile_image' => $facebookUser->avatar,
                'facebook_id' => $facebookUser->id,
                'facebook_token' => $facebookUser->token,
                'facebook_refresh_token' => $facebookUser->refreshToken
            ]);
        }
        return $user;
    }
}
