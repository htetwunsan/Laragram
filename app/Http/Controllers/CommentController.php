<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index(Post $post, Request $request)
    {
        $post->load('user');

        $comments = $post->commentsWithChildren()
            ->with('user')
            ->withCount('likes')
            ->latest()
            ->latest('id')
            ->cursorPaginate(10);

        Auth::user()->load('likedComments');

        $comments->each(function (Comment $comment) {
            $comment->addAuthRelatedAttributes(['has_liked']);
        });

        if ($request->expectsJson()) {
            $comments = collect(['html' => view('components.post.comment.comments', compact('comments'))->render()])
                ->merge($comments);
            return response()->json($comments);
        }

        return view('post.comment.index', compact(['post', 'comments']));
    }

    public function store(Post $post, Request $request)
    {
        $request->validate([
            'body' => ['required', 'string'],
            'comment_id' => ['nullable', 'numeric']
        ]);

        Auth::user()->commentPost($post, $request->body, Comment::find($request->comment_id));

        return back()->with('success', 'Your comment was posted.');
    }

    public function destroy(Post $post, Comment $comment)
    {
        $comment->deleteOrFail();

        return back()->with('success', 'Comment deleted.');
    }
}
