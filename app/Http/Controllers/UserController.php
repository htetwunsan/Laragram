<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function explore(Request $request)
    {
        $users = auth()->user()->exploreUsers()
            ->latest()
            ->latest('id')
            ->cursorPaginate(20);

        if ($request->expectsJson()) {
            $users = collect(['html' => view('components.user.users', compact('users'))->render()])
                ->merge($users);
            return response()->json($users);
        }

        return view('user.explore', compact('users'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => ['required']
        ]);

        $q = '%' . $request->q . '%';

        $users = User::whereNotIn('id', [auth()->id()])
            ->where(function ($query) use ($q) {
                $query->where('username', 'LIKE', $q)
                    ->orWhere('name', 'LIKE', $q)
                    ->orWhere('email', 'LIKE', $q);
            })
            ->simplePaginate(20);

        $users = collect(['html' => view('components.user.search-results', compact('users'))->render()])
            ->merge($users);

        return response()->json($users);
    }

    public function show(User $user, Request $request)
    {
        $posts = $user->posts()
            ->with('images')
            ->latest()
            ->latest('id')
            ->cursorPaginate(20);

        if ($request->expectsJson()) {
            $posts = collect(['html' => view('components.user.posts-images', compact('posts'))->render()])
                ->merge($posts);
            return response()->json($posts);
        }

        $activeTab = 'posts';

        $user->loadCount('posts');
        $user->setAttribute('followings_count', $user->followings()->count());
        $user->setAttribute('followers_count', $user->followers()->count());

        if (auth()->id() === $user->id) {
            return view('auth.show', compact('user', 'posts', 'activeTab'));
        }

        $user->addAuthRelatedAttributes(['is_blocking', 'is_following', 'is_followed', 'stories_have_viewed']);

        return view('user.show', compact('user', 'posts', 'activeTab'));
    }

    public function showFeeds(User $user, Request $request)
    {
        $posts = $user->posts()
            ->with(['user', 'images', 'likes', 'comments' => function ($query) {
                $query->with('user');
                $query->latest();
            }])
            ->latest()
            ->latest('id')
            ->cursorPaginate(10);

        $posts->each(function (Post $post) {
            $post->user->addAuthRelatedAttributes(['is_followed']);
            $post->addAuthRelatedAttributes(['has_liked', 'has_saved']);
        });

        if ($request->expectsJson()) {
            $posts = collect(['html' => view('components.user.feeds', compact('posts'))->render()])
                ->merge($posts);
            return response()->json($posts);
        }

        $activeTab = 'feeds';

        $user->loadCount('posts');
        $user->setAttribute('followings_count', $user->followings()->count());
        $user->setAttribute('followers_count', $user->followers()->count());

        if (auth()->id() === $user->id) {
            return view('auth.show-feeds', compact('user', 'posts', 'activeTab'));
        }

        $user->addAuthRelatedAttributes(['is_blocking', 'is_following', 'is_followed', 'stories_have_viewed']);

        return view('user.show-feeds', compact('user', 'posts', 'activeTab'));
    }

    public function showSaved(User $user, Request $request)
    {
        $posts = $user->savedPosts()
            ->with('images')
            ->latest('posts.created_at')
            ->latest('posts.id')
            ->cursorPaginate(15);

        if ($request->expectsJson()) {
            $posts = collect(['html' => view('components.user.posts-images', compact('posts'))->render()])
                ->merge($posts);
            return response()->json($posts);
        }

        $activeTab = 'saved';

        $user->loadCount('posts');
        $user->setAttribute('followings_count', $user->followings()->count());
        $user->setAttribute('followers_count', $user->followers()->count());

        return view('auth.show-saved', compact('user', 'posts', 'activeTab'));
    }
}
