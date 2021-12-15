<div id="followers_container"
     class="flex flex-col items-stretch">
    @component('components.user.followers', ['followers' => $followers])@endcomponent
    @component('components.post.confirm-unfollow-dialog')@endcomponent
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
                        const $btnUnfollow = $(this).find('.btn_unfollow');

                        let followingInProgress = false;

                        function handleFollow() {
                            if (followingInProgress) return;
                            followingInProgress = true;

                            const userId = appData.followers[index].id;

                            $btnFollow.attr('disabled', true);
                            $btnFollow.find('span').addClass('invisible');
                            $btnFollow.find('svg').removeClass('hidden');

                            axios.post(`/api/users/${userId}/follow`)
                                .then(response => {
                                    $btnFollow.attr('disabled', false);
                                    $btnFollow.find('span').removeClass('invisible');
                                    $btnFollow.find('svg').addClass('hidden');
                                    $btnFollow.addClass('hidden');
                                    $btnUnfollow.removeClass('hidden');
                                })
                                .catch(error => {
                                    $btnFollow.attr('disabled', false);
                                    $btnFollow.find('span').removeClass('invisible');
                                    $btnFollow.find('svg').addClass('hidden');
                                })
                                .finally(function () {
                                    followingInProgress = false;
                                });
                        }

                        function handleUnfollow() {
                            const user = appData.followers[index];
                            const $divConfirmUnfollowDialog = $('#div_confirm_unfollow_dialog');
                            window.toggleDialog($divConfirmUnfollowDialog, function () {

                                if (user.profile_image) {
                                    $divConfirmUnfollowDialog.find('.img_profile').attr('src', '/storage/' + user.profile_image);
                                }
                                $divConfirmUnfollowDialog.find('.span_username').text(user.username);

                                const $unfollow = $divConfirmUnfollowDialog.find('.btn_unfollow');
                                const $cancel = $divConfirmUnfollowDialog.find('.btn_cancel');

                                $unfollow.add($cancel).off('click');

                                let requestInProgress = false;

                                $unfollow.click(function () {
                                    if (requestInProgress) return;
                                    requestInProgress = true;

                                    axios.post(`/api/users/${user.id}/unfollow`)
                                        .then(response => {
                                            $btnUnfollow.addClass('hidden');
                                            $btnFollow.removeClass('hidden');
                                            toggleDialog($divConfirmUnfollowDialog);
                                        })
                                        .finally(function () {
                                            requestInProgress = false;
                                        })
                                });

                                $cancel.click(function () {
                                    window.toggleDialog($divConfirmUnfollowDialog);
                                });
                            });
                        }

                        $btnFollow.off('click').click(handleFollow);
                        $btnUnfollow.off('click').click(handleUnfollow);
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
