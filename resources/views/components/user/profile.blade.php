<header class="flex flex-row items-stretch mx-4 mt-4 mb-6">
    <div class="flex-shrink-0 flex flex-col items-stretch mr-7">
        <div style="width: 77px; height: 77px;">
            <a href="{{ route('user.story.index', ['user' => $user->username]) }}">
                @if($user->stories_have_viewed_by_auth_user)
                    <span
                        class="flex items-center justify-center rounded-full p-0.5 mx-auto mt-1 mb-1.5">
                        <span
                            class="block bg-white border border-triple219 rounded-full p-0.5"
                            role="link"
                            tabindex="-1">
                            <x-profile-image class="w-full h-full rounded-full"
                                             title="{{ $user->username }}'s profile photo"
                                             alt="{{ $user->username }}'s profile photo">
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
                            <x-profile-image class="w-full h-full rounded-full"
                                             title="{{ $user->username }}'s profile photo"
                                             alt="{{ $user->username }}'s profile photo">
                                {{ $user->profile_image }}
                            </x-profile-image>
                        </span>
                    </span>
                @endif
            </a>
        </div>
    </div>
    <section class="flex flex-col items-stretch overflow-hidden">
        <div class="flex flex-col items-stretch mb-3">
            <div class="flex items-center justify-center">
                <h2 class="text-triple38 font-light leading-8 -mt-1.5 -mb-1.5 overflow-hidden overflow-ellipsis"
                    style="font-size: 1.75rem">{{ $user->username }}</h2>

                <div class="flex items-center justify-center w-12"
                     style="margin-left: 5px">
                    <button id="btn_more_options" class="p-2" type="button">
                        <svg aria-label="Options" class="_8-yf5 " color="#262626" fill="#262626"
                             height="32" role="img" viewBox="0 0 24 24" width="32">
                            <circle cx="12" cy="12" r="1.5"></circle>
                            <circle cx="6.5" cy="12" r="1.5"></circle>
                            <circle cx="17.5" cy="12" r="1.5"></circle>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!--is following-->
        <div class="flex flex-col items-stretch" style="max-width: 250px;">
            @if($user->is_blocked_by_auth_user)
                @component('components.user.blocking', ['user' => $user])@endcomponent
            @elseif($user->is_followed_by_auth_user)
                @component('components.user.following', ['user' => $user])@endcomponent
            @else
                @component('components.user.unfollowing', ['user' => $user])@endcomponent
            @endif
            @component('components.user.more-options-dialog', ['user' => $user])@endcomponent
            @once
                @push('scripts')
                    <script>
                        var appData = {
                            user: @json($user)
                        };
                        $(document).ready(function () {
                            const $btnMoreOptions = $('#btn_more_options');

                            $btnMoreOptions.click(function () {
                                window.toggleDialog($('#div_more_options_dialog'), function ($dialog) {
                                    $dialog.data('user', appData.user);
                                });
                            });
                        });
                    </script>
                @endpush
            @endonce
        </div>
    </section>
</header>

<div class="px-4 pb-5">
    <h1 class="text-sm text-triple38 font-semibold leading-5 pb-px">{{ $user->name }}</h1>
    @if (! is_null($user->bio))
        <span class="block text-sm text-triple38 font-normal leading-5">{{ $user->bio }}</span>
    @endif
</div>

<ul class="flex flex-row justify-around list-none list-outside py-3 border-t border-solid border-triple219">
    <li class="text-sm text-triple142 text-center font-normal leading-18px w-1/3">
        <span class="block text-triple38 font-semibold">{{ $user->posts_count }}</span>
        @choice('post|posts', $user->posts_count)
    </li>

    <li class="text-sm text-triple142 text-center font-normal leading-18px w-1/3">
        @if($user->followers_count > 0)
            <a href="{{ route('user.followers', ['user' => $user->username]) }}">
                <span class="block text-triple38 font-semibold">{{ $user->followers_count }}</span>
                @choice('follower|followers', $user->followers_count)
            </a>
        @else
            <span class="block text-triple38 font-semibold">{{ $user->followers_count }}</span>
            @choice('follower|followers', $user->followers_count)
        @endif

    </li>

    <li class="text-sm text-triple142 text-center font-normal leading-18px w-1/3">
        @if($user->followings_count > 0)
            <a href="{{ route('user.followings', ['user' => $user->username]) }}">
                <span class="block text-triple38 font-semibold">{{ $user->followings_count }}</span>
                following
            </a>
        @else
            <span class="block text-triple38 font-semibold">{{ $user->followings_count }}</span>
            following
        @endif
    </li>
</ul>
