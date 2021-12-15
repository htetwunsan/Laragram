<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\EnsureAuthorIsNotAuthUser;
use App\Http\Middleware\EnsureUserIsNotAuthUser;
use App\Models\Post;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use InvalidArgumentException;

class FollowingController extends Controller
{
    public function userFollow(User $user)
    {
        try {
            auth()->user()->follow($user);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['success' => 'You have followed ' . $user->username . '.']);
    }

    public function userUnfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return response()->json(['success' => 'You have unfollowed ' . $user->username . '.']);
    }

    public function removeFollower(User $user)
    {
        auth()->user()->removeFollower($user);

        return response()->json(['success' => 'You have removed follower ' . $user->username . '.']);
    }

    public function authorFollow(Post $post)
    {
        $user = $post->user;

        try {
            auth()->user()->follow($user);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }

        return response()->json(['success' => 'You have followed ' . $user->username . '.']);
    }

    public function authorUnfollow(Post $post)
    {
        $user = $post->user;

        auth()->user()->unfollow($user);

        return response()->json(['success' => 'You have unfollowed ' . $user->username . '.']);
    }
}
