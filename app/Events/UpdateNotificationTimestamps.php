<?php

namespace App\Events;

use App\Models\Comment;
use App\Models\Following;
use App\Models\Like;
use App\Notifications\CommentNotification;
use App\Notifications\FollowNotification;
use App\Notifications\LikeNotification;
use DB;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Events\NotificationSent;
use Illuminate\Queue\SerializesModels;
use Log;

class UpdateNotificationTimestamps
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(NotificationSent $event)
    {
        $notification = DB::table('notifications')->find($event->notification->id);
        $data = json_decode($notification->data);
        switch ($notification->type) {
            case LikeNotification::class:
                $like = Like::find($data->id);
                DB::table('notifications')->where('id', $event->notification->id)
                    ->update([
                        'created_at' => $like->created_at,
                        'updated_at' => $like->updated_at
                    ]);
                break;
            case FollowNotification::class:
                $following = Following::find($data->id);
                DB::table('notifications')->where('id', $event->notification->id)
                    ->update([
                        'created_at' => $following->created_at,
                        'updated_at' => $following->updated_at
                    ]);
                break;
            case CommentNotification::class:
                $comment = Comment::find($data->id);
                DB::table('notifications')->where('id', $event->notification->id)
                    ->update([
                        'created_at' => $comment->created_at,
                        'updated_at' => $comment->updated_at
                    ]);
                break;
            default:
                break;
        }
        return true;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
