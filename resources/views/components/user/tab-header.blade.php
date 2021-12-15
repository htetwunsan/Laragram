<div class="flex flex-row items-center justify-center border-t border-b border-solid border-triple219">
    <a class="h-11 flex-grow flex flex-row items-center justify-center"
       href="{{ route('user.show', ['user' => $user->username]) }}">
        @if($activeTab == 'posts')
            <svg aria-label="Posts" class="text-fb_blue fill-current" height="24" role="img"
                 viewBox="0 0 48 48" width="24">
                <path clip-rule="evenodd"
                      d="M45 1.5H3c-.8 0-1.5.7-1.5 1.5v42c0 .8.7 1.5 1.5 1.5h42c.8 0 1.5-.7 1.5-1.5V3c0-.8-.7-1.5-1.5-1.5zm-40.5 3h11v11h-11v-11zm0 14h11v11h-11v-11zm11 25h-11v-11h11v11zm14 0h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11zm14 28h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11z"
                      fill-rule="evenodd">
                </path>
            </svg>
        @else
            <svg aria-label="Posts" class="_8-yf5 " color="#8e8e8e" fill="#8e8e8e" height="24"
                 role="img"
                 viewBox="0 0 48 48" width="24">
                <path clip-rule="evenodd"
                      d="M45 1.5H3c-.8 0-1.5.7-1.5 1.5v42c0 .8.7 1.5 1.5 1.5h42c.8 0 1.5-.7 1.5-1.5V3c0-.8-.7-1.5-1.5-1.5zm-40.5 3h11v11h-11v-11zm0 14h11v11h-11v-11zm11 25h-11v-11h11v11zm14 0h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11zm14 28h-11v-11h11v11zm0-14h-11v-11h11v11zm0-14h-11v-11h11v11z"
                      fill-rule="evenodd">

                </path>
            </svg>
        @endisset
    </a>

    <a class="h-11 flex-grow flex flex-row items-center justify-center"
       href="{{ route('user.show.feeds', ['user' => $user->username]) }}">
        @if($activeTab == 'feeds')
            <span class="block feed-icon-active"></span>
        @else
            <span class="block feed-icon"></span>
        @endisset
    </a>
    {{--    <a class="h-11 flex-grow flex flex-row items-center justify-center"--}}
    {{--       href="#">--}}
    {{--        <svg aria-label="Posts" class="_8-yf5 " color="#8e8e8e" fill="#8e8e8e" height="24" role="img"--}}
    {{--             viewBox="0 0 24 24" width="24">--}}
    {{--            <path--}}
    {{--                d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.6 0 12 0zm0 22.5C6.2 22.5 1.5 17.8 1.5 12S6.2 1.5 12 1.5 22.5 6.2 22.5 12 17.8 22.5 12 22.5zm5-11.8l-6.8-3.9c-.5-.3-1-.3-1.5 0-.4.3-.7.7-.7 1.3v7.8c0 .5.3 1 .8 1.3.2.1.5.2.8.2s.5-.1.8-.2l6.8-3.9c.5-.3.8-.8.8-1.3s-.5-1-1-1.3zm-7.5 5.2V8.1l6.8 3.9-6.8 3.9z">--}}
    {{--            </path>--}}
    {{--        </svg>--}}
    {{--    </a>--}}
    <a class="h-11 flex-grow flex flex-row items-center justify-center" href="#">
        <svg aria-label="Tagged" class="_8-yf5 " color="#8e8e8e" fill="#8e8e8e" height="24" role="img"
             viewBox="0 0 48 48" width="24">
            <path
                d="M41.5 5.5H30.4c-.5 0-1-.2-1.4-.6l-4-4c-.6-.6-1.5-.6-2.1 0l-4 4c-.4.4-.9.6-1.4.6h-11c-3.3 0-6 2.7-6 6v30c0 3.3 2.7 6 6 6h35c3.3 0 6-2.7 6-6v-30c0-3.3-2.7-6-6-6zm-29.4 39c-.6 0-1.1-.6-1-1.2.7-3.2 3.5-5.6 6.8-5.6h12c3.4 0 6.2 2.4 6.8 5.6.1.6-.4 1.2-1 1.2H12.1zm32.4-3c0 1.7-1.3 3-3 3h-.6c-.5 0-.9-.4-1-.9-.6-5-4.8-8.9-9.9-8.9H18c-5.1 0-9.4 3.9-9.9 8.9-.1.5-.5.9-1 .9h-.6c-1.7 0-3-1.3-3-3v-30c0-1.7 1.3-3 3-3h11.1c1.3 0 2.6-.5 3.5-1.5L24 4.1 26.9 7c.9.9 2.2 1.5 3.5 1.5h11.1c1.7 0 3 1.3 3 3v30zM24 12.5c-5.3 0-9.6 4.3-9.6 9.6s4.3 9.6 9.6 9.6 9.6-4.3 9.6-9.6-4.3-9.6-9.6-9.6zm0 16.1c-3.6 0-6.6-2.9-6.6-6.6 0-3.6 2.9-6.6 6.6-6.6s6.6 2.9 6.6 6.6c0 3.6-3 6.6-6.6 6.6z"></path>
        </svg>
    </a>
</div>
