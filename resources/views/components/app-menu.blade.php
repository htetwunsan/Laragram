<section id="section_menu" class="h-full flex flex-col items-stretch hidden relative">
    <header class="absolute top-0 w-full">
        <div class="h-11 flex items-center justify-between px-4">
            <button id="btn_close_menu">
                <svg aria-label="Close" class="w-6 h-6 text-triple38 fill-current" role="img"
                     viewBox="0 0 48 48">
                    <path clip-rule="evenodd"
                          d="M41.1 9.1l-15 15L41 39c.6.6.6 1.5 0 2.1s-1.5.6-2.1 0L24 26.1l-14.9 15c-.6.6-1.5.6-2.1 0-.6-.6-.6-1.5 0-2.1l14.9-15-15-15c-.6-.6-.6-1.5 0-2.1s1.5-.6 2.1 0l15 15 15-15c.6-.6 1.5-.6 2.1 0 .6.6.6 1.6 0 2.2z"
                          fill-rule="evenodd"></path>
                </svg>
            </button>
            <div class="flex-grow text-base text-triple38 font-semibold leading-18px text-center">
                Options
            </div>
            <div class="w-6"></div>
        </div>
    </header>

    <div class="absolute top-11 left-0 right-0 bottom-0 overflow-y-auto flex flex-col items-stretch bg-triple250">
        <div class="flex flex-col items-stretch">
            <div class="flex flex-col items-stretch border-b border-solid border-triple219">
                <h3 class="text-sm text-triple142 font-semibold leading-18px uppercase mx-4 mt-5 mb-2">
                    Account</h3>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center" href="{{ route('auth.edit') }}">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Edit Profile
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center" href="#">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Nametag
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center" href="{{ route('password.change') }}">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Change Password
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Privacy and Security
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Login Activity
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Emails from {{ config('app.name', 'Laragram') }}
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
                    Settings</h3>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Language
                            </div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Apps and Websites
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Notifications
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

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Ads
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Help Center
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
                    <div class="flex flex-col items-stretch mx-4">
                        <a class="flex items-center">
                            <div class="text-base text-triple38 leading-11 flex-grow">
                                Report a problem
                            </div>
                            <div class="right-chevron"></div>
                        </a>
                    </div>
                </div>

                <div class="bg-white flex flex-col items-stretch border-t border-solid border-triple219">
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

        <div class="flex flex-col items-stretch mt-3 mb-14 border-b border-solid border-triple219">
            <div class="bg-white flex flex-col items-stretch px-4 border-t border-solid border-triple219">
                <a class="flex items-center" tabindex="0" href="{{ route('logout') }}">
                    <div class="text-base text-error leading-11 flex-grow">Log Out</div>
                    <div class="right-chevron"></div>
                </a>
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
