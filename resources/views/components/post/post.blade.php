<article class="article_post flex flex-col items-stretch mb-4" tabindex="-1" role="presentation"
         data-user_id="{{ $post->user->id }}">
    <!--post header-->
    <div class="flex flex-col items-stretch border border-triple239">
        <header class="flex flex-auto flex-row items-center pl-4 py-3.5 pr-1">
            <a class="block w-8 h-8 rounded-full"
               href="{{ route('user.show', ['user' => $post->user->username]) }}">
                <x-profile-image class="w-full h-full rounded-full"
                                 alt="{{ $post->user->username }}`s profile image.">
                    {{ $post->user->profile_image }}
                </x-profile-image>
            </a>
            <div class="div_name flex-grow flex items-center">
                <a class="block ml-4 text-sm text-triple38 font-semibold leading-18px overflow-ellipsis"
                   href="{{ route('user.show', ['user' => $post->user->username]) }}">
                    {{ $post->user->username }}
                </a>
                <button
                    @class([
                        'hidden' => $post->user->is_followed_by_auth_user,
                        'btn_follow',
                        'text-sm',
                        'text-fb_blue',
                        'text-center',
                        'font-semibold',
                        'leading-18px'
                    ])
                    type="button">
                    <span class="text-triple38">&nbsp&nbsp•&nbsp</span>
                    Follow
                </button>
            </div>
            <div class="flex items-center justify-center pr-2">
                <button class="btn_more_options" type="button">
                    <svg aria-label="More options" color="#262626" fill="#262626"
                         height="24" role="img" viewBox="0 0 24 24" width="24">
                        <circle cx="12" cy="12" r="1.5"></circle>
                        <circle cx="6.5" cy="12" r="1.5"></circle>
                        <circle cx="17.5" cy="12" r="1.5"></circle>
                    </svg>
                </button>
            </div>
        </header>
    </div>
    <!--post content-->
    <div class="flex flex-col items-stretch bg-black relative">
        <button
            class="btn_previous absolute left-0 top-1/2 transform -translate-y-1/2 px-2 py-4 z-10"
            type="button"
            tabindex="-1">
            <span class="block grey-previous-chevron"></span>``
        </button>
        <button
            class="btn_next absolute right-0 top-1/2 transform -translate-y-1/2 px-2 py-4 z-10"
            type="button"
            tabindex="-1">
            <span class="block grey-next-chevron"></span>
        </button>
        <div
            class="div_carousel flex flex-row items-center overflow-x-auto overflow-y-hidden no-scrollbar"
            style="scroll-behavior: smooth; overflow-scrolling: touch; scroll-snap-type: x mandatory; min-height: 300px">
            @foreach($post->images as $image)
                <div class="w-full flex-none" style="scroll-snap-align: start">
                    <x-post.image class="w-full h-full object-cover" alt="{{ $image->alternate_text }}">
                        {{ $image->image }}
                    </x-post.image>
                </div>
            @endforeach
        </div>
    </div>
    <!--post footer-->
    <div class="flex flex-col items-stretch px-4 pt-1.5 pb-2 mt-1">
        <!--actions-->
        <section class="flex flex-row justify-between items-center relative mb-1.5">
            <div
                class="div_indicator absolute top-0 left-1/2 transform -translate-x-1/2 z-10 flex flex-row -mt-1">
                @if($post->images->count() > 1)
                    @foreach($post->images as $_)
                        <span class="block text-triple38 text-3xl leading-none -mx-0.5">
                                            •
                        </span>
                    @endforeach
                @endif
            </div>
            <div class="flex flex-row justify-center items-center gap-4">
                <div class="flex items-center justify-center py-2">
                    <button
                        @class(['btn_like', 'hidden' => $post->has_liked_by_auth_user ]) type="button">
                        <svg aria-label="Like" class="text-triple38 fill-current w-6 h-6"
                             role="img" viewBox="0 0 48 48">
                            <path
                                d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                            </path>
                        </svg>
                    </button>
                    <button
                        @class(['btn_unlike', 'transform', 'duration-200', 'ease-in', 'hidden' => ! $post->has_liked_by_auth_user ]) type="button">
                        <svg aria-label="Unlike" class="text-error fill-current w-6 h-6"
                             role="img" viewBox="0 0 48 48">
                            <path
                                d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                            </path>
                        </svg>
                    </button>
                </div>
                <button class="btn_comment block py-2" type="button"
                        onclick="window.location.href = '{{ route('post.comment.index', ['post' => $post]) }}';">
                    <svg aria-label="Comment" class="text-triple38 fill-current w-6 h-6"
                         role="img" viewBox="0 0 48 48">
                        <path clip-rule="evenodd"
                              d="M47.5 46.1l-2.8-11c1.8-3.3 2.8-7.1 2.8-11.1C47.5 11 37 .5 24 .5S.5 11 .5 24 11 47.5 24 47.5c4 0 7.8-1 11.1-2.8l11 2.8c.8.2 1.6-.6 1.4-1.4zm-3-22.1c0 4-1 7-2.6 10-.2.4-.3.9-.2 1.4l2.1 8.4-8.3-2.1c-.5-.1-1-.1-1.4.2-1.8 1-5.2 2.6-10 2.6-11.4 0-20.6-9.2-20.6-20.5S12.7 3.5 24 3.5 44.5 12.7 44.5 24z"
                              fill-rule="evenodd">

                        </path>
                    </svg>
                </button>
                <button id="btn_share" class="block py-2" type="button">
                    <svg aria-label="Share Post" class="text-triple38 fill-current w-6 h-6"
                         role="img" viewBox="0 0 48 48">
                        <path
                            d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">

                        </path>
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-center py-2">
                <button @class(['btn_save', 'hidden' => $post->has_saved_by_auth_user ]) type="button">
                    <svg aria-label="Save" class="text-triple38 fill-current w-6 h-6"
                         role="img" viewBox="0 0 48 48">
                        <path
                            d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 29 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1zM24 26c.8 0 1.6.3 2.2.9l15.8 16V3H6v39.9l15.8-16c.6-.6 1.4-.9 2.2-.9z">

                        </path>
                    </svg>
                </button>
                <button @class(['btn_unsave', 'hidden' => ! $post->has_saved_by_auth_user]) type="button">
                    <svg aria-label="Remove" class="text-triple38 fill-current w-6 h-6"
                         role="img" viewBox="0 0 48 48">
                        <path
                            d="M43.5 48c-.4 0-.8-.2-1.1-.4L24 28.9 5.6 47.6c-.4.4-1.1.6-1.6.3-.6-.2-1-.8-1-1.4v-45C3 .7 3.7 0 4.5 0h39c.8 0 1.5.7 1.5 1.5v45c0 .6-.4 1.2-.9 1.4-.2.1-.4.1-.6.1z">

                        </path>
                    </svg>
                </button>
            </div>
        </section>
        <!--likes-->
        <div @class(['div_like', 'mb-1.5', 'hidden' => $post->likes->count() <= 0])>
            <a class="text-sm text-triple38 font-semibold leading-18px" href="/liked-by"
               tabindex="0">
                {{ $post->likes->count() }}
                @choice('like|likes', $post->likes->count())
            </a>
        </div>
        <!--caption-->
        <div class="mb-1 text-sm text-triple38 break-words leading-18px overflow-ellipsis">
            <a class="font-semibold"
               href="{{ route('user.show', ['user' => $post->user->username]) }}"
               tabindex="0">
                {{ $post->user->username }}
            </a>
            &nbsp
            <span @class(['span_full_caption', 'hidden' => Str::length($post->caption) > 80])>{{ $post->caption }}</span>
            <span
                @class(['span_short_caption', 'hidden' => Str::length($post->caption) <= 80])>{{ Str::substr($post->caption, 0, 80) }}...&nbsp
                <button class="btn_more_caption inline-block text-triple142" type="button">
                    more
                </button>
            </span>
        </div>
        <!--view comments-->
        <div @class(['div_comment', 'mb-1', 'hidden' => $post->comments->count() <= 0])>
            <a class="text-sm text-triple142 leading-18px"
               href="{{ route('post.comment.index', ['post' => $post]) }}" tabindex="0">
                View all
                {{ $post->comments->count() }}
                @choice('comment|comments', $post->comments->count())
            </a>
        </div>
        <!--some comments-->
        @foreach($post->comments->take(2) as $comment)
            <div class="mb-1 text-sm text-triple38 break-words leading-18px overflow-ellipsis">
                <a class="font-semibold"
                   href="{{ route('user.show', ['user' => $comment->user->username]) }}"
                   tabindex="0">
                    {{ $comment->user->username }}
                </a>
                &nbsp
                <span @class(['hidden' => Str::length($comment->body) > 80])>{{ $comment->body }}</span>
                <span @class(['hidden' => Str::length($comment->body) <= 80])>{{ Str::substr($comment->body, 0, 80) }}...&nbsp
                        </span>
            </div>
    @endforeach
    <!--time-->
        <div class="flex flex-col items-stretch mb-4 leading-18px">
            <time class="text-triple142 uppercase"
                  style="font-size: 10px; letter-spacing: 0.2px; line-height: 17px"
                  datetime="{{ $post->created_at }}"> {{ $post->created_at->diffForHumans() }}
            </time>
        </div>
    </div>
</article>
