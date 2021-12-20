<section id="section_menu" class="h-full flex flex-col items-stretch hidden">
    <header class="absolute top-0 left-0 right-0">
        <div class="h-11 flex items-center justify-between px-4">
            <button id="btn_close_menu">
                <span class="block material-icons-outlined text-3xl text-triple38">close</span>
            </button>
            <div class="flex-grow text-base text-triple38 font-semibold leading-18px text-center">
                Options
            </div>
            <div class="w-6"></div>
        </div>
    </header>
    <div class="absolute top-11 left-0 right-0 bottom-0 flex flex-col items-stretch bg-triple250 overflow-y-auto">
        <div class="flex flex-col items-stretch">
            <div class="flex flex-col items-stretch border-b border-solid border-triple219">
                <h3 class="text-sm text-triple142 font-semibold leading-18px uppercase mx-4 mt-5 mb-2">
                    Options</h3>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center" href="{{ route('signup') }}">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Sign Up
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center" href="{{ route('login') }}">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Log In
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Download App
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Language
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-stretch">
            <div class="flex flex-col items-stretch border-b border-solid border-triple219">
                <h3 class="text-sm text-triple142 font-semibold leading-18px uppercase mx-4 mt-5 mb-2">
                    About</h3>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Ads
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Help Center
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                More
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col items-stretch">
            <div class="flex flex-col items-stretch border-b border-solid border-triple219">
                <h3 class="text-sm text-triple142 font-semibold leading-18px uppercase mx-4 mt-5 mb-2">
                    Directories</h3>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Hashtags
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Profiles
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
    <script>
        $(document).ready(function () {

            function handleMenuClick() {
                $sectionMain = $('#section_main');
                $sectionMenu = $('#section_menu');

                if ($sectionMain.hasClass('hidden')) {
                    $sectionMain.removeClass('hidden');
                    $sectionMenu.addClass('hidden');
                } else {
                    $sectionMain.addClass('hidden');
                    $sectionMenu.removeClass('hidden');
                }
            }

            $('#btn_open_menu').click(handleMenuClick);
            $('#btn_close_menu').click(handleMenuClick);
        });
    </script>
@endpush
