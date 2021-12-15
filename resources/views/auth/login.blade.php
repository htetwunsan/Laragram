<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <main class="flex flex-col flex-grow min-h-full">
            <nav class="flex justify-center items-center relative mx-4 mt-4 mb-1.5">
                <div class="leading-none">
                    <span class="text-xs text-triple142 font-medium leading-4">English</span>
                    <x-chevron-icon class="w-3 h-3 inline-block transform rotate-180 text-xs leading-none"/>
                </div>
                <button id="btn_open_menu" class="absolute top-0 left-0" type="button">
                    <span class="block more_horiz opacity-50"></span>
                </button>
            </nav>
            <article class="flex flex-grow items-stretch justify-center pb-8">
                <div class="flex flex-grow flex-col pb-16 items-center">
                    <div class="flex flex-col items-center py-2.5">
                        <h1 class="font-cookie font-semibold text-5xl text-triple38 tracking-wider mt-6 mb-3">{{ config('app.name', 'Laragram') }}</h1>
                    </div>
                    <div class="flex flex-col items-stretch" style="width: 350px">
                        <form class="flex flex-col items-stretch mt-6" method="POST" action="{{ route('login') }}">@csrf
                            <div class="flex flex-col items-stretch my-2 mx-10">
                                <button
                                    id="btn_continue_with_facebook"
                                    class="bg-fb_blue flex flex-row gap-x-1 items-center justify-center h-8 appearance-none py-1 px-2 border border-transparent border-solid rounded leading-5"
                                    type="button"
                                    onclick="showBottomToast('This feature is not available yet.')">
                                    <x-fb-icon/>
                                    <span
                                        class="block text-white text-sm font-bold leading-4 tracking-normal">Continue with Facebook</span>
                                </button>
                            </div>
                            <div class="flex flex-row items-center mt-3.5 mb-6 mx-10 h-4">
                                <hr class="flex-grow"/>
                                <div class="mx-4"><span
                                        class="text-sm text-triple142 leading-normal font-semibold">OR</span>
                                </div>
                                <hr class="flex-grow"/>
                            </div>
                            <div class="flex flex-col items-stretch mx-10 mb-2">
                                <div
                                    class="flex flex-col items-stretch border border-solid border-triple219 rounded overflow-hidden">
                                    <label class="h-9 relative">
                                        <input
                                            id="input_email"
                                            class="floating-input text-xs leading-18px w-full border-none placeholder-transparent"
                                            type="text"
                                            name="email"
                                            placeholder="Username or email"
                                            required autofocus/>
                                        <span
                                            class="floating-label h-9 leading-9 absolute left-2 text-xs text-triple142 select-none pointer-events-none">Username or email</span>
                                    </label>
                                </div>
                            </div>

                            <div class="flex flex-col items-stretch mx-10 mb-2">
                                <div
                                    class="flex flex-col items-stretch border border-solid border-triple219 rounded overflow-hidden">
                                    <div class="flex items-center">
                                        <label class="h-9 relative flex-grow">
                                            <input
                                                id="input_password"
                                                class="floating-input text-xs leading-18px w-full border-none placeholder-transparent"
                                                type="password"
                                                name="password"
                                                placeholder="Password"
                                                autocomplete="current-password"
                                                required/>
                                            <span
                                                class="floating-label h-9 leading-9 absolute left-2 text-xs text-triple142 select-none pointer-events-none"
                                            >Password</span>
                                        </label>

                                        <button id="btn_show"
                                                class="text-sm text-triple38 text-center font-semibold tracking-normal leading-18px ml-2 pr-2 hidden"
                                                type="button">
                                            Show
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-row justify-end mx-10 mb-2 py-2.5">
                                <a href="{{ route('password.request') }}">
                                    <div class="text-sm text-fb_blue leading-4">
                                        Forgot password?
                                    </div>
                                </a>
                            </div>
                            <div class="flex flex-col items-stretch mx-10 my-2">
                                <button
                                    id="btn_submit"
                                    class="bg-fb_blue bg-opacity-30 text-sm text-center font-semibold text-white leading-18px border border-solid rounded w-auto py-1.5 px-2.5"
                                    disabled
                                    type="submit">
                                    Log In
                                </button>
                            </div>
                            @if($errors->any())
                                <div class="flex flex-col items-stretch mx-10 my-2.5">
                                    @foreach($errors->all() as $error)
                                        <span class="text-sm text-error text-center font-normal leading-18px"
                                        >{{ $error }}</span>
                                    @endforeach
                                </div>
                            @endif
                            <div class="flex flex-col items-center mx-10 mt-4">
                                <div class="-mt-1 -mb-4">
                                    <p class="text-sm text-triple142 leading-18px">
                                        Don't have an account?
                                        <a href="{{ route('signup') }}">
                                        <span class="text-sm text-fb_blue font-semibold leading-18px"
                                        >Sign up</span>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </article>
            <div class="flex flex-col items-center justify-center mb-6">
                <span class="block text-xs text-gray-400 leading-none">from</span>
                <span class="block font-sans font-semibold text-sm text-triple38 tracking-widest"
                >FACEBOOK</span>
            </div>
        </main>
        <footer class="bg-triple250 flex flex-col items-stretch px-4">
            <div class="flex flex-col items-stretch mb-3">
                <div class="flex flex-col items-stretch mt-6 justify-start">
                    <div class="flex flex-wrap items-stretch justify-center">
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2"
                        >Meta</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">About</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Blog</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Jobs</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Help</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Api</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Privacy</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Terms</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2"
                        >Top Accounts</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Hashtags</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Locations</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2"
                        >Instagram Lite</span>
                    </div>

                    <div class="flex flex-wrap items-stretch justify-center">
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Beauty</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Dance</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Fitness</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2"
                        >Food & Drink</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2"
                        >Home & Garden</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2">Music</span>
                        <span class="block text-xs text-triple142 leading-4 mx-2 mb-2"
                        >Visual Arts</span>
                    </div>
                </div>
            </div>
        </footer>
    </section>

    <x-app-bottom-toast/>

    <x-guest-menu/>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $inputEmail = $('#input_email');
                $inputPassword = $('#input_password');
                $btnSubmit = $('#btn_submit');
                $btnShow = $('#btn_show');

                let emailValid = false;
                let passwordValid = false;

                function toggleShowBtn() {
                    if ($inputPassword.val().length > 0) {
                        $btnShow.removeClass('hidden');
                    } else {
                        $btnShow.addClass('hidden');
                    }
                }

                function togglePasswordVisibility() {
                    if ($inputPassword.attr('type') === 'password') {
                        $inputPassword.attr('type', 'text');
                        $inputPassword.attr('autocomplete', 'off');
                        $btnShow.text('Hide');
                    } else {
                        $inputPassword.attr('type', 'password');
                        $inputPassword.attr('autocomplete', 'current-password');
                        $btnShow.text('Show');
                    }
                }

                function validate() {
                    if (emailValid && passwordValid) {
                        $btnSubmit.removeClass('bg-opacity-30');
                        $btnSubmit.prop('disabled', false);
                    } else {
                        $btnSubmit.addClass('bg-opacity-30');
                        $btnSubmit.prop('disabled', true);
                    }
                }

                toggleShowBtn();

                $inputEmail.on('input propertychange', function () {
                    emailValid = $(this).val().length > 0;
                    validate();
                });

                $inputPassword.on('input propertychange', function () {
                    toggleShowBtn();
                    passwordValid = $(this).val().length >= 8;
                    validate();
                });

                $btnShow.click(togglePasswordVisibility);
            });
        </script>
    @endpush
</x-base-layout>
