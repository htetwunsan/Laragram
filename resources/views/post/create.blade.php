<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar">
        <form id="form_post_create" class="flex flex-col items-stretch" enctype="multipart/form-data"
            action="{{ route('post.store') }}" method="POST">
            @csrf
            <input id="input_post_image" class="hidden" type="file" accept="image/*" name="post_image" />
            <input id="input_alt_text" type="hidden" name="alternate_text" value="">

            <x-app-top-navigation>
                <x-slot name="left">
                    <div class="flex flex-row items-stretch justify-start w-8">
                        <button id="btn_back" class="flex flex-row" tabindex="0" type="button">
                            <span class="block transform -rotate-90">
                                <svg aria-label="Back" class="text-triple38 fill-current w-6 h-6" role="img"
                                    viewBox="0 0 48 48">
                                    <path
                                        d="M40 33.5c-.4 0-.8-.1-1.1-.4L24 18.1l-14.9 15c-.6.6-1.5.6-2.1 0s-.6-1.5 0-2.1l16-16c.6-.6 1.5-.6 2.1 0l16 16c.6.6.6 1.5 0 2.1-.3.3-.7.4-1.1.4z">

                                    </path>
                                </svg>
                            </span>
                        </button>
                    </div>
                </x-slot>

                <h1
                    class="first_step flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">
                    New Post</h1>
                <h1
                    class="second_step flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis hidden">
                    Advanced Settings</h1>
                <h1
                    class="third_step flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis hidden">
                    Alt Text</h1>

                <x-slot name="right">
                    <div class="first_step flex flex-row items-stretch justify-end">
                        <button id="btn_submit"
                            class="text-base text-fb_blue text-opacity-30 text-center font-semibold leading-18px"
                            type="button" disabled>
                            Share
                        </button>
                    </div>

                    <div class="second_step w-8 hidden"></div>

                    <div class="third_step flex flex-row items-stretch justify-end hidden">
                        <button id="btn_save" class="text-base text-fb_blue text-center font-semibold leading-18px"
                            type="button">
                            Save
                        </button>
                    </div>
                </x-slot>
            </x-app-top-navigation>

            <main class="bg-triple250 bg-opacity-65 flex-grow flex flex-col items-stretch" role="main">
                <div class="first_step flex flex-col items-stretch">
                    <div class="bg-white flex border border-b border-triple219 p-4 z-50">
                        <span class="block rounded-full -mt-0.5 mr-1.5" style="width: 30px; height: 30px">
                            <x-profile-image class="w-full h-full rounded-full" alt="">
                                {{ auth()->user()->profile_image }}
                            </x-profile-image>
                        </span>

                        <div class="flex-grow flex flex-col items-stretch">
                            <textarea id="textarea_caption"
                                class="h-12 text-sm text-triple38 break-words leading-18px resize-none border-none focus:outline-none focus:ring-0 p-0.5"
                                name="caption" aria-label="Write a caption…" placeholder="Write a caption…" rows="2"
                                autocomplete="off" autocorrect="off"></textarea>
                        </div>

                        <div class="flex items-center justify-center w-12 h-12">
                            <button id="btn_add_image"
                                class="w-full h-full text-sm text-triple142 text-center font-normal leading-18px border border-dashed border-triple142 rounded"
                                type="button">
                                Add Image
                            </button>
                            <img id="img_preview" class="w-full h-full hidden" src="" alt="preview image">
                        </div>
                    </div>

                    <div class="mt-3 ml-4">
                        <button id="btn_advanced_settings" class="text-xs text-triple142 leading-4 -mt-0.5"
                            style="margin-bottom: -3px" type="button">
                            Advanced Settings
                        </button>
                    </div>
                </div>

                <div class="second_step flex flex-col items-stretch mt-6 hidden">
                    <div class="bg-white px-4">
                        <h4 class="text-base text-triple38 font-semibold leading-6 -my-1.5">Accessibility</h4>
                    </div>

                    <button id="btn_write_alt_text" class="h-11 bg-white border border-t border-b border-triple219 mt-3"
                        type="button">
                        <div class="flex items-center justify-between mx-4">
                            <div class="text-base text-triple38 text-center leading-6 -my-1.5">
                                Write Alt Text
                            </div>
                            <div class="right-chevron"></div>
                        </div>
                    </button>

                    <div class="px-4 pt-3">
                        <div class="text-xs text-triple142 leading-4 -mt-0.5" style="margin-bottom: -3px">Alt text
                            describes your
                            photos for people with visual impairments. Alt text will be automatically created for your
                            photos or you can choose to write your own.
                        </div>
                    </div>
                </div>

                <div class="third_step flex p-4 hidden">
                    <div class="flex items-center justify-center w-12 h-12 mr-4">
                        <img class="w-full h-full" src="{{ asset('images/default-profile.jpeg') }}" alt="">
                    </div>

                    <div class="flex-grow flex flex-col items-stretch">
                        <textarea id="textarea_alt_text"
                            class="h-12 text-sm text-triple38 break-words leading-18px resize-none border-none focus:outline-none focus:ring-0 p-0.5"
                            aria-label="Write alt text…" placeholder="Write alt text…" rows="1" autocomplete="off"
                            autocorrect="off"></textarea>
                    </div>
                </div>
            </main>
        </form>
        @component('components.field-errors')@endcomponent
    </section>
    <div id="div_overlay" class="bg-black bg-opacity-65 fixed inset-0 z-10 hidden">
    </div>
    @once
        @push('scripts')
            <script>
                $(document).ready(function() {
                    const $btnBack = $('#btn_back');

                    const $form = $('#form_post_create');
                    const $inputPostImage = $('#input_post_image');
                    const $textAreaCaption = $('#textarea_caption');
                    const $inputAltText = $('#input_alt_text');
                    const $textAreaAltText = $('#textarea_alt_text');

                    const $btnAddImage = $('#btn_add_image');
                    const $btnAdvancedSettings = $('#btn_advanced_settings');
                    const $btnWriteAltText = $('#btn_write_alt_text');
                    const $btnSubmit = $('#btn_submit');
                    const $btnSave = $('#btn_save');

                    const $imgPreview = $('#img_preview');
                    const $divOverlay = $('#div_overlay');

                    let step = 1;

                    function toggleForm() {
                        switch (step) {
                            case 0:
                                window.location.href = '{{ route('post.index') }}';
                                break;
                            case 1:
                                $form.find('.first_step').removeClass('hidden');
                                $form.find('.second_step').addClass('hidden');
                                $form.find('.third_step').addClass('hidden');
                                break;
                            case 2:
                                $form.find('.second_step').removeClass('hidden');
                                $form.find('.first_step').addClass('hidden');
                                $form.find('.third_step').addClass('hidden');
                                break;
                            case 3:
                                $textAreaAltText.val($inputAltText.val());
                                $form.find('.third_step').removeClass('hidden');
                                $form.find('.first_step').addClass('hidden');
                                $form.find('.second_step').addClass('hidden');
                                break;
                            default:
                                step = 1;
                                toggleForm();
                                break;
                        }
                    }

                    function validatePostImage() {
                        const valid = $inputPostImage[0].files.length > 0;
                        toggleSubmit(valid);
                        return valid;
                    }

                    function toggleSubmit(valid) {
                        if (valid) {
                            $btnSubmit.prop('disabled', false);
                            $btnSubmit.removeClass('text-opacity-30');
                        } else {
                            $btnSubmit.prop('disabled', true);
                            $btnSubmit.addClass('text-opacity-30');
                        }
                    }

                    $btnAddImage.add($imgPreview).click(function() {
                        $inputPostImage.trigger('click');
                    });

                    $inputPostImage.change(function(event) {
                        if (validatePostImage()) {
                            const src = URL.createObjectURL(event.target.files[0]);
                            $imgPreview.attr('src', src);
                            $imgPreview.removeClass('hidden');
                            $btnAddImage.addClass('hidden');
                        }
                    });

                    $btnBack.click(function() {
                        step--;
                        toggleForm();
                    });

                    $btnSave.click(function() {
                        $inputAltText.val($textAreaAltText.val());
                        step--;
                        toggleForm();
                    });

                    $textAreaCaption.focusin(function() {
                        $divOverlay.removeClass('hidden');
                    });
                    $textAreaCaption.focusout(function() {
                        $divOverlay.addClass('hidden');
                    });

                    $btnAdvancedSettings.add($btnWriteAltText).click(function() {
                        step++;
                        toggleForm();
                    });

                    $btnSubmit.click(function() {
                        $form.submit();
                    });
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
