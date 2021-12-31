<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\BlockingController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\FollowingController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ChatController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

require __DIR__ . '/auth.php';


if (env('app_debug')) {
    Route::get('/test', function (Request $request) {
        return view('test');
    });
}

Route::middleware('auth')->group(function () {
    Route::get('/', [PostController::class, 'index'])
        ->name('post.index');

    Route::get('/explore', [PostController::class, 'explore'])
        ->name('post.explore');

    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('post.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('post.store');

    Route::get('/posts/{post}', [PostController::class, 'show'])
        ->name('post.show');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->name('post.destroy');

    Route::get('/posts/{post}/comments', [CommentController::class, 'index'])
        ->name('post.comment.index');

    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->name('post.comment.store');

    Route::delete('/posts/{post}/comments/{comment}', [CommentController::class, 'destroy'])
        ->name('post.comment.destroy');

    Route::get('/explore/search', [UserController::class, 'search'])
        ->name('user.search');

    Route::get('/explore/people', [UserController::class, 'explore'])
        ->name('user.explore');

    Route::get('/{user:username}', [UserController::class, 'show'])
        ->name('user.show');

    Route::get('/{user:username}/feeds', [UserController::class, 'showFeeds'])
        ->name('user.show.feeds');

    Route::get('/{user:username}/saved', [UserController::class, 'showSaved'])
        ->name('user.show.saved');

    Route::get('/{user:username}/stories', [StoryController::class, 'index'])
        ->name('user.story.index');

    Route::get('/{user:username}/followings', [FollowingController::class, 'followings'])
        ->name('user.followings');

    Route::get('/{user:username}/followers', [FollowingController::class, 'followers'])
        ->name('user.followers');

    Route::post('/stories', [StoryController::class, 'store'])
        ->name('story.store');

    Route::delete('/stories/{story}', [StoryController::class, 'destroy'])
        ->name('story.destroy');

    Route::post('/users/{user}/follow', [FollowingController::class, 'userFollow'])
        ->name('user.follow');

    Route::post('/users/{user}/unfollow', [FollowingController::class, 'userUnfollow'])
        ->name('user.unfollow');

    Route::post('/authors/{user}/follow', [FollowingController::class, 'authorFollow'])
        ->name('author.follow');

    Route::post('/authors/{user}/unfollow', [FollowingController::class, 'authorUnfollow'])
        ->name('author.unfollow');

    Route::post('/users/{user}/block', [BlockingController::class, 'block'])
        ->name('user.block');

    Route::post('/users/{user}/unblock', [BlockingController::class, 'unblock'])
        ->name('user.unblock');

    Route::get('/auth/activity', [ActivityController::class, 'index'])
        ->name('auth.activity');


    Route::prefix('/direct')->group(function () {
        //React root and will handle the rest routing by react
        Route::get('/inbox', [ChatController::class, 'index'])
            ->name('chat.index');

        Route::fallback(function () {
            return redirect()->route('chat.index');
        });
    });
});
