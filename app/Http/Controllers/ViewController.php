<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function storyView(Story $story)
    {
        Auth::user()->viewStory($story);
        return response()->json([
            'data' => [
                'views_count' => $story->views()->count()
            ],
            'success' => 'You have viewed the story.'
        ]);
    }
}
