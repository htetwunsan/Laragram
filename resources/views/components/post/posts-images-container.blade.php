<div id="posts_container" class="grid grid-cols-3 gap-0.5 overflow-y-auto">
    @component('components.user.posts-images', ['posts' => $posts])@endcomponent
</div>
@once
    @push('scripts')
        <script>
            $(document).ready(function () {
                const $postsContainer = $('#posts_container');

                const scrollOffset = 1000;
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

                $($postsContainer).scroll(_.throttle(function () {
                    if ($($postsContainer).scrollTop() + $($postsContainer).height() + scrollOffset >= $($postsContainer).height()) {
                        loadMorePosts();
                    }
                }, 500));
            });
        </script>
    @endpush
@endonce
