<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <main class="flex flex-col flex-grow items-stretch">
            <div class="flex justify-center items-center relative h-11">
            </div>
            <!--header-->
            <header
                class="max-w-screen-sm fixed top-0 w-full  h-11 z-10 bottom-0 border border-solid border-triple219 bg-white">
                <div class="flex w-full h-full items-center justify-between px-4">
                    <button id="btn_back" class="w-8 flex justify-start" type="button">
                        <span class="block arrow_back"></span>
                        {{--                        <span class="block text-triple38 material-icons-outlined -ml-0.5">arrow_back_ios</span>--}}
                    </button>
                    <h1 class="flex-grow text-center font-semibold text-base leading-18px">Register</h1>
                    <div class="w-8"></div>
                </div>
            </header>
            <!--footer-->
            <div id="div_footer" class="max-w-screen-sm fixed w-full bottom-0 h-11">
                <button class="w-full h-full py-3 border border-solid rounded border-transparent bg-fb_blue"
                        type="button"
                        onclick="showBottomToast('This feature is not available yet.')">
                    <div class="flex justify-center items-center">
                        <x-fb-icon></x-fb-icon>
                        <div
                            class="text-sm text-center text-white font-bold tracking-normal select-none leading-18px antialiased ml-1">
                            Sign up with Facebook
                        </div>
                    </div>
                </button>
            </div>

            <nav id="div_nav" class="flex justify-center items-center relative mx-4 mt-4 mb-1.5">
                <div class="leading-none">
                    <span class="text-xs text-triple142 font-medium leading-4">English</span>
                    <x-chevron-icon class="w-3 h-3 inline-block transform rotate-180 text-xs leading-none"/>
                </div>
                <button id="btn_open_menu" class="absolute top-0 left-0" type="button">
                    <span class="block more_horiz opacity-50"></span>
                </button>
            </nav>

            <div class="flex-grow flex flex-col items-stretch mx-10 mt-6">
                <form id="form_signup" method="POST" action="{{ route('signup') }}">@csrf
                    <div class="flex flex-col items-stretch">
                        <div id="div_email" class="flex flex-col items-stretch mb-2 gap-y-2">
                            <div
                                class="flex flex-col items-stretch border border-solid border-triple219 rounded overflow-hidden">
                                <label class="h-9 relative">
                                    <input
                                        id="input_email"
                                        class="floating-input text-xs leading-18px w-full border-none placeholder-transparent"
                                        type="email"
                                        name="email"
                                        placeholder="Email"
                                        required autofocus/>
                                    <span
                                        class="floating-label h-9 leading-9 absolute left-2 text-xs text-triple142 select-none pointer-events-none">Email Address</span>
                                </label>
                            </div>

                            <div
                                class="flex flex-row items-stretch justify-start overflow-y-hidden overflow-x-scroll gap-x-1">
                                <button
                                    id="btn_help_gmail"
                                    class="text-sm text-triple38 text-center font-semibold leading-18px border border-solid border-triple219 rounded px-2.5 py-1.5 mb-2"
                                    type="button">
                                    @gmail.com
                                </button>

                                <button
                                    id="btn_help_hotmail"
                                    class="text-sm text-triple38 text-center font-semibold leading-18px border border-solid border-triple219 rounded px-2.5 py-1.5 mb-2"
                                    type="button">
                                    @hotmail.com
                                </button>

                                <button
                                    id="btn_help_yahoo"
                                    class="text-sm text-triple38 text-center font-semibold leading-18px border border-solid border-triple219 rounded px-2.5 py-1.5 mb-2"
                                    type="button">
                                    @yahoo.com
                                </button>

                                <button
                                    id="btn_help_outlook"
                                    class="text-sm text-triple38 text-center font-semibold leading-18px border border-solid border-triple219 rounded px-2.5 py-1.5 mb-2"
                                    type="button">
                                    @outlook.com
                                </button>
                            </div>
                        </div>


                        <div id="div_name" class="flex flex-col items-stretch mb-2">
                            <div
                                class="flex flex-col items-stretch border border-solid border-triple219 rounded overflow-hidden">
                                <label class="h-9 relative">
                                    <input
                                        id="input_name"
                                        class="floating-input text-xs leading-18px w-full border-none placeholder-transparent"
                                        type="text"
                                        name="name"
                                        placeholder="Full Name"
                                        value="{{ old('name') }}"
                                        required autofocus/>
                                    <span
                                        class="floating-label h-9 leading-9 absolute left-2 text-xs text-triple142 select-none pointer-events-none">Full Name</span>
                                </label>
                            </div>
                        </div>

                        <div id="div_password" class="flex flex-col items-stretch mb-2">
                            <div
                                class="flex flex-col items-stretch border border-solid border-triple219 rounded overflow-hidden">
                                <div class="flex items-center">
                                    <label class="flex-grow h-9 relative">
                                        <input
                                            id="input_password"
                                            class="floating-input text-xs leading-18px w-full border-none placeholder-transparent"
                                            type="password"
                                            name="password"
                                            placeholder="Password"
                                            autocomplete="new-password"
                                            required autofocus/>
                                        <span
                                            class="floating-label h-9 leading-9 absolute left-2 text-xs text-triple142 select-none pointer-events-none">Password</span>
                                    </label>

                                    <button id="btn_show"
                                            type="button"
                                            class="text-sm text-triple38 text-center font-semibold tracking-normal leading-18px ml-2 pr-2 hidden">
                                        Show
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div id="div_birthday" class="flex flex-col items-stretch hidden">
                            <div class="flex flex-col items-center justify-center">
                                <img class="mb-4" width="144" height="96"
                                     src="{{ asset('images/birthday-cupcake.png') }}"
                                     alt="birthday cupcake"/>
                                <div class="text-sm text-triple38 font-semibold leading-18px mb-4">Add Your
                                    Birthday
                                </div>
                                <div class="flex flex-col items-stretch mb-4">
                                    <span class="block text-sm text-triple142 text-center leading-18px">This won't be a part of your public profile.</span>
                                    <button class="text-sm text-fb_blue text-center leading-18px -mt-0.5 -mb-1"
                                            type="button">Why do I
                                        need to provide my birthday?
                                    </button>
                                </div>
                                <div class="flex flex-col items-center justify-start mb-2">
                                    <div class="flex flex-col items-stretch">
                                        <div class="flex text-triple142 items-center justify-center gap-2">
                                            <label>
                                                <input id="input_birthday" class="hidden" type="date" name="birthday"/>
                                            </label>
                                            <select
                                                id="select_month"
                                                class="pl-2 pr-6 h-9 py-0 leading-default border-triple219 rounded text-xs leading-9 overflow-x-visible overflow-y-visible focus:ring-0 focus:border-none focus:text-black"
                                                title="Month:">
                                                {{--                                            <option title="January" value="1">January</option>--}}
                                            </select>

                                            <select
                                                id="select_date"
                                                class="pl-2 pr-6 h-9 py-0 leading-default border-triple219 rounded text-xs leading-9 overflow-x-visible overflow-y-visible focus:ring-0 focus:border-none focus:text-black"
                                                title="Day:">
                                                {{--                                            <option title="1" value="1">1</option>--}}
                                            </select>

                                            <select
                                                id="select_year"
                                                class="pl-2 pr-6 h-9 py-0 leading-default border-triple219 rounded text-xs leading-9 overflow-x-visible overflow-y-visible focus:ring-0 focus:border-none focus:text-black"
                                                title="Year:">
                                                {{--                                            @for($i = now()->year; $i >= now()->year -120 ; $i--)--}}
                                                {{--                                                <option title="{{ $i }}" value="{{ $i }}">{{ $i }}</option>--}}
                                                {{--                                            @endfor--}}
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col items-stretch justify-start mt-1 mb-2">
                                    <div class="text-xs text-triple142 text-center font-normal leading-4 -mt-0.5 -mb-1">
                                        Use your own birthday, even if this account is for a business, a pet, or
                                        something
                                        else
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="div_consent" class="flex flex-col items-stretch hidden">
                            <h1 class="text-sm text-triple38 text-center font-semibold leading-18px">Welcome
                                to {{ config('app_name', 'Laragram') }}, </h1>
                            <h2 class="text-sm text-triple142 text-center font-normal leading-18px px-7 py-4">
                                Find people to follow and start sharing photos. You can change your fullname anytime.
                            </h2>

                        </div>

                        @if($errors->any())
                            <div class="flex flex-col items-stretch mx-10 my-2.5">
                                @foreach($errors->all() as $error)
                                    <span
                                        class="text-sm text-error text-center font-normal leading-18px">{{ $error }}</span>
                                @endforeach
                            </div>
                        @endif


                        <div id="div_next" class="flex flex-col items-stretch my-4">
                            <button
                                id="btn_next"
                                class="bg-fb_blue bg-opacity-30 text-sm text-center font-semibold text-white leading-18px border border-solid rounded w-auto py-3 px-4"
                                disabled
                                type="button">
                                Next
                            </button>
                        </div>

                        <div id="div_submit" class="flex flex-col items-stretch my-4 hidden">
                            <button
                                id="btn_submit"
                                class="bg-fb_blue bg-opacity-30 text-sm text-center font-semibold text-white leading-18px border border-solid rounded w-auto py-3 px-4"
                                disabled
                                type="submit">
                                Sign Up
                            </button>
                        </div>

                        <p id="p_policy"
                           class="text-xs text-triple142 text-center font-normal leading-18px mt-2 mx-5 mb-14 hidden">By
                            signing up,
                            you agree to our
                            <a class="text-triple38 font-semibold"
                               href="#" tabindex="0" target="_blank">Terms</a> ,
                            <a class="text-triple38 font-semibold" href="#" tabindex="0" target="_blank">Data
                                Policy</a> and <a class="text-triple38 font-semibold" href="/legal/cookies/"
                                                  tabindex="0"
                                                  target="_blank">Cookies Policy</a> .
                        </p>

                    </div>
                </form>
            </div>
        </main>
    </section>

    <x-app-bottom-toast/>

    <x-guest-menu/>

    @push('scripts')
        <script>
            $(document).ready(function () {
                let step = 1;

                const $inputEmail = $('#input_email');
                const $inputName = $('#input_name');
                const $inputPassword = $('#input_password');
                const $inputBirthday = $('#input_birthday');

                const $selectMonth = $('#select_month');
                const $selectYear = $('#select_year');
                const $selectDate = $('#select_date');

                const $btnNext = $('#btn_next');
                const $btnSubmit = $('#btn_submit');
                const $btnShow = $('#btn_show');
                const $btnBack = $('#btn_back');

                const $btnHelpGmail = $('#btn_help_gmail');
                const $btnHelpHotmail = $('#btn_help_hotmail');
                const $btnHelpYahoo = $('#btn_help_yahoo');
                const $btnHelpOutlook = $('#btn_help_outlook');

                function toggleFields() {
                    const $divEmail = $('#div_email');
                    const $divName = $('#div_name');
                    const $divPassword = $('#div_password');
                    const $divBirthday = $('#div_birthday');

                    const $divNext = $('#div_next');
                    const $divSubmit = $('#div_submit');

                    const $divConsent = $('#div_consent');
                    const $pPolicy = $('#p_policy');

                    const $divNav = $('#div_nav');
                    const $divFooter = $('#div_footer');

                    switch (step) {
                        case 1:
                            $divEmail.removeClass('hidden');
                            $divName.removeClass('hidden');
                            $divPassword.removeClass('hidden');
                            $divNext.removeClass('hidden');
                            $divNav.removeClass('hidden');
                            $divFooter.removeClass('hidden');

                            $divConsent.addClass('hidden');
                            $pPolicy.addClass('hidden');
                            $divBirthday.addClass('hidden');
                            $divSubmit.addClass('hidden');
                            break;

                        case 2:
                            $divNext.removeClass('hidden');
                            $divBirthday.removeClass('hidden');

                            $divEmail.addClass('hidden');
                            $divName.addClass('hidden');
                            $divPassword.addClass('hidden');
                            $divSubmit.addClass('hidden');
                            $divNav.addClass('hidden');
                            $divFooter.addClass('hidden');
                            $divConsent.addClass('hidden');
                            $pPolicy.addClass('hidden');
                            break;

                        case 3:
                            $divConsent.removeClass('hidden');
                            $pPolicy.removeClass('hidden');
                            $divSubmit.removeClass('hidden');


                            $divEmail.addClass('hidden');
                            $divName.addClass('hidden');
                            $divPassword.addClass('hidden');
                            $divBirthday.addClass('hidden');
                            $divNext.addClass('hidden');
                            $divNav.addClass('hidden');
                            $divFooter.addClass('hidden');

                            $('#div_consent h1').append($inputName.val());
                            break;

                        default:
                            break;
                    }

                    validate();
                }

                function toggleShowBtn(valid) {
                    if (valid) {
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
                        $inputPassword.attr('autocomplete', 'new-password');
                        $btnShow.text('Show');
                    }
                }

                function toggleSubmit(valid) {
                    if (valid) {
                        $btnSubmit.removeClass('bg-opacity-30');
                        $btnSubmit.prop('disabled', false);
                    } else {
                        $btnSubmit.addClass('bg-opacity-30');
                        $btnSubmit.prop('disabled', true);
                    }
                }

                function toggleNext(valid) {
                    if (valid) {
                        $btnNext.removeClass('bg-opacity-30');
                        $btnNext.prop('disabled', false);
                    } else {
                        $btnNext.addClass('bg-opacity-30');
                        $btnNext.prop('disabled', true);
                    }
                }

                function isValidEmail() {
                    const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
                    return re.test($inputEmail.val());
                }

                function isValidName() {
                    return $inputName.val().length > 0;
                }

                function isValidPassword() {
                    return $inputPassword.val().length >= 8;
                }

                function isValidDate() {
                    const maxValidDate = new Date();
                    maxValidDate.setFullYear(maxValidDate.getFullYear() - 5);

                    const selectedDate = new Date($selectYear.val(), $selectMonth.val() - 1, $selectDate.val());

                    return selectedDate <= maxValidDate;
                }

                function validate() {
                    switch (step) {
                        case 1:
                            toggleNext(isValidEmail() && isValidName() && isValidPassword());
                            break;
                        case 2:
                            toggleNext(isValidDate());
                            break;
                        case 3:
                            toggleSubmit(isValidEmail() && isValidName() && isValidPassword() && isValidDate());
                            break;
                        default:
                            break;
                    }
                    return isValidEmail() && isValidName() && isValidPassword() && isValidDate();
                }

                const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

                months.forEach((element, index) => {
                    $selectMonth.append(`<option title=${element} value=${index + 1}>${element}</option>`);
                });

                $selectMonth.val(new Date().getMonth() + 1);

                for (let i = new Date().getFullYear(); i >= new Date().getFullYear() - 120; --i) {
                    $selectYear.append(`<option title=${i} value=${i}>${i}</option>`);
                }

                function renderDate() {
                    const d = new Date($selectYear.val(), $selectMonth.val(), 0);

                    let oldDate = $selectDate.val() || new Date().getDate();

                    if (oldDate > d.getDate()) {
                        oldDate = d.getDate();
                    }

                    $selectDate.empty();

                    for (let i = 1; i <= d.getDate(); ++i) {
                        $selectDate.append(`<option title=${i} value=${i}>${i}</option>`);
                    }

                    $selectDate.val(oldDate);
                }

                renderDate();
                toggleShowBtn();

                $inputEmail.on('input', function () {
                    validate();

                    [$btnHelpGmail, $btnHelpHotmail, $btnHelpOutlook, $btnHelpYahoo].forEach((element) => {
                        const splitString = $(this).val().split('@');
                        if (splitString.length < 2) return;
                        if (element.text().trim().split('.')[0].includes('@' + splitString[1].split('.')[0])) {
                            element.removeClass('hidden');
                        } else {
                            element.addClass('hidden');
                        }
                    });
                });

                $inputName.on('input', function () {
                    validate();
                });

                $inputPassword.on('input', function () {
                    toggleShowBtn($(this).val().length > 0);
                    validate();
                });

                $selectMonth.change(function () {
                    renderDate();
                    validate();
                });

                $selectDate.change(function () {
                    validate();
                });

                $selectYear.change(function () {
                    renderDate();
                    validate();
                });

                $btnShow.click(togglePasswordVisibility);

                $btnNext.click(function () {
                    step++;

                    toggleFields();
                });

                $btnBack.click(function () {
                    if (step === 1) {
                        window.history.back();
                        return;
                    }

                    step--;
                    ph

                    toggleFields();
                });

                $btnHelpGmail.add($btnHelpHotmail).add($btnHelpYahoo).add($btnHelpOutlook).click(function () {
                    const withoutAt = $inputEmail.val().split('@')[0];
                    $inputEmail.val(withoutAt + $(this).text().trim()).trigger('input');
                });

                $(window).keydown(function (event) {
                    if (event.keyCode === 13) {
                        $('#form_signup').submit();
                    }
                });

                $('#form_signup').submit(function (event) {
                    if (!validate()) {
                        event.preventDefault();
                        return;
                    }

                    const d = new Date($selectYear.val(), $selectMonth.val() - 1, $selectDate.val());
                    $inputBirthday.val(d.toISOString().split('T')[0]);
                });
            });
        </script>
    @endpush
</x-base-layout>
