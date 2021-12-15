<nav class="flex flex-col items-stretch order-last px-5 z-50">
    <div class="flex flex-col items-stretch h-11">
        <div class="flex flex-col items-stretch">
            <div class="h-11"></div>
            <div
                class="max-w-screen-sm mx-auto h-11 fixed left-0 right-0 bottom-0 flex flex-col items-stretch border-t border-solid border-triple219 z-10 bg-white">
                <div class="flex flex-row items-stretch justify-around h-full">
                    <a class="flex-grow flex flex-row items-center justify-center" href="/">
                        @if($slot == 'home')
                            <svg aria-label="Home" class="text-triple38 fill-current h-6 w-6" role="img"
                                 viewBox="0 0 48 48">
                                <path
                                    d="M45.5 48H30.1c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2.1-4.6-4.6-4.6s-4.6 2.1-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.6-.6 2.1 0l21.5 21.5c.3.3.4.7.4 1.1v23.5c.1.8-.6 1.5-1.4 1.5z"></path>
                            </svg>
                        @else
                            <svg aria-label="Home" class="text-triple38 fill-current h-6 w-6"
                                 role="img" viewBox="0 0 48 48">
                                <path
                                    d="M45.3 48H30c-.8 0-1.5-.7-1.5-1.5V34.2c0-2.6-2-4.6-4.6-4.6s-4.6 2-4.6 4.6v12.3c0 .8-.7 1.5-1.5 1.5H2.5c-.8 0-1.5-.7-1.5-1.5V23c0-.4.2-.8.4-1.1L22.9.4c.6-.6 1.5-.6 2.1 0l21.5 21.5c.4.4.6 1.1.3 1.6 0 .1-.1.1-.1.2v22.8c.1.8-.6 1.5-1.4 1.5zm-13.8-3h12.3V23.4L24 3.6l-20 20V45h12.3V34.2c0-4.3 3.3-7.6 7.6-7.6s7.6 3.3 7.6 7.6V45z"></path>
                            </svg>
                        @endif
                    </a>

                    <a class="flex-grow flex flex-row items-center justify-center" href="{{ route('post.explore') }}">
                        @if($slot == 'search')
                            <svg aria-label="Search &amp; Explore" class="_8-yf5 " color="#262626" fill="#262626"
                                 height="24" role="img" viewBox="0 0 48 48" width="24">
                                <path
                                    d="M47.6 44L35.8 32.2C38.4 28.9 40 24.6 40 20 40 9 31 0 20 0S0 9 0 20s9 20 20 20c4.6 0 8.9-1.6 12.2-4.2L44 47.6c.6.6 1.5.6 2.1 0l1.4-1.4c.6-.6.6-1.6.1-2.2zM20 35c-8.3 0-15-6.7-15-15S11.7 5 20 5s15 6.7 15 15-6.7 15-15 15z">

                                </path>
                            </svg>
                        @else
                            <svg aria-label="Search &amp; Explore" class="text-triple38 fill-current h-6 w-6"
                                 role="img" viewBox="0 0 48 48">
                                <path
                                    d="M20 40C9 40 0 31 0 20S9 0 20 0s20 9 20 20-9 20-20 20zm0-37C10.6 3 3 10.6 3 20s7.6 17 17 17 17-7.6 17-17S29.4 3 20 3z"></path>
                                <path
                                    d="M46.6 48.1c-.4 0-.8-.1-1.1-.4L32 34.2c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l13.5 13.5c.6.6.6 1.5 0 2.1-.2.3-.6.4-1 .4z"></path>
                            </svg>
                        @endif
                    </a>

                    <a class="flex-grow flex flex-row items-center justify-center" href="{{ route('post.create') }}">
                        <svg aria-label="New Post" class="text-triple38 fill-current h-6 w-6"
                             role="img" viewBox="0 0 48 48">
                            <path
                                d="M31.8 48H16.2c-6.6 0-9.6-1.6-12.1-4C1.6 41.4 0 38.4 0 31.8V16.2C0 9.6 1.6 6.6 4 4.1 6.6 1.6 9.6 0 16.2 0h15.6c6.6 0 9.6 1.6 12.1 4C46.4 6.6 48 9.6 48 16.2v15.6c0 6.6-1.6 9.6-4 12.1-2.6 2.5-5.6 4.1-12.2 4.1zM16.2 3C10 3 7.8 4.6 6.1 6.2 4.6 7.8 3 10 3 16.2v15.6c0 6.2 1.6 8.4 3.2 10.1 1.6 1.6 3.8 3.1 10 3.1h15.6c6.2 0 8.4-1.6 10.1-3.2 1.6-1.6 3.1-3.8 3.1-10V16.2c0-6.2-1.6-8.4-3.2-10.1C40.2 4.6 38 3 31.8 3H16.2z"></path>
                            <path
                                d="M36.3 25.5H11.7c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5h24.6c.8 0 1.5.7 1.5 1.5s-.7 1.5-1.5 1.5z"></path>
                            <path
                                d="M24 37.8c-.8 0-1.5-.7-1.5-1.5V11.7c0-.8.7-1.5 1.5-1.5s1.5.7 1.5 1.5v24.6c0 .8-.7 1.5-1.5 1.5z"></path>
                        </svg>
                    </a>

                    <a class="flex-grow flex flex-row items-center justify-center"
                       href="{{ route('auth.activity') }}">
                        @if($slot == 'activity')
                            <svg aria-label="Activity" class="_8-yf5 " color="#262626" fill="#262626" height="24"
                                 role="img" viewBox="0 0 48 48" width="24">
                                <path
                                    d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                                </path>
                            </svg>
                        @else
                            <svg aria-label="Activity" class="text-triple38 fill-current h-6 w-6"
                                 role="img" viewBox="0 0 48 48">
                                <path
                                    d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path>
                            </svg>
                        @endif

                    </a>

                    <a class="flex-grow flex flex-row items-center justify-center relative"
                       href="{{ route('user.show', ['user' => auth()->user()->username]) }}">
                        @if($slot == 'profile')
                            <div class="absolute rounded-full border border-triple38"
                                 style="width: 30px; height: 30px"></div>
                        @endif
                        <span class="w-6 h-6 block rounded-full"
                              role="link" tabindex="-1">
                            <x-profile-image alt="{{ auth()->user()->username }}'s profile picture"
                                             class="w-6 h-6 rounded-full" draggable="false">
                                {{ auth()->user()->profile_image }}
                            </x-profile-image>
                                </span>
                    </a>

                </div>
            </div>
        </div>
    </div>
</nav>
