<div id="posts_container"
     class="flex-grow flex flex-col items-stretch">
    @component('components.post.posts', ['posts' => $posts])@endcomponent
    @component('components.post.more-options-dialog')@endcomponent

    @once
        @push('scripts')
            <script>
                var appPosts = @json($posts->items());
                var appAuthUser = @json(auth()->user());
                $(document).ready(function () {
                    const scrollOffset = 1200;
                    let isFetching = false;
                    let nextPageUrl = "{{ $posts->nextPageUrl() }}";

                    function initPostEvents() {
                        const $articles = $('.article_post');

                        $articles.each(function (index) {
                            const $carousel = $(this).find('.div_carousel');
                            const $indicators = $(this).find('.div_indicator span');
                            const $btnNext = $(this).find('.btn_next');
                            const $btnPrevious = $(this).find('.btn_previous');

                            const $divLike = $(this).find('.div_like');
                            const $divComment = $(this).find('.div_comment');
                            const $btnLike = $(this).find('.btn_like');
                            const $btnUnlike = $(this).find('.btn_unlike');
                            const $btnSave = $(this).find('.btn_save');
                            const $btnUnsave = $(this).find('.btn_unsave');

                            const $btnMoreCaption = $(this).find('.btn_more_caption');
                            const $spanShortCaption = $(this).find('.span_short_caption');
                            const $spanFullCaption = $(this).find('.span_full_caption');

                            const $btnMoreOptions = $(this).find('.btn_more_options');

                            const $btnFollow = $(this).find('.btn_follow');


                            $btnNext.off('click').click(function () {
                                $carousel.scrollLeft($carousel.scrollLeft() + $carousel.width());
                            });

                            $btnPrevious.off('click').click(function () {
                                $carousel.scrollLeft($carousel.scrollLeft() - $carousel.width());
                            });

                            function toggleNext(valid) {
                                valid ? $btnNext.removeClass('hidden') : $btnNext.addClass('hidden');
                            }

                            function togglePrevious(valid) {
                                valid ? $btnPrevious.removeClass('hidden') : $btnPrevious.addClass('hidden');
                            }

                            function toggleIndicators() {
                                const activeIndex = Math.round($carousel.scrollLeft() / $carousel.width());
                                $indicators.each(function (index) {
                                    if (index === activeIndex) {
                                        $(this).removeClass('text-triple38');
                                        $(this).addClass('text-fb_blue');
                                    } else {
                                        $(this).addClass('text-triple38');
                                        $(this).removeClass('text-fb_blue');
                                    }
                                })
                            }

                            $carousel.off('scroll').scroll(function () {
                                togglePrevious($(this).scrollLeft() >= $carousel.width() / 2);
                                toggleNext($(this)[0].scrollWidth - ($(this)[0].scrollLeft + $(this)[0].clientWidth) >= $carousel.width() / 2);
                                toggleIndicators();
                            });

                            $carousel.trigger('scroll');

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

                                const postId = appPosts[index].id;

                                $btnLike.addClass('hidden');

                                $btnUnlike.removeClass('hidden');
                                $btnUnlike.addClass('scale-125');
                                setTimeout(function () {
                                    $btnUnlike.removeClass('scale-125');
                                    $btnUnlike.addClass('scale-100')
                                }, 200);
                                axios.post(`/api/posts/${postId}/like`)
                                    .then(response => {
                                        const likesCount = response.data.data.likes_count;
                                        toggleDivLike(likesCount);
                                    })
                                    .catch(error => {
                                        $btnLike.removeClass('hidden');
                                        $btnUnlike.addClass('hidden');
                                    })
                                    .finally(function () {
                                        likeInProgress = false;
                                    });
                            }

                            function handleUnlike() {
                                if (likeInProgress) return;
                                likeInProgress = true;

                                const postId = appPosts[index].id;

                                $btnUnlike.addClass('hidden');

                                $btnLike.removeClass('hidden');
                                axios.post(`/api/posts/${postId}/unlike`)
                                    .then(response => {
                                        const likesCount = response.data.data.likes_count;
                                        toggleDivLike(likesCount);
                                    })
                                    .catch(error => {
                                        $btnUnlike.removeClass('hidden');
                                        $btnLike.addClass('hidden');
                                    })
                                    .finally(function () {
                                        likeInProgress = false;
                                    });
                            }

                            $btnLike.off('click').click(handleLike);
                            $btnUnlike.off('click').click(handleUnlike)
                            $carousel.off('dblclick').dblclick(handleLike)

                            let saveInProgress = false;

                            function handleSave() {
                                if (saveInProgress) return;
                                saveInProgress = true;

                                const postId = appPosts[index].id;

                                $btnSave.addClass('hidden');
                                $btnUnsave.removeClass('hidden');

                                axios.post(`/api/posts/${postId}/save`)
                                    .catch(error => {
                                        $btnSave.removeClass('hidden');
                                        $btnUnsave.addClass('hidden');
                                    })
                                    .finally(function () {
                                        saveInProgress = false;
                                    });
                            }

                            function handleUnsave() {
                                if (saveInProgress) return;
                                saveInProgress = true;

                                const postId = appPosts[index].id;

                                $btnSave.removeClass('hidden');
                                $btnUnsave.addClass('hidden');

                                axios.post(`/api/posts/${postId}/unsave`)
                                    .catch(error => {
                                        $btnSave.addClass('hidden');
                                        $btnUnsave.removeClass('hidden');
                                    })
                                    .finally(function () {
                                        saveInProgress = false;
                                    });
                            }

                            $btnSave.off('click').click(handleSave);
                            $btnUnsave.off('click').click(handleUnsave);

                            $btnMoreCaption.off('click').click(function () {
                                $spanShortCaption.addClass('hidden');
                                $spanFullCaption.removeClass('hidden');
                            });

                            $btnMoreOptions.off('click').click(function () {
                                const post = appPosts[index];
                                toggleMoreOptionsDialog(post);
                            });

                            let followInProgress = false;

                            $btnFollow.off('click').click(function () {
                                if (followInProgress) return;
                                followInProgress = true;

                                const postId = appPosts[index].id;

                                axios.post(`/api/authors/${postId}/follow`).then(response => {
                                    $($btnFollow).addClass('hidden');
                                }).finally(function () {
                                    followInProgress = false;
                                });
                            });
                        });
                    }

                    function loadMorePosts() {
                        if (!nextPageUrl) return;
                        if (isFetching) return;
                        isFetching = true;
                        axios.get(nextPageUrl).then(response => {
                            appPosts.push(...response.data.data);
                            nextPageUrl = response.data.next_page_url;
                            $('#posts_container').append(response.data.html);
                            initPostEvents();
                        }).finally(function () {
                            isFetching = false;
                        });
                    }

                    $(window).scroll(_.throttle(function () {
                        if ($(window).scrollTop() + $(window).height() + scrollOffset >= $(document).height()) {
                            loadMorePosts();
                        }
                    }, 500));

                    initPostEvents();

                });
            </script>
        @endpush
    @endonce
</div>
