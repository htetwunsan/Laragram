<?php

use App\Http\Controllers\BlockingController;
use App\Http\Controllers\Api\FollowingController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\SaveController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ViewController;
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

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/home', function () {
        $users = User::whereNotIn('id', [auth()->id()])->latest('id')->take(20)->get();
        return response()->json($users);
    });

    Route::post('/users/{user}/follow', [FollowingController::class, 'userFollow'])
        ->name('api.user.follow');
    Route::post('/users/{user}/unfollow', [FollowingController::class, 'userUnfollow'])
        ->name('api.user.unfollow');
    Route::post('/users/{user}/remove-follower', [FollowingController::class, 'removeFollower'])
        ->name('api.user.remove-follower');

    Route::post('/authors/{post}/follow', [FollowingController::class, 'authorFollow'])
        ->name('api.author.follow');
    Route::post('/authors/{post}/unfollow', [FollowingController::class, 'authorUnfollow'])
        ->name('api.author.unfollow');

    Route::post('/users/{user}/block', [BlockingController::class, 'block'])
        ->name('api.user.block');
    Route::post('/users/{user}/unblock', [BlockingController::class, 'unblock'])
        ->name('api.user.unblock');

    Route::post('/posts/{post}/like', [LikeController::class, 'postLike'])
        ->name('api.post.like');
    Route::post('/posts/{post}/unlike', [LikeController::class, 'postUnlike'])
        ->name('api.post.unlike');

    Route::post('/posts/{post}/save', [SaveController::class, 'save'])
        ->name('api.post.save');
    Route::post('/posts/{post}/unsave', [SaveController::class, 'unsave'])
        ->name('api.post.unsave');

    Route::post('/comments/{comment}/like', [LikeController::class, 'commentLike'])
        ->name('api.comment.like');
    Route::post('/comments/{comment}/unlike', [LikeController::class, 'commentUnlike'])
        ->name('api.comment.unlike');

    Route::post('/stories/{story}/view', [ViewController::class, 'storyView'])
        ->name('api.story.view');
});
