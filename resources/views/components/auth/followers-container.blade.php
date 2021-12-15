<div id="followers_container"
     class="flex flex-col items-stretch">
    @component('components.auth.followers', ['followers' => $followers])@endcomponent
    @component('components.auth.confirm-remove-follower-dialog')@endcomponent
</div>
@once
    @push('scripts')
        <script>
            var appData = {
                followers: @json($followers->items())
            };

            $(document).ready(function () {
                const $followersContainer = $('#followers_container');

                const scrollOffset = 500;
                let isFetching = false;
                let nextPageUrl = "{{ $followers->nextPageUrl() }}";

                function initEvents() {
                    const $divFollowers = $followersContainer.find('.div_follower');

                    $divFollowers.each(function (index) {
                        const $btnFollow = $(this).find('.btn_follow');
                        const $btnRemoveFollower = $(this).find('.btn_remove_follower');
                        const $btnRemoveFollowerDone = $(this).find('.btn_remove_follower_done');

                        let followingInProgress = false;

                        function handleFollow() {
                            if (followingInProgress) return;
                            followingInProgress = true;

                            const userId = appData.followers[index].id;

                            axios.post(`/api/users/${userId}/follow`)
                                .then(response => {
                                    $btnFollow.addClass('hidden');
                                })
                                .finally(function () {
                                    followingInProgress = false;
                                });
                        }

                        function handleRemoveFollower() {
                            const user = appData.followers[index];
                            showConfirmRemoveFollowerDialog(user, function (response) {
                                $btnRemoveFollower.addClass('hidden');
                                $btnRemoveFollowerDone.removeClass('hidden');
                            });
                        }

                        $btnFollow.off('click').click(handleFollow);
                        $btnRemoveFollower.off('click').click(handleRemoveFollower);
                    });
                }

                function loadMoreFollowers() {
                    if (!nextPageUrl) return;
                    if (isFetching) return;

                    isFetching = true;

                    axios.get(nextPageUrl)
                        .then(response => {
                            appData.followers.push(...response.data.data);
                            nextPageUrl = response.data.next_page_url;
                            $followersContainer.append(response.data.html);
                            initEvents();
                        })
                        .finally(function () {
                            isFetching = false;
                        });
                }

                $(window).scroll(_.throttle(function () {
                    if ($(window).scrollTop() + $(window).height() + scrollOffset >= $(document).height()) {
                        loadMoreFollowers();
                    }
                }, 500));

                initEvents();
            });
        </script>
    @endpush
@endonce
