@foreach($followings as $following)
    <div class="div_following flex px-4 py-2">
        <div class="flex items-center justify-center m-1 mr-3 pr-px">
            <a class="rounded-full" style="width: 30px; height: 30px;"
               href="{{ route('user.show', ['user' => $following->username]) }}">
                <x-profile-image class="w-full h-full rounded-full" alt="">
                    {{ $following->profile_image }}
                </x-profile-image>
            </a>
        </div>

        <div class="flex-grow flex flex-col items-stretch">
            <a class="block text-sm text-triple38 font-semibold leading-18px"
               href="{{ route('user.show', ['user' => $following->username]) }}">
                {{ $following->username }}
            </a>
            <span class="block text-sm text-triple142 leading-18px">
                                    {{ $following->name }}
                            </span>
        </div>

        <div class="flex items-center justify-center ml-4">
            @component('components.button-toggle-follow', ['is_following' => $following->is_followed_by_auth_user])
            @endcomponent
        </div>
    </div>
@endforeach
