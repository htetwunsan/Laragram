<div class="div_activity flex flex-row items-start justify-center gap-x-3 px-4 py-3 overflow-hidden">
    <div class="w-11 h-11 flex-none flex items-center justify-center rounded-full">
        <a href="{{ route('user.show', ['user' => $notification->like->user->username]) }}">
            <x-profile-image class="w-full h-full rounded-full" alt="profile image">
                {{ $notification->like->user->profile_image }}
            </x-profile-image>
        </a>
    </div>

    <div class="flex-grow flex items-center">
    <span
        class="text-sm text-triple38 leading-18px overflow-ellipsis"
        style="word-break: break-word">
        <a class="font-semibold whitespace-nowrap break-words"
           href="{{ route('user.show', ['user' => $notification->like->user->username]) }}"
           tabindex="0">
        {{ $notification->like->user->username }}
        </a>
        <span class="whitespace-normal overflow-ellipsis break-words">
            {{ $notification->data['message'] }}
        </span>
        <time class="inline text-triple142 break-words" style="margin-left: 5px;"
              datetime="{{ $notification->created_at }}">
            {{ $notification->formatted_created_at }}
        </time>
    </span>
    </div>

    <div class="w-10 h-11 flex flex-none self-center items-center justify-center">
        @if($notification->like->likeable_type === \App\Models\Post::class)
            <a href="{{ route('post.show', ['post' => $notification->like->likeable->id ]) }}">
                <x-post.image class="w-full h-full object-cover"
                              alt="{{ $notification->like->likeable->image->alternate_text }}">
                    {{ $notification->like->likeable->image->image }}
                </x-post.image>
            </a>
        @endif
        @if($notification->like->likeable_type === \App\Models\Comment::class)
            <a href="{{ route('post.show', ['post' => $notification->like->likeable->post->id ]) }}">
                <x-post.image class="w-full h-full object-cover"
                              alt="{{ $notification->like->likeable->post->image->alternate_text }}">
                    {{ $notification->like->likeable->post->image->image }}
                </x-post.image>
            </a>
        @endif
    </div>
</div>
