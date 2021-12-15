<div id="activities_container" class="flex-grow flex flex-col items-stretch">
    @component('components.activity.activities', ['notifications' => $notifications])@endcomponent
    @component('components.post.confirm-unfollow-dialog')@endcomponent
</div>
@once
    @push('scripts')
        <script>
            var appData = {
                notifications: @json($notifications->items())
            };

            $(document).ready(function () {
                const $activitiesContainer = $('#activities_container');

                const scrollOffset = 500;
                let isFetching = false;
                let nextPageUrl = "{{ $notifications->nextPageUrl() }}";

                function initEvents() {
                    const $divActivities = $activitiesContainer.find('.div_activity');

                    $divActivities.each(function (index) {
                        const $btnFollow = $(this).find('.btn_follow');
                        const $btnUnfollow = $(this).find('.btn_unfollow');

                        let followingInProgress = false;

                        function handleFollow() {
                            if (followingInProgress) return;
                            followingInProgress = true;

                            const userId = appData.notifications[index].following.follower.id;

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
                            const user = appData.notifications[index].following.follower;
                            showConfirmUnfollowDialog(user, function (response) {
                                $btnUnfollow.addClass('hidden');
                                $btnFollow.removeClass('hidden');
                            });
                        }

                        $btnFollow.off('click').click(handleFollow);
                        $btnUnfollow.off('click').click(handleUnfollow);
                    });
                }

                function loadMoreNotifications() {
                    if (!nextPageUrl) return;
                    if (isFetching) return;

                    isFetching = true;

                    axios.get(nextPageUrl)
                        .then(response => {
                            console.log(response.data);
                            appData.notifications.push(...response.data.data);
                            nextPageUrl = response.data.next_page_url;
                            $activitiesContainer.append(response.data.html);
                            initEvents();
                        })
                        .finally(function () {
                            isFetching = false;
                        });
                }

                $(window).scroll(_.throttle(function () {
                    if ($(window).scrollTop() + $(window).height() + scrollOffset >= $(document).height()) {
                        loadMoreNotifications();
                    }
                }, 500));

                initEvents();
            });
        </script>
    @endpush
@endonce
