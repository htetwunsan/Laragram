<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <!--header-->
        <x-app-top-navigation>
            <x-slot name="left">
                <div class="flex flex-row w-8">
                    <x-back-button>{{ route('user.show', ['user' => auth()->user()->username]) }}</x-back-button>
                </div>
            </x-slot>
            <h1 class="flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">
                Change Password</h1>

            <x-slot name="right">
                <div class="flex flex-row w-8"></div>
            </x-slot>
        </x-app-top-navigation>

        <!--footer-->
        <x-app-bottom-navigation>profile</x-app-bottom-navigation>

        <x-field-errors/>

    @component('components.success', ['message' => session('success')])
    @endcomponent

    <!--main-content-->
        <main class="flex-grow flex flex-col items-stretch">
            <div class="flex-grow flex flex-col items-stretch border border-solid border-triple219">
                <article class="flex flex-col items-stretch">
                    <div class="flex flex-row items-stretch justify-start mt-5">
                        <div class="flex flex-col items-stretch mt-0.5 mx-5">
                            <div class="flex-shrink-0 flex flex-col items-stretch">
                                <div style="width: 38px; height: 38px">
                                    <img class="w-full h-full rounded-full" title="Profile photo"
                                         alt="Profile photo"
                                         src="@if(! is_null(auth()->user()->profile_image))/storage/{{ auth()->user()->profile_image }}@else{{ asset('images/default-profile.jpeg') }}@endif"/>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col items-stretch mr-5">
                            <h1 class="text-2xl text-triple38 text-left font-normal mb-0.5"
                                style="line-height: 38px">{{ auth()->user()->username }}</h1>
                        </div>
                    </div>

                    <form class="flex flex-col items-stretch mb-4 mt-8" action="{{ route('password.change') }}"
                          method="POST">@csrf

                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_old_password">Old Password</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="true"
                                        id="input_old_password"
                                        autocomplete="current-password"
                                        type="password" name="old_password"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_new_password">New Password</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="true"
                                        id="input_new_password"
                                        autocomplete="new-password"
                                        type="password" name="new_password"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_confirm_new_password">Confirm New Password</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="true"
                                        id="input_confirm_new_password"
                                        type="password" name="new_password_confirmation"
                                    />
                                </div>
                            </div>
                        </div>

                        <!--Submit-->
                        <div class="flex flex-col items-stretch mb-2">
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-row items-center justify-between mt-4">
                                    <button
                                        id="btn_submit"
                                        class="bg-fb_blue bg-opacity-30 text-sm text-white text-center font-semibold leading-18px border border-solid border-transparent rounded px-2.5 py-1.5"
                                        type="submit" disabled>
                                        Change Password
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </main>
    </section>
    @push('scripts')
        <script>
            $(document).ready(function () {
                $inputOldPassword = $('#input_old_password');
                $inputNewPassword = $('#input_new_password');
                $inputConfirmNewPassword = $('#input_confirm_new_password');

                $btnSubmit = $('#btn_submit');

                function toggleSubmit(valid) {
                    if (valid) {
                        $btnSubmit.removeClass('bg-opacity-30');
                        $btnSubmit.prop('disabled', false);
                    } else {
                        $btnSubmit.addClass('bg-opacity-30');
                        $btnSubmit.prop('disabled', true);
                    }
                }

                function isValidOldPassword() {
                    return $inputOldPassword.val().length >= 8;
                }

                function isValidNewPassword() {
                    return $inputNewPassword.val().length >= 8;
                }

                function isValidConfirmNewPassword() {
                    return $inputConfirmNewPassword.val().length >= 8;
                }


                function validate() {
                    const validAll = isValidOldPassword() && isValidNewPassword() && isValidConfirmNewPassword();
                    toggleSubmit(validAll);
                    return validAll;
                }

                $inputOldPassword.add($inputNewPassword).add($inputConfirmNewPassword).on('input', validate);
            });
        </script>
    @endpush
</x-base-layout>
