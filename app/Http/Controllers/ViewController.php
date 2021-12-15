<?php

namespace App\Http\Controllers;

use App\Models\Story;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function storyView(Story $story)
    {
        auth()->user()->viewStory($story);
        return response()->json([
            'data' => [
                'views_count' => $story->views()->count()
            ],
            'success' => 'You have viewed the story.'
        ]);
    }
}
