<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Log;

class PostController extends Controller
{

    public function index(Request $request)
    {
        if (auth()->user()->followings()->count() < 2) {
            $users = auth()->user()->exploreUsers()
                ->with('postImages')
                ->latest()
                ->latest('id')
                ->take(30)
                ->get()
                ->map(function (User $user) {
                    $user->setRelation('postImages', $user->postImages->take(3));
                    return $user;
                });
            return view('post.new-user-index', compact('users'));
        }

        $posts = auth()->user()->recommendedPosts()
            ->with(['user', 'images', 'likes', 'comments' => function ($query) {
                $query->with('user');
                $query->latest();
            }])
            ->latest()
            ->latest('id')
            ->cursorPaginate(10);

        auth()->user()->load(['followings', 'likedPosts', 'savedPosts']);

        $posts->each(function (Post $post) {
            $post->user->addAuthRelatedAttributes(['is_followed']);
            $post->addAuthRelatedAttributes(['has_liked', 'has_saved']);
        });

        if ($request->expectsJson()) {
            $posts = collect(['html' => view('components.post.posts', compact('posts'))->render()])->merge($posts);
            return response()->json($posts);
        }

        auth()->user()->load(['viewedStories', 'activeStories']);

        $users = auth()->user()->recommendedStories()
            ->take(20)
            ->get();

        $users->each(function (User $user) {
            $user->addAuthRelatedAttributes(['stories_have_viewed']);
        });

        auth()->user()->addAuthRelatedAttributes(['stories_have_viewed']);
        auth()->user()->loadCount('activeStories');

        return view('post.index', compact('posts', 'users'));
    }

    public function explore(Request $request)
    {
        $posts = auth()->user()->explorePosts()
            ->latest()
            ->latest('id')
            ->cursorPaginate(20);

        if ($request->expectsJson()) {
            $posts = collect(['html' => view('components.user.posts-images', compact('posts'))->render()])
                ->merge($posts);
            return response()->json($posts);
        }

        return view('post.explore', compact('posts'));
    }

    public function create()
    {
        return view('post.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'caption' => ['nullable'],
            'post_image' => ['required', 'image'],
            'alternate_text' => ['nullable', 'max:255']
        ]);

        auth()->user()->createPost(
            $request->caption,
            $request->file('post_image'),
            $request->alternate_text
        );

        return redirect()->route('post.index')->with('success', 'Your photo was posted.');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'images', 'likes', 'comments' => function ($query) {
            $query->with('user');
            $query->latest();
        }]);

        $post->user->addAuthRelatedAttributes(['is_followed']);
        $post->addAuthRelatedAttributes(['has_liked', 'has_saved']);

        return view('post.show', compact('post'));
    }

    public function destroy(Post $post, Request $request)
    {
        $post->deleteOrFail();

        if ($request->redirect_to == 'index') {
            return redirect()->route('post.index');
        }

        return back()->with('success', 'Post deleted.');
    }
}
