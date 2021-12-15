<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar">
        <x-app-top-navigation>
            <x-slot name="left">
                <div class="flex flex-row items-stretch justify-start w-8">
                    <x-back-button></x-back-button>
                </div>
            </x-slot>

            <a href="/" tabindex="0">
                <h1 class="text-base text-triple38 text-center font-semibold">Comments</h1>
            </a>

            <x-slot name="right">
                <div class="flex flex-row items-stretch justify-end w-8">
                    <a aria-label="Direct messaging"
                       tabindex="0">
                        <svg aria-label="Direct" class="text-triple38 fill-current w-6 h-6" role="img"
                             viewBox="0 0 48 48">
                            <path
                                d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">
                            </path>
                        </svg>
                    </a>
                </div>
            </x-slot>
        </x-app-top-navigation>

        <x-app-bottom-navigation>home</x-app-bottom-navigation>

        <main class="bg-triple250 flex-grow flex flex-col items-stretch" role="main">
            <!--add comment or reply-->
            <div class="bg-triple239 flex items-center justify-center border-t border-b border-triple219 py-2">
                <span class="block w-8 h-8 rounded-full mx-4" role="link" tabindex="-1">
                    <x-profile-image class="w-full h-full rounded-full"
                                     alt="{{ auth()->user()->username }}' profile image">{{ auth()->user()->profile_image }}</x-profile-image>
                </span>
                <form id="form_post_comment"
                      class="bg-white flex-grow flex border border-triple219 px-4 py-3 mr-4"
                      style="border-radius: 30px"
                      action="{{ route('post.comment.index', ['post' => $post]) }}"
                      method="POST">@csrf
                    <textarea
                        class="flex-grow text-sm text-triple38 break-words leading-18px resize-none border-none focus:outline-none focus:ring-0 p-0"
                        style="height: 18px; max-height: 80px;"
                        name="body"
                        placeholder="Add a comment..."
                        autocomplete="off"
                        required
                        rows="1"></textarea>
                    <input type="hidden" name="comment_id" value=""/>
                    <button class="text-sm text-fb_blue text-opacity-30 text-center font-semibold leading-18px"
                            disabled
                            type="submit">Post
                    </button>
                </form>
            </div>

            <div id="div_replying_to"
                 class="bg-triple239 flex items-center justify-between px-4 py-3 border-b border-triple219 hidden">
                <span class="block text-sm text-triple142 leading-18px">Replying to username</span>
                <button class="text-sm text-triple38 font-semibold leading-18px" type="button">
                    X
                </button>
            </div>

            <div class="flex flex-col items-stretch p-4">
                <!--captions -->
                <div class="pr-7 pt-3 pb-4 border-b border-triple219 mb-4"
                     style="margin-top: -5px; margin-right: -2px;">
                    <div class="flex justify-between">
                        <!--comment user's image-->
                        <div class="flex items-stretch">
                            <div class="flex">
                                <a class="block w-8 h-8" style="margin-right: 18px"
                                   href="{{ route('user.show', ['user' => $post->user->username]) }}">
                                    <x-profile-image class="w-full h-full rounded-full" alt="">
                                        {{ $post->user->profile_image }}
                                    </x-profile-image>
                                </a>
                            </div>

                            <div class="leading-18px">
                                <h3 class="inline-flex">
                                    <a class="text-sm text-triple38 text-center font-semibold leading-18px"
                                       href="{{ route('user.show', ['user' => $post->user->username]) }}">{{ $post->user->username }}</a>
                                </h3>
                                <span class="text-sm text-triple38 leading-18px break-words">{{ $post->caption }}</span>

                                <div class="div_info flex items-center mt-4 mb-1">
                                    <time
                                        class="text-xs text-triple142 leading-4 mr-3"
                                        datetime="{{ $post->created_at }}">{{ $post->formatted_created_at }}</time>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--comments-->
                <div id="comments_container">
                    @component('components.post.comment.comments', ['comments' => $comments])@endcomponent
                </div>
            </div>
        </main>

        <x-app-dialog id="div_self_comment_options">
            <button class="text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
                    type="button"
                    tabindex="0">Report
            </button>
            <form class="flex items-center justify-center h-12 py-1 px-2 border-b border-triple219"
                  method="POST">@csrf @method('DELETE')
                <button class="text-sm text-error text-center font-bold"
                        type="submit"
                        tabindex="0">Delete
                </button>
            </form>
            <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
                    type="button"
                    tabindex="0" onclick="$('#div_self_comment_options').addClass('hidden')">
                Cancel
            </button>
        </x-app-dialog>
    </section>
    @once
        @push('scripts')
            <script>
                var appData = {
                    post: @json($post)
                };
                $(document).ready(function () {
                    const scrollOffset = 1200;
                    let isFetching = false;
                    let nextPageUrl = "{{ $comments->nextPageUrl() }}";

                    function loadMoreComments() {
                        if (!nextPageUrl) return;
                        if (isFetching) return;
                        isFetching = true;
                        axios.get(nextPageUrl).then(response => {
                            $('#comments_container').append(response.data.html);
                            nextPageUrl = response.data.next_page_url;
                            initCommentEvents();
                        }).finally(function () {
                            isFetching = false;
                        })
                    }

                    $(window).scroll(function () {
                        if ($(this).scrollTop() + $(this).height() + scrollOffset >= $(document).height()) {
                            loadMoreComments();
                        }
                    });


                    const $formPostComment = $('#form_post_comment');
                    const $textAreaBody = $formPostComment.find('textarea[name=body]');
                    const $inputCommentId = $formPostComment.find('input[name=comment_id]');
                    const $btnSubmit = $formPostComment.find('button[type=submit]');

                    const $divReplyingTo = $('#div_replying_to');
                    const $spanReplying = $divReplyingTo.find('span');
                    const $btnCancelReplying = $divReplyingTo.find('button[type=button]');

                    function toggleSubmit(valid) {
                        if (valid) {
                            $btnSubmit.attr('disabled', false);
                            $btnSubmit.removeClass('text-opacity-30');
                        } else {
                            $btnSubmit.attr('disabled', true);
                            $btnSubmit.addClass('text-opacity-30');
                        }
                    }

                    function validateBody() {
                        return $textAreaBody.val().length > 0;
                    }

                    function validate() {
                        toggleSubmit(validateBody());
                    }

                    $textAreaBody.on('input', function () {
                        this.style.height = 'auto';
                        this.style.height = (this.scrollHeight) + 'px';
                        validate();
                    });

                    $btnCancelReplying.click(function () {
                        $inputCommentId.val("");
                        $textAreaBody.val("");
                        $divReplyingTo.addClass('hidden');
                    });

                    function initCommentEvents() {
                        const comments = $('.ul_comment');
                        comments.each(function () {
                            const commentId = $(this).data('id');
                            const username = $(this).data('username');

                            const $btnLike = $(this).find('.btn_like');
                            const $btnUnlike = $(this).find('.btn_unlike');
                            const $divLike = $(this).find('.div_like');

                            const $btnReply = $(this).find('.btn_reply');

                            const $btnViewReplies = $(this).find('.btn_view_replies').first();
                            const $btnHideReplies = $(this).find('.btn_hide_replies').first();
                            const $liReplies = $(this).find('.li_replies').first();

                            let likeInProgress = false;

                            function toggleDivLike(likesCount) {
                                if (likesCount > 0) {
                                    $divLike.find('a').text(`${likesCount} ${likesCount === 1 ? 'like' : 'likes'}`);
                                    $divLike.removeClass('hidden');
                                } else {
                                    $divLike.addClass('hidden');
                                }
                            }

                            function handleLike() {
                                if (likeInProgress) return;
                                likeInProgress = true;
                                axios.post(`/api/comments/${commentId}/like`).then(response => {
                                    const likesCount = response.data.data.likes_count;
                                    toggleDivLike(likesCount);
                                    $btnLike.addClass('hidden');
                                    $btnUnlike.removeClass('hidden');
                                }).finally(function () {
                                    likeInProgress = false;
                                });
                            }

                            function handleUnlike() {
                                if (likeInProgress) return;
                                likeInProgress = true;
                                axios.post(`/api/comments/${commentId}/unlike`).then(response => {
                                    const likesCount = response.data.data.likes_count;
                                    toggleDivLike(likesCount);
                                    $btnUnlike.addClass('hidden');
                                    $btnLike.removeClass('hidden');
                                }).finally(function () {
                                    likeInProgress = false;
                                });
                            }

                            $(this).find('li').first().off('dblclick').dblclick(handleLike);

                            $btnLike.off('click').click(handleLike);

                            $btnUnlike.off('click').click(handleUnlike);

                            $btnReply.off('click').click(function () {
                                $spanReplying.text('Replying to ' + username);
                                $inputCommentId.val(commentId);
                                $textAreaBody.val('@' + username + ' ');
                                $textAreaBody.focus();
                                $divReplyingTo.removeClass('hidden');
                            });

                            $btnViewReplies.off('click').click(function () {
                                $(this).addClass('hidden');

                                $btnHideReplies.removeClass('hidden');

                                $liReplies.removeClass('hidden');
                            });

                            $btnHideReplies.off('click').click(function () {
                                $(this).addClass('hidden');

                                $btnViewReplies.removeClass('hidden');

                                $liReplies.addClass('hidden');
                            });


                            const $divSelfCommentOptions = $('#div_self_comment_options');
                            const $divInfo = $(this).find('.div_info').first();
                            const $btnMoreOptions = $divInfo.find('.btn_more_options');

                            $divInfo.off('mouseenter', 'mouseleave').hover(function () {
                                $btnMoreOptions.removeClass('hidden');
                            }, function () {
                                $btnMoreOptions.addClass('hidden');
                            });


                            $btnMoreOptions.off('click').click(function () {
                                const postId = appData.post.id;
                                $divSelfCommentOptions.find('form').attr('action', `/posts/${postId}/comments/${commentId}`);
                                $divSelfCommentOptions.removeClass('hidden');
                            });
                        });
                    }

                    initCommentEvents();
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
