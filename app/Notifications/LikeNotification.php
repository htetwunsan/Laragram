<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use InvalidArgumentException;

class LikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $like;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Like $like)
    {
        $this->like = $like;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        if ($this->like->likeable_type === Post::class) {
            return [
                'id' => $this->like->id,
                'message' => ' liked your photo.'
            ];
        }

        if ($this->like->likeable_type === Comment::class) {
            return [
                'id' => $this->like->id,
                'message' => ' liked your comment: ' . $this->like->likeable->body
            ];
        }

        throw new InvalidArgumentException('This likeable type is currently not supported');
    }
}
