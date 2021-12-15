<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <x-app-top-navigation>
            <x-slot name="left">
                <div class="flex flex-row items-stretch justify-start w-8">
                    <button type="button">
                        <svg aria-label="New Story" class="text-triple38 fill-current w-6 h-6"
                             role="img" viewBox="0 0 48 48">
                            <path clip-rule="evenodd"
                                  d="M38.5 46h-29c-5 0-9-4-9-9V17c0-5 4-9 9-9h1.1c1.1 0 2.2-.6 2.7-1.7l.5-1c1-2 3.1-3.3 5.4-3.3h9.6c2.3 0 4.4 1.3 5.4 3.3l.5 1c.5 1 1.5 1.7 2.7 1.7h1.1c5 0 9 4 9 9v20c0 5-4 9-9 9zm6-29c0-3.3-2.7-6-6-6h-1.1C35.1 11 33 9.7 32 7.7l-.5-1C31 5.6 29.9 5 28.8 5h-9.6c-1.1 0-2.2.6-2.7 1.7l-.5 1c-1 2-3.1 3.3-5.4 3.3H9.5c-3.3 0-6 2.7-6 6v20c0 3.3 2.7 6 6 6h29c3.3 0 6-2.7 6-6V17zM24 38c-6.4 0-11.5-5.1-11.5-11.5S17.6 15 24 15s11.5 5.1 11.5 11.5S30.4 38 24 38zm0-20c-4.7 0-8.5 3.8-8.5 8.5S19.3 35 24 35s8.5-3.8 8.5-8.5S28.7 18 24 18z"
                                  fill-rule="evenodd">

                            </path>
                        </svg>
                    </button>
                </div>
            </x-slot>

            <a href="/" tabindex="0">
                <h1 class="font-cookie font-semibold text-3xl text-triple38 tracking-wider">{{ config('app.name', 'Laragram') }}</h1>
            </a>

            <x-slot name="right">
                <div class="flex flex-row items-stretch justify-end w-8">
                    <a aria-label="Direct messaging" class="xWeGp" href="#"
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
            <div class="flex flex-col items-stretch justify-center px-8 py-6 mt-2">
                <h2 class="text-triple38 text-center font-light -my-1"
                    style="font-size: 22px; line-height: 26px;">
                    Welcome
                    to {{ config('app.name', 'Laragram') }}</h2>
                <div class="mt-4">
                    <div class="text-sm text-triple142 text-center font-normal leading-18px -my-1">
                        When you follow people, you'll see the photos and videos they post here.
                    </div>
                </div>
            </div>
            <div class="flex flex-col items-stretch relative">
                <div id="div_carousel"
                     class="flex flex-row items-center overflow-x-auto overflow-y-hidden gap-4 no-scrollbar mt-2 px-24"
                     style="scroll-behavior: smooth; overflow-scrolling: touch; scroll-snap-type: x mandatory; height: 388px">
                    @foreach($users as $user)
                        <div
                            class="div_user bg-white flex flex-col items-stretch flex-none w-64 h-64 my-1 rounded-3xl"
                            style="scroll-snap-align: center; width: 236px; height: 344px;"
                            role="button"
                            onclick="location.href = '{{ route('user.show', ['user' => $user->username]) }}';">
                            <div class="flex-grow flex flex-col items-center p-4 rounded-3xl relative"
                                 style="box-shadow: 0 2px 24px rgba(0, 0, 0, 0.1)">

                                <div class="absolute right-0 top-4 m-1">
                                    <button class="btn_close p-2" type="button">
                                        <svg aria-label="Dismiss" class="text-triple142 fill-current" height="11"
                                             role="img" viewBox="0 0 48 48" width="11">
                                            <path clip-rule="evenodd"
                                                  d="M41.8 9.8L27.5 24l14.2 14.2c.6.6.6 1.5 0 2.1l-1.4 1.4c-.6.6-1.5.6-2.1 0L24 27.5 9.8 41.8c-.6.6-1.5.6-2.1 0l-1.4-1.4c-.6-.6-.6-1.5 0-2.1L20.5 24 6.2 9.8c-.6-.6-.6-1.5 0-2.1l1.4-1.4c.6-.6 1.5-.6 2.1 0L24 20.5 38.3 6.2c.6-.6 1.5-.6 2.1 0l1.4 1.4c.6.6.6 1.6 0 2.2z"
                                                  fill-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>

                                <span class="block bg-triple250 border border-solid border-triple219 rounded-full mb-2"
                                      role="link"
                                      tabindex="-1"
                                      style="width: 88px; height: 88px;">
                                    <x-profile-image class="w-full h-full rounded-full" alt="profile picture">
                                        {{ $user->profile_image }}
                                    </x-profile-image>
                                </span>

                                <a class="text-sm text-triple38 text-center font-semibold leading-18px"
                                   href="{{ route('user.show', ['user' => $user->username]) }}">
                                    {{ $user->username }}
                                </a>

                                <div class="text-sm text-triple142 text-center font-normal leading-18px p-1 mb-2">
                                    {{ $user->name }}
                                </div>

                                <!--posts images-->
                                <div class="flex flex-col items-stretch justify-center" style="height: 133px">
                                    @if($user->postImages->count() <= 0)
                                        <div class="flex items-center justify-center py-2 gap-x-2">
                                            <div
                                                class="w-11 h-11 flex flex-none items-center justify-center border border-triple38 rounded-full">
                                                <span class="block camera"></span>
                                            </div>
                                            <div class="flex flex-col items-stretch gap-y-3">
                                                <div class="text-sm text-triple38 font-semibold leading-18px">
                                                    No Posts Yet
                                                </div>
                                                <div class="text-xs text-triple142 leading-4 -mt-0.5 -mb-1">When
                                                    {{ $user->username }} posts,
                                                    you'll see
                                                    their photos and
                                                    videos
                                                    here.
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex flex-row flex-auto items-center justify-center gap-x-px"
                                             style="width: 230px">
                                            @foreach($user->postImages as $post_image)
                                                <div style="height: 76px; width: 76px">
                                                    <img class="w-full h-full" alt="{{ $user->name }}'s post image."
                                                         src="{{ $post_image->image }}"/>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="flex flex-col items-center justify-center mb-1" style="height: 32px">
                                        <div class="text-xs text-triple142 text-center leading-4">
                                            <!--query related description-->
                                            New to {{ config('app.name', 'Laragram') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full flex flex-col items-stretch">
                                    <button
                                        class="btn_follow flex-grow bg-fb_blue border border-transparent rounded text-sm text-white text-center font-semibold leading-18px relative"
                                        style="padding: 5px 9px;"
                                        type="button">
                                        <span>Follow</span>
                                        <svg class="absolute inset-0 m-auto w-4 h-4 text-white animate-spin hidden"
                                             xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor"
                                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </button>
                                    <button
                                        class="btn_unfollow w-full bg-transparent border border-triple219 rounded text-sm text-triple38 text-center font-semibold leading-18px hidden"
                                        style="padding: 5px 9px;"
                                        type="button">
                                        Following
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <button id="btn_previous" class="absolute left-0 px-2 py-4 z-10" style="top: 170px"
                        type="button"
                        tabindex="-1">
                    <span class="block previous-chevron"></span>
                </button>
                <button id="btn_next" class="absolute right-0 px-2 py-4 z-10" style="top: 170px;"
                        type="button"
                        tabindex="-1">
                    <span class="block next-chevron"></span>
                </button>
            </div>
        </main>
        @component('components.post.confirm-unfollow-dialog')@endcomponent
    </section>
    @once
        @push('scripts')
            <script>
                var appUsers = @json($users);

                $(document).ready(function () {
                    const $carousel = $('#div_carousel');
                    const $btnNext = $('#btn_next');
                    const $btnPrevious = $('#btn_previous');


                    $btnNext.click(function () {
                        $carousel.scrollLeft($carousel.scrollLeft() + 236);
                    });

                    $btnPrevious.click(function () {
                        $carousel.scrollLeft($carousel.scrollLeft() - 236);
                    });

                    function toggleNext(valid) {
                        valid ? $btnNext.removeClass('hidden') : $btnNext.addClass('hidden');
                    }

                    function togglePrevious(valid) {
                        valid ? $btnPrevious.removeClass('hidden') : $btnPrevious.addClass('hidden');
                    }

                    $carousel.scroll(function () {
                        togglePrevious($(this).scrollLeft() >= 118);
                        toggleNext($(this)[0].scrollWidth - ($(this)[0].scrollLeft + $(this)[0].clientWidth) >= 118);
                    });

                    $carousel.trigger('scroll');

                    const $divUsers = $carousel.find('.div_user');
                    $divUsers.each(function (index) {
                        const $divUser = $(this);

                        const $btnClose = $(this).find('.btn_close');
                        const $btnFollow = $(this).find('.btn_follow');
                        const $btnUnfollow = $(this).find('.btn_unfollow');

                        $btnClose.click(function (event) {
                            event.stopPropagation();
                            $divUser.hide(500);
                        });

                        let followingInProgress = false;

                        function handleFollow(event) {
                            event.stopPropagation();
                            if (followingInProgress) return;
                            followingInProgress = true;

                            const userId = appUsers[index].id;

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

                                    $btnNext.trigger('click');
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

                        function handleUnfollow(event) {
                            event.stopPropagation();
                            const user = appUsers[index];
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
                                            window.toggleDialog($divConfirmUnfollowDialog);
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

                        $btnFollow.click(handleFollow);
                        $btnUnfollow.click(handleUnfollow);
                    });
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
