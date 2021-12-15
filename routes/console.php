<?php

use App\Models\Story;
use App\Models\User;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('seed:stories', function () {
    $users = User::all();
    for ($_ = 0; $_ < 100; ++$_) {
        $user = $users->random();
        Story::factory(rand(1, 5))
            ->for($user)
            ->create();
    }
})->purpose('Seeding stories');
