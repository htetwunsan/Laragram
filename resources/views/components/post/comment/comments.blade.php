@foreach($comments as $comment)
    <ul class="ul_comment list-outside list-none flex flex-col items-stretch mb-4"
        data-id="{{ $comment->id }}"
        data-username="{{ $comment->user->username }}">
        <li class="pr-7 pt-3"
            style="margin-top: -5px; margin-right: -2px;">
            <div class="flex justify-between">
                <!--comment user's image-->
                <div class="flex items-stretch">
                    <div class="flex">
                        <a class="block w-8 h-8" style="margin-right: 18px"
                           href="{{ route('user.show', ['user' => $comment->user->username]) }}">
                            <x-profile-image class="w-full h-full rounded-full" alt="">
                                {{ $comment->user->profile_image }}
                            </x-profile-image>
                        </a>
                    </div>

                    <div class="leading-18px">
                        <h3 class="inline-flex">
                            <a class="text-sm text-triple38 text-center font-semibold leading-18px break-words"
                               href="{{ route('user.show', ['user' => $comment->user->username]) }}">{{ $comment->user->username }}</a>
                        </h3>
                        <span class="text-sm text-triple38 leading-18px break-words"
                              style="word-break: break-word">{{ $comment->body }}</span>

                        <div class="div_info flex items-center mt-4 mb-1">
                            <time
                                class="text-xs text-triple142 leading-4 mr-3"
                                datetime="{{ $comment->created_at }}">{{ $comment->formatted_created_at }}</time>
                            <div @class(['div_like', 'hidden' => $comment->likes_count <= 0])>
                                <a class="block text-xs text-triple142 font-semibold leading-4 mr-3"
                                   href="/liked-by"
                                   tabindex="0">
                                    {{ $comment->likes_count }}
                                    @choice('like|likes', $comment->likes_count)
                                </a>
                            </div>
                            <button
                                class="btn_reply text-xs text-triple142 font-semibold leading-4 mr-3"
                                type="button">Reply
                            </button>
                            @if($comment->user->id == auth()->id())
                                <button class="btn_more_options hidden" type="button">
                                    <svg aria-label="More options" color="#262626" fill="#262626"
                                         height="16" role="img" viewBox="0 0 24 24" width="16">
                                        <circle cx="12" cy="12" r="1.5"></circle>
                                        <circle cx="6.5" cy="12" r="1.5"></circle>
                                        <circle cx="17.5" cy="12" r="1.5"></circle>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>

                </div>

                <div class="ml-4 mt-2">
                    <button
                        @class(['btn_unlike', 'hidden' => ! $comment->has_liked ]) type="button">
                        <svg aria-label="Unlike" class="text-error fill-current w-3 h-3"
                             role="img" viewBox="0 0 48 48">
                            <path
                                d="M34.6 3.1c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5s1.1-.2 1.6-.5c1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                            </path>
                        </svg>
                    </button>
                    <button
                        @class(['btn_like', 'hidden' => $comment->has_liked ]) type="button">
                        <svg aria-label="Like" class="text-triple38 fill-current w-3 h-3" role="img"
                             viewBox="0 0 48 48">
                            <path
                                d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z">

                            </path>
                        </svg>
                    </button>
                </div>
            </div>
        </li>
        @if($comment->children->count() > 0)
            <li>
                <ul class="ml-14 mt-4">
                    <li>
                        <div class="mb-4">
                            <button class="btn_view_replies flex items-center" type="button">
                                <span class="h-0 w-6 block border-b border-triple142 mr-4"></span>
                                <span
                                    class="text-xs text-triple142 text-center font-semibold leading-4">View replies ({{ $comment->children->count() }})</span>
                            </button>
                            <button class="btn_hide_replies flex items-center hidden" type="button">
                                <span class="h-0 w-6 block border-b border-triple142 mr-4"></span>
                                <span
                                    class="text-xs text-triple142 text-center font-semibold leading-4">Hide replies</span>
                            </button>
                        </div>
                    </li>
                    <li class="li_replies hidden">
                        @component('components.post.comment.comments', ['comments' => $comment->children])@endcomponent
                    </li>
                </ul>
            </li>
        @endif
    </ul>
@endforeach
