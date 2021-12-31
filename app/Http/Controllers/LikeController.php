<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function postLike(Post $post)
    {
        Auth::user()->likePost($post);

        return response()->json([
            'data' => [
                'likes_count' => $post->likes()->count()
            ],
            'success' => 'You have liked the post.'
        ]);
    }

    public function postUnlike(Post $post)
    {
        Auth::user()->unlikePost($post);

        return response()->json([
            'data' => [
                'likes_count' => $post->likes()->count()
            ],
            'success' => 'You have unliked the post.'
        ]);
    }

    public function commentLike(Comment $comment)
    {
        Auth::user()->likeComment($comment);

        return response()->json([
            'data' => [
                'likes_count' => $comment->likes()->count()
            ],
            'success' => 'You have liked the comment.'
        ]);
    }

    public function commentUnlike(Comment $comment)
    {
        Auth::user()->unlikeComment($comment);

        return response()->json([
            'data' => [
                'likes_count' => $comment->likes()->count()
            ],
            'success' => 'You have unliked the comment.'
        ]);
    }
}
