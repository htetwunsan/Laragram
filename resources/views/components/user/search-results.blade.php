@foreach($users as $user)
    <li>
        <a href="{{ route('user.show', ['user' => $user->username]) }}" tabindex="0">
            <div class="flex items-center justify-center px-4 py-2">
                <div class="w-11 h-11 flex items-center justify-center mr-3">
                    <x-profile-image class="w-full h-full rounded-full" alt="">
                        {{ $user->profile_image }}
                    </x-profile-image>
                </div>

                <div class="flex-grow flex flex-col items-stretch">
                    <div class="leading-18px">
                        <span class="block text-sm text-triple38 font-semibold leading-18px whitespace-nowrap -mb-1"
                              style="margin-top: -3px;">
                            {{ $user->username }}
                        </span>
                    </div>

                    <div class="leading-18px mt-2">
                        <span
                            class="block text-sm text-triple142 leading-18px overflow-ellipsis whitespace-nowrap -mb-1"
                            style="margin-top: -3px">
                            {{ $user->name }}
                        </span>
                    </div>
                </div>
            </div>
        </a>
    </li>
@endforeach
