<x-base-layout>
    <section id="section_main" class="bg-triple38 h-full flex flex-col items-stretch overflow-y-auto">
        <!--header-->
        <nav class="flex flex-col items-stretch order-first z-50">
            <header class="flex flex-col items-stretch px-2 pt-2"
                    style="height: 72px">
                <!--indicators-->
                <div class="flex items-center justify-center gap-0.5 mb-2" style="height: 2px">
                    @foreach($stories as $story)
                        <div class="h-full flex-grow relative">
                            <div class="h-full bg-white bg-opacity-35"></div>
                            <div
                                class="div_indicator h-full bg-white absolute left-0 top-0"></div>
                        </div>
                    @endforeach
                </div>

                <div class="flex items-center justify-between mx-2">
                    <div class="flex items-center justify-center">
                        <a class="flex items-center justify-center"
                           href="{{ route('user.show', ['user' => $user->username]) }}">
                            <div class="w-8 h-8">
                                <x-profile-image class="w-full h-full rounded-full" alt="">
                                    {{ $user->profile_image }}
                                </x-profile-image>
                            </div>

                            <div class="ml-2 pr-2.5">
                                <span
                                    class="text-sm text-white text-center font-semibold leading-18px overflow-ellipsis">
                                    {{ $user->username }}
                                </span>
                            </div>
                        </a>

                        <div id="div_time">
                            @foreach($stories as $story)
                                <time
                                    class="text-sm text-white text-center text-opacity-60 {{ $loop->first ? 'block' : 'hidden' }}"
                                    style="line-height: 17px"
                                    datetime="{{ $story->created_at }}">
                                    {{ $story->created_at->diffForHumans() }}
                                </time>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center justify-center">
                        <button id="btn_close" class="p-2" type="button">
                            <svg aria-label="Close" color="#ffffff" fill="#ffffff" height="24"
                                 role="img" viewBox="0 0 48 48" width="24">
                                <path clip-rule="evenodd"
                                      d="M41.1 9.1l-15 15L41 39c.6.6.6 1.5 0 2.1s-1.5.6-2.1 0L24 26.1l-14.9 15c-.6.6-1.5.6-2.1 0-.6-.6-.6-1.5 0-2.1l14.9-15-15-15c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l15 15 15-15c.6-.6 1.5-.6 2.1 0 .6.6.6 1.6 0 2.2z"
                                      fill-rule="evenodd">

                                </path>
                            </svg>
                        </button>
                    </div>
                </div>
            </header>
        </nav>

        <!--footer-->
        <nav class="flex flex-col items-stretch order-last px-5 z-50">
            <div class="flex flex-col items-stretch h-11">
                <div class="flex flex-col items-stretch">
                    <div class="h-11"></div>
                    <div
                        class="max-w-screen-sm mx-auto h-11 fixed left-0 right-0 bottom-0 flex flex-col items-stretch z-10">
                        <div class="flex flex-row items-stretch justify-end h-full">
                            <footer class="flex flex-col items-stretch justify-end px-4 pb-2">
                                <div class="flex items-center justify-end">
                                    <button class="p-2" type="button">
                                        <svg aria-label="Direct" color="#dbdbdb" fill="#dbdbdb" height="24"
                                             role="img" viewBox="0 0 48 48" width="24">
                                            <path
                                                d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">

                                            </path>
                                        </svg>
                                    </button>

                                    <button id="btn_more_options" class="p-2" type="button">
                                        <svg aria-label="Menu" color="#ffffff" fill="#ffffff" height="24" role="img"
                                             viewBox="0 0 24 24" width="24">
                                            <circle cx="12" cy="12" r="2.75"></circle>
                                            <circle cx="19.5" cy="12" r="2.75"></circle>
                                            <circle cx="4.5" cy="12" r="2.75"></circle>
                                        </svg>
                                    </button>
                                </div>
                            </footer>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!--main-->
        <main class="flex-grow flex flex-col items-stretch" role="main">
            <div class="flex-grow flex items-center justify-center relative">
                <div id="div_carousel"
                     class="flex flex-row items-center overflow-hidden no-scrollbar"
                     style="scroll-snap-type: x mandatory;">
                    @foreach($stories as $story)
                        <div class="w-full h-full flex-none flex items-center justify-center"
                             style="scroll-snap-align: center">
                            <x-story.image class="w-full h-full sm:w-4/5 object-cover" alt="">
                                {{ $story->image }}
                            </x-story.image>
                        </div>
                    @endforeach
                </div>


                <div class="flex items-center justify-center absolute inset-0 opacity-0">
                    <button id="btn_prev"
                            class="bg-purple-500 w-1/5 h-full opacity-0 cursor-default z-10"
                            type="button">
                    </button>

                    <button id="btn_pause_resume"
                            class="bg-pink-500 w-3/5 h-full opacity-0 cursor-default z-10"
                            type="button">

                    </button>

                    <button id="btn_next"
                            class="bg-yellow-500 w-1/5 h-full opacity-0 cursor-default z-10"
                            style="top: 72px; bottom: 72px; width: 20%"
                            type="button">
                    </button>
                </div>
            </div>
        </main>

        @component('components.story.more-options-dialog')@endcomponent

        @component('components.success', ['message' => session('success')])@endcomponent
    </section>
    @once
        @push('scripts')
            <script>
                var appData = {
                    stories: @json($stories),
                    authUser: @json(auth()->user())
                };
                $(document).ready(function () {
                    const $carousel = $('#div_carousel');
                    const $btnNext = $('#btn_next');
                    const $btnPauseResume = $('#btn_pause_resume');
                    const $btnPrevious = $('#btn_prev');
                    const $btnClose = $('#btn_close');
                    const $btnMoreOptions = $('#btn_more_options');

                    const $divIndicators = $('.div_indicator');
                    let length = $divIndicators.length;
                    const index = {
                        _value: 0,

                        get value() {
                            return this._value;
                        },

                        set value(value) {
                            if (value < 0) {
                                value = 0;
                            }
                            this.onChange(this._value, value);
                            this._value = value;
                        },

                        _onChangeListener: function (oldVal, newVal) {
                        },

                        onChange: function (externalOnChangeListener) {
                            this._onChangeListener = externalOnChangeListener;
                        },
                    }

                    index.onChange = function (oldVal, newVal) {
                        if (newVal >= length) {
                            $btnClose.trigger('click');
                            return;
                        }
                        axios.post(`/api/stories/${appData.stories[newVal].id}/view`);
                    };

                    index.value = 0;

                    function toggleIndicator() {
                        if (index.value >= length) return;
                        for (let i = 0; i < index.value; ++i) {
                            const $indicator = $divIndicators.eq(i);
                            $indicator.stop();
                            $indicator.css('width', '100%');
                        }

                        for (let i = index.value; i < length; ++i) {
                            const $indicator = $divIndicators.eq(i);
                            $indicator.stop();
                            $indicator.css('width', '0');
                        }

                        const $indicator = $divIndicators.eq(index.value);
                        $indicator.animate({'width': '100%'}, 7000, 'linear', function () {
                            $btnNext.trigger('click');
                        });
                    }

                    function toggleTime() {
                        if (index.value >= length) return;
                        const $times = $('#div_time time');
                        for (let i = 0; i < length; ++i) {
                            const $time = $times.eq(i);
                            if (i === index.value) {
                                $time.removeClass('hidden');
                                continue;
                            }

                            $time.addClass('hidden');
                        }
                    }

                    function nextCarousel() {
                        if (index.value >= length) return;
                        $carousel.scrollLeft($carousel.scrollLeft() + $carousel.width());
                    }

                    function previousCarousel() {
                        if (index.value >= length) return;
                        $carousel.scrollLeft($carousel.scrollLeft() - $carousel.width());
                    }

                    $btnNext.click(function () {
                        index.value++;
                        nextCarousel();
                        toggleIndicator();
                        toggleTime();
                    });

                    $btnPauseResume.click(function () {
                        const $indicator = $divIndicators.eq(index.value);
                        if ($indicator.is(':animated')) {
                            $indicator.pause();
                        } else {
                            $indicator.resume();
                        }
                    });

                    $btnPrevious.click(function () {
                        index.value--;
                        previousCarousel();
                        toggleIndicator();
                        toggleTime();
                    });

                    $btnClose.click(function () {
                        if (document.referrer) {
                            window.location.href = document.referrer;
                        } else {
                            window.location.href = '{{ route('post.index') }}';
                        }
                    });

                    $btnMoreOptions.click(function () {
                        const story = appData.stories[index.value];
                        toggleMoreOptionsDialog(story, function () {
                            $divIndicators.eq(index.value).pause();
                        });
                    });

                    toggleIndicator();
                    toggleTime();
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
