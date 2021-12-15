<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar">

        <!--header-->
        <nav class="flex flex-col items-stretch order-first z-50">
            <div class="flex flex-col items-stretch h-11">
                <header
                    class="max-w-screen-sm mx-auto fixed top-0 left-0 right-0 flex flex-col items-stretch border-b border-solid border-triple219 z-10 bg-white">
                    <div class="flex flex-row items-center justify-between h-11 px-4">
                        <div class="flex items-center justify-start w-8">
                        </div>

                        <h1 class="font-cookie font-semibold text-3xl text-triple38 tracking-wider">{{ config('app.name', 'Laragram') }}</h1>


                        <div class="flex items-center justify-end w-8">
                        </div>
                    </div>
                </header>
            </div>
        </nav>

        <!--footer-->
        <nav class="flex flex-col items-stretch order-last z-50">
            <div class="flex flex-col items-stretch h-11">
                <header
                    class="max-w-screen-sm mx-auto fixed bottom-0 left-0 right-0 flex flex-col items-stretch border-t border-solid border-triple219 z-10 bg-white">
                    <a href="{{ route('login') }}">
                        <div class="flex flex-row items-center justify-between h-11 px-4">
                            <div class="flex items-center justify-start w-8">
                            </div>

                            <h1 class="text-sm text-triple38 text-center font-semibold leading-18px">Back To Login</h1>


                            <div class="flex items-center justify-end w-8">
                            </div>
                        </div>
                    </a>
                </header>
            </div>
        </nav>

        <main class="bg-white flex-grow flex flex-col items-stretch">
            <div class="flex-grow flex items-center justify-center mx-3">
                <div class="flex flex-col items-stretch">
                    <div class="flex items-center justify-center mt-6 mb-4">
                        <span class="block lock-small"></span>
                    </div>

                    <div class="flex items-center justify-center mx-11 mb-4">
                        <h4 class="text-base text-triple38 text-center font-semibold leading-6 -my-1.5">
                            Trouble Logging In?
                        </h4>
                    </div>

                    <div class="flex items-center justify-center mx-11 mb-4">
                        <div class="text-sm text-triple142 text-center leading-18px"
                             style="margin-top: -3px; margin-bottom: -4px">
                            Enter your email, phone, or username and we'll send you a link to get back into your
                            account.
                        </div>
                    </div>

                    <div class="flex items-center justify-center mx-11 mb-4">
                        <form id="form_forgot_password"
                              class="flex-grow flex flex-col items-stretch"
                              method="POST"
                              action="{{ route('password.email') }}">@csrf
                            <div
                                class="flex flex-col items-stretch mb-4">
                                <label class="bg-triple250 flex items-center justify-center h-10 pr-2 relative
                                border border-solid border-triple219 rounded-md overflow-hidden">
                                    <input
                                        id="input_email"
                                        class="floating-input2 bg-transparent text-xs leading-18px w-full border-none placeholder-transparent"
                                        type="email"
                                        name="email"
                                        placeholder="Email"
                                        required autofocus/>
                                    <span
                                        class="floating-label2 h-9 leading-9 absolute left-0 right-0 text-xs text-triple199
                                        select-none pointer-events-none"
                                        style="margin: 3px 9px;">Email Address</span>
                                </label>
                            </div>

                            <div class="flex flex-col items-stretch">
                                <button
                                    id="btn_submit"
                                    class="bg-fb_blue bg-opacity-30 text-sm text-white text-center font-semibold leading-18px border border-transparent rounded"
                                    type="submit"
                                    disabled
                                    style="padding: 5px 9px;">
                                    Send Login Link
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="flex flex-row items-center mx-11 my-4">
                        <hr class="flex-grow"/>
                        <div class="mx-4"><span
                                class="text-sm text-triple142 leading-normal font-semibold">OR</span>
                        </div>
                        <hr class="flex-grow"/>
                    </div>

                    <div class="flex flex-col items-stretch mb-4">
                        <a class="text-sm text-triple38 text-center font-semibold leading-18px"
                           href="{{ route('signup') }}">
                            Create New Account
                        </a>
                    </div>
                </div>
            </div>

            <x-field-errors/>
        </main>
    </section>
    @once
        @push('scripts')
            <script>
                $(document).ready(function () {
                    const $inputEmail = $('#input_email');
                    const $btnSubmit = $('#btn_submit');

                    $inputEmail.on('input', function () {
                        if ($(this).val().length > 0) {
                            $btnSubmit.removeClass('bg-opacity-30');
                            $btnSubmit.attr('disabled', false);
                        } else {
                            $btnSubmit.addClass('bg-opacity-30');
                            $btnSubmit.attr('disabled', true);
                        }
                    });
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
