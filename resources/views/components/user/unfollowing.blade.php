<div class="flex flex-none flex-row gap-x-2" style="height: 30px;">
    <div class="flex-grow flex flex-col items-stretch">
        <form class="h-full flex flex-col items-stretch" action="{{ route('user.follow', ['user' => $user]) }}"
              method="POST">@csrf
            <button
                class="bg-fb_blue h-full text-sm text-center text-white font-semibold px-3 border rounded"
                type="submit"
                style="line-height: 26px;">
                @if($user->is_following_auth_user)Follow Back @else Follow @endif
            </button>
        </form>
    </div>
    <div class="flex flex-col items-stretch">
        <button class="bg-fb_blue h-full px-3 border rounded">
            <span class="inline-block transform rotate-180">
                <svg
                    aria-label="Down Chevron Icon" class="_8-yf5 "
                    fill="#ffffff" height="12" role="img" viewBox="0 0 48 48" width="12">
                    <path
                        d="M40 33.5c-.4 0-.8-.1-1.1-.4L24 18.1l-14.9 15c-.6.6-1.5.6-2.1 0s-.6-1.5 0-2.1l16-16c.6-.6 1.5-.6 2.1 0l16 16c.6.6.6 1.5 0 2.1-.3.3-.7.4-1.1.4z">

                    </path>
                </svg>
            </span>
        </button>
    </div>
</div>
