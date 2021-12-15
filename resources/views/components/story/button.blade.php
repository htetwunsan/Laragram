<div class="w-20 flex flex-row items-center justify-center px-1">
    <button class="relative w-16" type="button"
            onclick="window.location.href = '{{ route('user.story.index', ['user' => $user->username]) }}';">
        @if($user->stories_have_viewed_by_auth_user)
            <span
                class="flex items-center justify-center rounded-full p-0.5 mx-auto mt-1 mb-1.5">
                <span
                    class="block bg-white border border-triple219 rounded-full p-0.5"
                    role="link"
                    tabindex="-1">
                        <x-profile-image class="w-14 h-14 rounded-full" alt="" draggable="false">
                            {{ $user->profile_image }}
                        </x-profile-image>
                </span>
            </span>
        @else
            <span
                class="flex items-center justify-center bg-gradient-to-tr from-yellow-400 to-fuchsia-600 rounded-full p-0.5 mx-auto mt-1 mb-2">
                <span
                    class="block bg-white rounded-full p-0.5"
                    role="link"
                    tabindex="-1">
                    <x-profile-image class="w-14 h-14 rounded-full" alt="" draggable="false">
                        {{ $user->profile_image }}
                    </x-profile-image>
                </span>
            </span>
        @endif

        <span
            class="block text-xs text-triple38 text-center font-normal leading-4 overflow-ellipsis overflow-hidden whitespace-nowrap mx-auto">
                    {{ $user->username }}
        </span>
    </button>
</div>
