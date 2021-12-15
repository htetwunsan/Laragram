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
                Edit Profile</h1>

            <x-slot name="right">
                <div class="w-8"></div>
            </x-slot>
        </x-app-top-navigation>

        <!--footer-->
        <x-app-bottom-navigation>profile</x-app-bottom-navigation>

        <x-field-errors/>

        <!--main-content-->
        <main class="flex-grow flex flex-col items-stretch">
            <div class="flex flex-col items-stretch border border-solid border-triple219">
                <article class="flex flex-col items-stretch">
                    <div class="flex flex-row items-stretch justify-start mt-5">
                        <div class="flex flex-col items-stretch mt-0.5 mx-5">
                            <div class="flex-shrink-0 flex flex-col items-stretch">
                                <form id="form_update_profile_image" action="{{ route('auth.update.profile-image') }}"
                                      method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    @method('PATCH')
                                    <input id="input_profile_image" class="hidden" type="file" accept="image/*"
                                           name="profile_image"/>
                                </form>
                                <button type="button" title="Add a profile photo"
                                        style="width: 38px; height: 38px"
                                        onclick="$('#input_profile_image').trigger('click')">
                                    <x-profile-image class="w-full h-full rounded-full" title="Add a profile photo"
                                                     alt="Add a profile photo">
                                        {{ auth()->user()->profile_image }}
                                    </x-profile-image>
                                </button>
                            </div>
                            @push('scripts')
                                <script>
                                    $(document).ready(function () {
                                        $inputProfileImage = $('#input_profile_image');
                                        $form = $('#form_update_profile_image');

                                        $inputProfileImage.change(function () {
                                            $form.submit();
                                        });
                                    });
                                </script>
                            @endpush
                        </div>
                        <div class="flex flex-col items-stretch mr-5">
                            <h1 class="text-xl text-triple38 text-left font-normal mb-0.5"
                                style="line-height: 22px">{{ auth()->user()->username }}</h1>
                            <button class="text-sm text-fb_blue text-left font-semibold leading-18px" type="button"
                                    onclick="$('#input_profile_image').trigger('click')">
                                Change Profile Photo
                            </button>
                        </div>
                    </div>

                    <form class="flex flex-col items-stretch my-4" action="{{ route('auth.edit') }}"
                          method="POST">@csrf @method('put')

                    <!--Name-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_name">Name</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="false"
                                        id="input_name"
                                        placeholder="Name" type="text" name="name"
                                        value="{{ auth()->user()->name }}"/>
                                    <div class="flex flex-col items-stretch justify-start mt-4 mb-2">
                                        <div class="flex flex-col items-stretch justify-start mb-4">
                                            <div class="text-xs text-triple142 font-normal leading-4 -my-1">Help
                                                people discover your account by using the name you're known by: either
                                                your full name, nickname, or business name.
                                            </div>
                                        </div>
                                        <div class="text-xs text-triple142 font-normal leading-4 -my-1">You can
                                            only change your name twice within 14 days.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Username-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_username">Username</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="false"
                                        id="input_username"
                                        placeholder="Username" type="text" name="username"
                                        value="{{ auth()->user()->username }}"/>
                                    <div class="flex flex-col items-stretch justify-start mt-4 mb-2">
                                        <div class="flex flex-col items-stretch justify-start mb-4">
                                            <div class="text-xs text-triple142 font-normal leading-4 -my-1">In most
                                                cases, you'll be able to change your username back to freehostingacc1
                                                for another 14 days.
                                                <a aria-label="Learn more about changing your username"
                                                   class="inline-block text-xs text-fb_blue font-normal leading-4"
                                                   href="#"
                                                >Learn More</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Website-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_website">Website</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="false"
                                        id="input_website"
                                        placeholder="Website" type="text" name="website"
                                        value="{{ auth()->user()->website }}"/>
                                </div>
                            </div>
                        </div>
                        <!--Bio-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_bio">Bio</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <textarea id="input_bio"
                                          class="text-base text-triple38 font-normal tracking-normal leading-18px break-words h-15 px-2.5 py-1.5 border border-solid border-triple219 rounded"
                                          name="bio">{{ auth()->user()->bio }}</textarea>
                            </div>
                        </div>
                        <!--Personal Information-->
                        <div class="flex flex-col items-stretch mb-2">
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <div class="flex flex-col items-stretch justify-start mt-4 mb-2">
                                        <div class="flex flex-col items-stretch justify-start mb-1">
                                            <h2 class="text-sm text-triple142 font-semibold leading-18px">Personal
                                                Information
                                            </h2>
                                        </div>
                                        <div class="text-xs text-triple142 font-normal leading-4 -my-1">Provide your
                                            personal information, even if the account is used for a business, a pet or
                                            something else. This won't be a part of your public profile.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Email-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_email">Email</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="false"
                                        id="input_email"
                                        placeholder="Email" type="email" name="email"
                                        value="{{ auth()->user()->email }}"/>
                                </div>
                            </div>
                        </div>
                        <!--Phone-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px"
                                       for="input_phone">Phone</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="flex flex-col items-stretch">
                                    <input
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"
                                        aria-required="false"
                                        id="input_phone"
                                        placeholder="Phone" type="text" name="phone"
                                        value="{{ auth()->user()->phone }}"/>
                                </div>
                            </div>
                        </div>
                        <!--Gender-->
                        <div class="flex flex-col items-stretch mb-2">
                            <aside class="px-5 mt-1.5" style="flex-basis: 25px">
                                <label class="text-base text-triple38 font-semibold leading-18px">Gender</label>
                            </aside>
                            <div class="flex flex-col items-stretch justify-start px-5">
                                <div class="max-w-screen-sm flex flex-row items-center justify-between">
                                    <label
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8">
                                        <input class="mx-1 focus:ring-0" type="radio" name="gender"
                                               value="prefer not to say"
                                               @if(auth()->user()->gender == 'prefer not to say') checked @endif>Prefer
                                        not to say
                                    </label>
                                    <label
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 mx-1">
                                        <input class="mx-1 focus:ring-0" type="radio" name="gender" value="male"
                                               @if(auth()->user()->gender == 'male') checked @endif>Male
                                    </label>
                                    <label
                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 mx-1">
                                        <input class="mx-1 focus:ring-0" type="radio" name="gender" value="female"
                                               @if(auth()->user()->gender == 'female') checked @endif>Female
                                    </label>
                                    {{--                                    <input--}}
                                    {{--                                        class="text-base text-triple38 font-normal tracking-normal leading-18px h-8 border border-solid border-triple219 rounded px-2.5"--}}
                                    {{--                                        aria-required="false"--}}
                                    {{--                                        id="input_gender"--}}
                                    {{--                                        placeholder="Gender" type="text" name="gender"--}}
                                    {{--                                        value="prefer not to say"/>--}}
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
                                        Submit
                                    </button>
                                    <button
                                        id="btn_disable_my_account"
                                        class="text-sm text-fb_blue text-center font-semibold leading-18px overflow-ellipsis mx-1"
                                        type="button">
                                        Temporarily
                                        disable my
                                        account
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
                $inputName = $('#input_name');
                $inputUsername = $('#input_username');
                $inputWebsite = $('#input_website');
                $inputBio = $('#input_bio');
                $inputEmail = $('#input_email');
                $inputPhone = $('#input_phone');

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

                function isValidName() {
                    return $inputName.val().length > 0;
                }

                function isValidUsername() {
                    return $inputUsername.val().length > 0;
                }

                function isValidWebsite() {
                    return true;
                }

                function isValidBio() {
                    return true;
                }

                function isValidEmail() {
                    const re = /^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i;
                    return re.test($inputEmail.val());
                }

                function isValidPhone() {
                    return true;
                }

                function validate() {
                    const validAll = isValidName() && isValidUsername() && isValidWebsite() && isValidBio() && isValidEmail() && isValidPhone();
                    toggleSubmit(validAll);
                    return validAll;
                }

                validate();

                $inputName.add($inputUsername).add($inputWebsite).add($inputBio).add($inputEmail).add($inputPhone).on('input', validate);

            });
        </script>
    @endpush
</x-base-layout>
