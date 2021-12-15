<?php

namespace App\Http\Controllers;

use App\Models\Story;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class StoryController extends Controller
{
    public function index(User $user)
    {
        $stories = $user->activeStories()->oldest()->get();
        return view('story.index', compact('stories', 'user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image']
        ]);

        auth()->user()->createStory($request->file('image'));

        return redirect()->route('post.index')->with('success', 'Your photo was added.');
    }

    public function destroy(Story $story)
    {
        $story->deleteOrFail();

        return back()->with('success', 'Story deleted.');
    }
}
