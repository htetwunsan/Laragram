<div class="div_activity flex flex-row items-start justify-center gap-x-3 px-4 py-3 overflow-hidden">
    <div class="w-11 h-11 flex-none flex items-center justify-center rounded-full">
        <a href="{{ route('user.show', ['user' => $notification->following->follower->username]) }}">
            <x-profile-image class="w-full h-full rounded-full" alt="profile image">
                {{ $notification->following->follower->profile_image }}
            </x-profile-image>
        </a>
    </div>

    <div class="flex-grow flex items-center">
        <span
            class="text-sm text-triple38 leading-18px overflow-ellipsis"
            style="word-break: break-word">
            <a class="font-semibold whitespace-nowrap break-words"
               href="{{ route('user.show', ['user' => $notification->following->follower->username]) }}"
               tabindex="0">
            {{ $notification->following->follower->username }}
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

    <div class="flex flex-none items-center justify-center">
        @component('components.button-toggle-follow', ['is_following' => $notification->is_following])@endcomponent
    </div>
</div>
