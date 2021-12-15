<?php

namespace App\Http\Controllers;

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
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Followed');
    }

    public function userUnfollow(User $user)
    {
        auth()->user()->unfollow($user);

        return back()->with('success', 'Unfollowed');
    }

    public function authorFollow(Post $post)
    {
        $user = $post->user;

        try {
            auth()->user()->follow($user);
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Followed');
    }

    public function authorUnfollow(Post $post)
    {
        $user = $post->user;

        auth()->user()->unfollow($user);

        return back()->with('success', 'Unfollowed');
    }

    public function followers(User $user, Request $request)
    {
        $followers = $user->followers()->withTimestamps()
            ->latest('pivot_created_at')
            ->simplePaginate(15);

        auth()->user()->load('followings');

        $followers->each(function (User $follower) {
            $follower->addAuthRelatedAttributes(['is_followed']);
        });

        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                $followers = collect([
                    'html' => view('components.auth.followers', ['followers' => $followers])->render()
                ])->merge($followers);
                return response()->json($followers);
            }

            return view('auth.followers-index', compact('followers', 'user'));
        }

        if ($request->expectsJson()) {
            $followers = collect([
                'html' => view('components.user.followers', ['followers' => $followers])->render()
            ])->merge($followers);
            return response()->json($followers);
        }

        return view('user.followers-index', compact('followers', 'user'));
    }

    public function followings(User $user, Request $request)
    {
        $followings = $user->followings()->withTimestamps()
            ->latest('pivot_created_at')
            ->simplePaginate(15);
        
        auth()->user()->load('followings');

        $followings->each(function (User $following) {
            $following->addAuthRelatedAttributes(['is_followed']);
        });

        if ($user->id === auth()->id()) {
            if ($request->expectsJson()) {
                $followings = collect([
                    'html' => view('components.auth.followings', ['followings' => $followings])->render()
                ])->merge($followings);
                return response()->json($followings);
            }

            return view('auth.followings-index', compact('followings', 'user'));
        }

        if ($request->expectsJson()) {
            $followings = collect([
                'html' => view('components.user.followings', ['followings' => $followings])->render()
            ])->merge($followings);
            return response()->json($followings);
        }

        return view('user.followings-index', compact('followings', 'user'));
    }

}
