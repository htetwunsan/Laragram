<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Save;
use Illuminate\Http\Request;

class SaveController extends Controller
{
    public function save(Post $post)
    {
        auth()->user()->savePost($post);

        return response()->json(['success' => 'You have saved the post.']);
    }

    public function unsave(Post $post)
    {
        auth()->user()->unsavePost($post);

        return response()->json(['success' => 'You have unsaved the post.']);
    }
}
