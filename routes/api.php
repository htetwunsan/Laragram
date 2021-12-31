<?php

use App\Http\Controllers\BlockingController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\ParticipantController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\SeenMessageController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/home', function () {
        $users = User::whereNotIn('id', [auth()->id()])->latest('id')->take(20)->get();
        return response()->json($users);
    });

    Route::get('/auth/user', function () {
        return Auth::user();
    })->name('auth.user');

    Route::post('/users/{user}/follow', [FollowingController::class, 'userFollow'])
        ->name('user.follow');
    Route::post('/users/{user}/unfollow', [FollowingController::class, 'userUnfollow'])
        ->name('user.unfollow');
    Route::post('/users/{user}/remove-follower', [FollowingController::class, 'removeFollower'])
        ->name('user.remove-follower');

    Route::post('/authors/{post}/follow', [FollowingController::class, 'authorFollow'])
        ->name('author.follow');
    Route::post('/authors/{post}/unfollow', [FollowingController::class, 'authorUnfollow'])
        ->name('author.unfollow');

    Route::post('/users/{user}/block', [BlockingController::class, 'block'])
        ->name('user.block');
    Route::post('/users/{user}/unblock', [BlockingController::class, 'unblock'])
        ->name('user.unblock');

    Route::post('/posts/{post}/like', [LikeController::class, 'postLike'])
        ->name('post.like');
    Route::post('/posts/{post}/unlike', [LikeController::class, 'postUnlike'])
        ->name('post.unlike');

    Route::post('/posts/{post}/save', [SaveController::class, 'save'])
        ->name('post.save');
    Route::post('/posts/{post}/unsave', [SaveController::class, 'unsave'])
        ->name('post.unsave');

    Route::post('/comments/{comment}/like', [LikeController::class, 'commentLike'])
        ->name('comment.like');
    Route::post('/comments/{comment}/unlike', [LikeController::class, 'commentUnlike'])
        ->name('comment.unlike');

    Route::post('/stories/{story}/view', [ViewController::class, 'storyView'])
        ->name('story.view');

    Route::get('/explore/search', [UserController::class, 'search'])
        ->name('user.search');

    Route::get('/rooms', [RoomController::class, 'index'])
        ->name('room.index');

    Route::post('/rooms', [RoomController::class, 'store'])
        ->name('room.store');

    Route::get('/rooms/{room}', [RoomController::class, 'show'])
        ->name('room.show');

    Route::get('/rooms/{room}/messages', [MessageController::class, 'index'])
        ->name('room.message.index');

    Route::post('/rooms/{room}/messages', [MessageController::class, 'store'])
        ->name('room.message.store');

    Route::post('/messages/{message}/see', [SeenMessageController::class, 'seeMessage'])
        ->name('message.see');

    Route::get('/messages/{message}/seen-by-participants', [SeenMessageController::class, 'seenByParticipants'])
        ->name('message.seen-by-participants');

    Route::post('/rooms/{room}/messages/see', [SeenMessageController::class, 'seeRoomMessages'])
        ->name('room.message.see');

    Route::post('/rooms/{room}/mute', [ParticipantController::class, 'muteRoom'])
        ->name('room.participant.mute');

    Route::post('/rooms/{room}/unmute', [ParticipantController::class, 'unmuteRoom'])
        ->name('room.participant.unmute');

    Route::post('/rooms/{room}/delete-room', [ParticipantController::class, 'deleteRoom'])
        ->name('room.participant.delete-room');
});
