@foreach($followers as $follower)
    <div class="div_follower flex px-4 py-2 gap-x-3">
        <div class="flex items-center justify-center">
            <a class="rounded-full" style="width: 30px; height: 30px;"
               href="{{ route('user.show', ['user' => $follower->username]) }}">
                <x-profile-image class="w-full h-full rounded-full" alt="">
                    {{ $follower->profile_image }}
                </x-profile-image>
            </a>
        </div>

        <div class="flex-grow flex flex-col items-stretch">
            <div class="flex flex-row"
                 style="margin-top: -3px; margin-bottom: -4px;">
                <a class="block text-sm text-triple38 font-semibold leading-18px"
                   href="{{ route('user.show', ['user' => $follower->username]) }}">
                    {{ $follower->username }}
                </a>
                @if(! $follower->is_followed_by_auth_user)
                    <button class="btn_follow flex flex-row" type="button">
                        <span class="block text-sm text-triple38 leading-18px px-1">·</span>
                        <span class="block text-xs text-fb_blue text-center font-semibold leading-4">
                            Follow
                        </span>
                    </button>
                @endif
            </div>
            <div class="mt-2">
                <span class="block text-sm text-triple142 leading-18px"
                      style="margin-top: -3px; margin-bottom: -4px;">
                    {{ $follower->name }}
                </span>
            </div>
        </div>

        <div class="flex items-center justify-center ml-4">
            @component('components.button-remove-follower')@endcomponent
        </div>
    </div>
@endforeach
