<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
use App\Notifications\CommentNotification;
use App\Notifications\FollowNotification;
use App\Notifications\LikeNotification;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $notifications = Auth::user()->notifications()->simplePaginate(10);

        $notifications->each(function ($notification) {
            switch ($notification->type) {
                case FollowNotification::class:
                    $notification->setAttribute(
                        'following',
                        Following::with(['following', 'follower'])->find($notification->data['id'])
                    );
                    $notification->setAttribute(
                        'is_following',
                        Auth::user()->isFollowing($notification->following->follower)
                    );
                    break;
                case LikeNotification::class:
                    $notification->setAttribute(
                        'like',
                        Like::with(['user', 'likeable'])->find($notification->data['id'])
                    );
                    break;
                case CommentNotification::class:
                    $notification->setAttribute(
                        'comment',
                        Comment::with(['user', 'post'])->find($notification->data['id'])
                    );
                    break;
                default:
                    break;
            }
            $notification->setAttribute(
                'formatted_created_at',
                $notification->created_at->diffForHumans(null, CarbonInterface::DIFF_ABSOLUTE, true)
            );
        });

        if ($request->expectsJson()) {
            $notifications = collect([
                'html' => view('components.activity.activities', ['notifications' => $notifications])->render()
            ])->merge($notifications);
            return response()->json($notifications);
        }

        return view('activity.index', compact('notifications'));
    }
}
