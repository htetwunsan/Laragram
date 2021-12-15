<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function postLike(Post $post)
    {
        auth()->user()->likePost($post);

        return response()->json([
            'data' => [
                'likes_count' => $post->likes()->count()
            ],
            'success' => 'You have liked the post.'
        ]);
    }

    public function postUnlike(Post $post)
    {
        auth()->user()->unlikePost($post);

        return response()->json([
            'data' => [
                'likes_count' => $post->likes()->count()
            ],
            'success' => 'You have unliked the post.'
        ]);
    }

    public function commentLike(Comment $comment)
    {
        auth()->user()->likeComment($comment);

        return response()->json([
            'data' => [
                'likes_count' => $comment->likes()->count()
            ],
            'success' => 'You have liked the comment.'
        ]);
    }

    public function commentUnlike(Comment $comment)
    {
        auth()->user()->unlikeComment($comment);

        return response()->json([
            'data' => [
                'likes_count' => $comment->likes()->count()
            ],
            'success' => 'You have unliked the comment.'
        ]);
    }
}
