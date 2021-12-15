<div class="flex-grow flex flex-col items-stretch">
    <div
        class="flex flex-row items-baseline justify-start">
        <div
            class="flex flex-col flex-grow flex-shrink items-stretch justify-start">
            <div
                class="flex flex-col flex-grow-0 flex-shrink-0 items-center justify-start m-4">
                <div class="text-xs text-triple142 font-normal">Only you can
                    see what you've saved
                </div>
            </div>
        </div>
    </div>
    @if($posts->count() > 0)
        <div class="flex-grow flex flex-col items-stretch">
            <div id="posts_container" class="grid grid-cols-3 gap-0.5">
                @component('components.user.posts-images', ['posts' => $posts])@endcomponent
            </div>
        </div>
        @once
            @push('scripts')
                <script>
                    $(document).ready(function () {
                        const scrollOffset = 500;
                        let isFetching = false;
                        let nextPageUrl = "{{ $posts->nextPageUrl() }}";

                        function loadMorePosts() {
                            if (!nextPageUrl) return;
                            if (isFetching) return;
                            isFetching = true;
                            axios.get(nextPageUrl).then(response => {
                                nextPageUrl = response.data.next_page_url;
                                $('#posts_container').append(response.data.html);
                            }).finally(function () {
                                isFetching = false;
                            })
                        }

                        $(window).scroll(_.throttle(function () {
                            if ($(window).scrollTop() + $(window).height() + scrollOffset >= $(document).height()) {
                                loadMorePosts();
                            }
                        }, 500));
                    });
                </script>
            @endpush
        @endonce
    @else
        <div class="flex flex-col items-stretch justify-start">
            <div class="flex flex-col items-center justify-start mx-11 my-15">
                <!-- replace with selected -->
                <div class="saved-circle"></div>
                <div
                    class="flex flex-col items-stretch justify-start my-6">
                    <h1 class="text-triple38 font-light leading-8 -mt-1.5 -mb-1.5"
                        style="font-size: 1.75rem">Save</h1>
                </div>
                <div
                    class="flex flex-col items-stretch justify-start mb-6">
                    <div class="text-sm text-triple38 text-center font-normal leading-18px -my-1">Save
                        photos
                        and videos that you want to see again. No one is notified, and only you can see what
                        you've saved.
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
