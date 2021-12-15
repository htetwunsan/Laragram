@foreach($notifications as $notification)
    @switch($notification->type)
        @case(\App\Notifications\FollowNotification::class)
        @component('components.activity.follow', ['notification' => $notification])@endcomponent
        @break

        @case(\App\Notifications\LikeNotification::class)
        @component('components.activity.like', ['notification' => $notification])@endcomponent
        @break

        @case(\App\Notifications\CommentNotification::class)
        @component('components.activity.comment', ['notification' => $notification])@endcomponent
        @default
    @endswitch
@endforeach
