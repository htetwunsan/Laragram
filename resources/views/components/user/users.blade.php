@foreach($users as $user)
    <div class="div_user flex px-4 py-2">
        <div class="flex items-center justify-center mr-3">
            <a class="w-11 h-11 rounded-full" href="{{ route('user.show', ['user' => $user->username]) }}">
                <x-profile-image class="w-full h-full rounded-full" alt="">
                    {{ $user->profile_image }}
                </x-profile-image>
            </a>
        </div>

        <div class="flex-grow flex flex-col items-stretch">
            <div>
                <a class="block text-sm text-triple38 font-semibold leading-18px"
                   style="margin-top: -3px; margin-bottom: -4px;"
                   href="{{ route('user.show', ['user' => $user->username]) }}">
                    {{ $user->username }}
                </a>
            </div>

            <div class="mt-2">
                <span class="block text-sm text-triple142 leading-18px"
                      style="margin-top: -3px; margin-bottom: -4px;">
                    {{ $user->name }}
                </span>
            </div>

            <div class="mt-2">
                <span class="block text-xs text-triple142 leading-4"
                      style="margin-top: -2px; margin-bottom: -3px;">
                     <!--query related description-->
                    New to {{ config('app.name', 'Laragram') }}
                </span>
            </div>
        </div>

        <div class="flex items-center justify-center ml-2">
            <button
                class="btn_follow bg-fb_blue border border-transparent rounded text-sm text-white text-center font-semibold leading-18px relative"
                style="padding: 5px 9px;"
                type="button">
                <span>Follow</span>
                <svg class="absolute inset-0 m-auto w-4 h-4 text-white animate-spin hidden"
                     xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>
            <button
                class="btn_unfollow bg-transparent border border-triple219 rounded text-sm text-triple38 text-center font-semibold leading-18px hidden"
                style="padding: 5px 9px;"
                type="button">
                Following
            </button>
        </div>
    </div>
@endforeach
