<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaveController extends Controller
{
    public function save(Post $post)
    {
        Auth::user()->savePost($post);

        return response()->json(['success' => 'You have saved the post.']);
    }

    public function unsave(Post $post)
    {
        Auth::user()->unsavePost($post);

        return response()->json(['success' => 'You have unsaved the post.']);
    }
}
