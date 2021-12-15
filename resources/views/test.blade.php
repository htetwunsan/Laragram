<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch">
        {{--        <div class="overflow-hidden flex flex-shrink-0 flex-nowrap flex-row items-center mx-4 mt-4 mb-6">--}}
        {{--            <div class="flex-shrink-0 flex flex-col items-stretch mr-7">--}}
        {{--                <form id="form_update_profile_image" action="{{ route('auth.edit') }}" method="POST"--}}
        {{--                      enctype="multipart/form-data">--}}
        {{--                    @csrf--}}
        {{--                    @method('PATCH')--}}
        {{--                    <input id="input_profile_image" class="hidden" type="file" accept="image/*"--}}
        {{--                           name="profile_image"/>--}}
        {{--                </form>--}}
        {{--                <button type="button" title="Add a profile photo" style="width: 77px; height: 77px"--}}
        {{--                        onclick="$('#input_profile_image').trigger('click')">--}}
        {{--                    <x-profile-image class="w-full h-full rounded-full" title="Add a profile photo"--}}
        {{--                                     alt="Add a profile photo">--}}
        {{--                        {{ asset('images/default-profile.jpeg') }}--}}
        {{--                    </x-profile-image>--}}
        {{--                </button>--}}
        {{--            </div>--}}
        {{--            <div class="flex flex-row items-center mb-3 w-full overflow-hidden">--}}
        {{--                <h2--}}
        {{--                    class="text-triple38 font-light leading-8 -mt-1.5 -mb-1.5 whitespace-nowrap overflow-hidden overflow-ellipsis"--}}
        {{--                    style="font-size: 1.75rem; min-width: 0">clarabelle.hirthe171277sadasdasdasdasdas</h2>--}}

        {{--                <div class="flex flex-shrink-0 items-center justify-center"--}}
        {{--                     style="margin-left: 5px">--}}
        {{--                    <button class="p-2" type="button">--}}
        {{--                        <svg aria-label="Options" class="_8-yf5 " color="#262626" fill="#262626"--}}
        {{--                             height="32" role="img" viewBox="0 0 24 24" width="32">--}}
        {{--                            <circle cx="12" cy="12" r="1.5"></circle>--}}
        {{--                            <circle cx="6.5" cy="12" r="1.5"></circle>--}}
        {{--                            <circle cx="17.5" cy="12" r="1.5"></circle>--}}
        {{--                        </svg>--}}
        {{--                    </button>--}}
        {{--                </div>--}}
        {{--            </div>--}}
        {{--        </div>--}}

        <header class="flex flex-shrink-0 flex-row items-stretch mx-4 mt-4 mb-6">
            <div class="flex-shrink-0 flex flex-col items-stretch mr-7">
                <form id="form_update_profile_image" action="{{ route('auth.edit') }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input id="input_profile_image" class="hidden" type="file" accept="image/*"
                           name="profile_image"/>
                </form>
                <button type="button" title="Add a profile photo" style="width: 77px; height: 77px"
                        onclick="$('#input_profile_image').trigger('click')">
                    <x-profile-image class="w-full h-full rounded-full" title="Add a profile photo"
                                     alt="Add a profile photo">
                        {{ asset('images/default-profile.jpeg') }}
                    </x-profile-image>
                </button>
            </div>
            <section class="flex flex-1 flex-col items-stretch overflow-hidden">
                <div class="flex flex-row flex-shrink items-center mb-3">
                    <h2
                        class="text-triple38 font-light leading-8 -mt-1.5 -mb-1.5 whitespace-nowrap overflow-hidden overflow-ellipsis"
                        style="font-size: 1.75rem; min-width: 0">clarabelle.hirthe171277</h2>

                    <div class="flex flex-shrink-0 items-center justify-center"
                         style="margin-left: 5px">
                        <button class="p-2" type="button">
                            <svg aria-label="Options" class="_8-yf5 " color="#262626" fill="#262626"
                                 height="32" role="img" viewBox="0 0 24 24" width="32">
                                <circle cx="12" cy="12" r="1.5"></circle>
                                <circle cx="6.5" cy="12" r="1.5"></circle>
                                <circle cx="17.5" cy="12" r="1.5"></circle>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="flex flex-col items-stretch" style="max-width: 250px; tab-index: 0">
                    <a class="text-sm text-triple38 text-center font-semibold leading-18px border border-solid border-triple219 rounded"
                       href="{{ route('auth.edit') }}"
                       style="padding:5px 9px;">Edit
                        Profile</a>
                </div>
            </section>
        </header>
    </section>
</x-base-layout>
