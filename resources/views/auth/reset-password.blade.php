<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <!--header-->
        <nav class="flex flex-col items-stretch order-first z-50">
            <div class="flex flex-col items-stretch h-11">
                <header
                    class="max-w-screen-sm mx-auto fixed top-0 left-0 right-0 flex flex-col items-stretch border-b border-solid border-triple219 z-10 bg-white">
                    <div class="flex flex-row items-center justify-between h-11 px-4">
                        <div class="flex items-center justify-start">
                            <h1 class="font-cookie font-semibold text-3xl text-triple38 tracking-wider">
                                {{ config('app.name', 'Laragram') }}
                            </h1>
                        </div>

                        <div class="flex items-center justify-end">
                            <a class="text-sm text-fb_blue text-center font-semibold leading-18px"
                               href="{{ route('login') }}">
                                Log In
                            </a>
                        </div>
                    </div>
                </header>
            </div>
        </nav>

        <main class="bg-white flex-grow flex flex-col items-stretch">
            <div class="flex-grow flex flex-col items-stretch mt-12">
                <div class="flex items-center justify-center p-8 pb-0">
                    <span class="text-base text-triple38 text-center font-bold leading-5 mb-2">
                            Create A Strong Password
                    </span>
                </div>

                <div class="flex items-center justify-center"
                     style="padding: 0 53px 26px 53px;">
                    <span class="text-sm text-triple142 text-center leading-18px">
                            Your password must be more than six characters and include a combination of numbers, letters and special characters (!$@%).
                    </span>
                </div>

                <form class="flex flex-col items-stretch"
                      method="POST"
                      action="{{ route('password.update') }}">@csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">
                    <input type="hidden" name="email" value="{{ old('email', $request->email) }}"/>

                    <div class="flex items-center justify-start"
                         style="padding: 0 53px;">
                        <span id="span_password_info"
                              class="text-xs text-triple142 text-left leading-4 invisible">
                                Password must be at least 6 characters.
                        </span>
                    </div>

                    <div style="padding: 0 53px 10px 53px;">
                        <label class="bg-triple250 flex flex-col items-stretch border border-triple219 rounded">
                            <input
                                id="input_password"
                                class="bg-triple250 text-sm text-triple38 font-normal leading-18px m-1 px-0.5 py-px border-none focus:ring-0 focus:outline-none"
                                type="password"
                                name="password"
                                style="height: 38px"
                                placeholder="New password">
                        </label>
                    </div>

                    <div class="flex items-center justify-start"
                         style="padding: 0 53px;">
                        <span id="span_password_confirmation_info"
                              class="text-xs text-triple142 text-left leading-4 invisible">
                                Password don't match.
                        </span>
                    </div>

                    <div style="padding: 0 53px 10px 53px;">
                        <label class="bg-triple250 flex flex-col items-stretch border border-triple219 rounded">
                            <input
                                id="input_password_confirmation"
                                class="bg-triple250 text-sm text-triple38 font-normal leading-18px m-1 px-0.5 py-px border-none focus:ring-0 focus:outline-none"
                                type="password"
                                name="password_confirmation"
                                style="height: 38px"
                                placeholder="New password, again">
                        </label>
                    </div>

                    <div class="flex flex-col items-stretch mt-5"
                         style="padding: 0 52px 20px 52px;">
                        <button
                            id="btn_submit"
                            class="bg-fb_blue bg-opacity-30 h-11 text-sm text-white text-center font-bold leading-18px rounded"
                            disabled
                            type="submit">
                            Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </main>
        <x-field-errors/>
    </section>
    @once
        @push('scripts')
            <script>
                $(document).ready(function () {
                    const $inputPassword = $('#input_password');
                    const $spanPasswordInfo = $('#span_password_info');
                    const $inputPasswordConfirmation = $('#input_password_confirmation');
                    const $spanPasswordConfirmationInfo = $('#span_password_confirmation_info');
                    const $btnSubmit = $('#btn_submit');

                    function validPassword() {
                        const len = $inputPassword.val().length;
                        if (len > 0 && len < 6) {
                            $spanPasswordInfo.removeClass('invisible');
                        } else {
                            $spanPasswordInfo.addClass('invisible');
                        }

                        return len >= 6;
                    }

                    function validPasswordConfirmation() {
                        const valid = $inputPasswordConfirmation.val() === $inputPassword.val();
                        if ($inputPasswordConfirmation.val().length > 0 && !valid) {
                            $spanPasswordConfirmationInfo.removeClass('invisible');
                        } else {
                            $spanPasswordConfirmationInfo.addClass('invisible');
                        }

                        return valid;
                    }

                    function validate() {
                        if (validPassword() && validPasswordConfirmation()) {
                            $btnSubmit.removeClass('bg-opacity-30');
                            $btnSubmit.attr('disabled', false);
                        } else {
                            $btnSubmit.addClass('bg-opacity-30');
                            $btnSubmit.attr('disabled', true);
                        }
                    }

                    $inputPassword.on('input', function () {
                        validate();
                    });

                    $inputPasswordConfirmation.on('input', function () {
                        validate();
                    });
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
