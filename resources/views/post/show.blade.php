<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar">
        <!--header-->
        <x-app-top-navigation>
            <x-slot name="left">
                <x-back-button></x-back-button>
            </x-slot>

            <h1 class="flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">
                Post</h1>

            <x-slot name="right">
                <div class="w-6 h-6"></div>
            </x-slot>
        </x-app-top-navigation>

        <!--footer-->
        <x-app-bottom-navigation>home</x-app-bottom-navigation>

        <!--main content-->
        @component('components.post.post', ['post' => $post])@endcomponent
        @component('components.post.more-options-dialog')@endcomponent
        @once
            @push('scripts')
                <script>
                    var appPost = @json($post);
                    var appAuthUser = @json(auth()->user());
                    $(document).ready(function () {

                        $('#div_confirm_delete_dialog')
                            .find('.form_delete_post')
                            .prepend(`<input type="hidden" name="redirect_to" value="index"/>`);

                        function initPostEvents() {
                            const $articles = $('.article_post');

                            $articles.each(function () {
                                const postId = appPost.id;

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


                                $btnNext.click(function () {
                                    $carousel.scrollLeft($carousel.scrollLeft() + $carousel.width());
                                });

                                $btnPrevious.click(function () {
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

                                $carousel.scroll(function () {
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

                                $btnLike.click(handleLike);
                                $btnUnlike.click(handleUnlike)
                                $carousel.dblclick(handleLike)

                                let saveInProgress = false;

                                function handleSave() {
                                    if (saveInProgress) return;
                                    saveInProgress = true;

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

                                $btnSave.click(handleSave);
                                $btnUnsave.click(handleUnsave);

                                $btnMoreCaption.click(function () {
                                    $spanShortCaption.addClass('hidden');
                                    $spanFullCaption.removeClass('hidden');
                                });

                                $btnMoreOptions.click(function () {
                                    toggleMoreOptionsDialog(appPost);
                                });

                                $btnFollow.click(function () {
                                    axios.post(`/api/authors/${postId}/follow`).then(response => {
                                        console.log(response.data);
                                        $($btnFollow).addClass('hidden');
                                    });
                                });
                            });
                        }

                        initPostEvents();

                    });
                </script>
            @endpush
        @endonce
    </section>
</x-base-layout>
