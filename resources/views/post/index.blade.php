<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar">
        <x-app-top-navigation>
            <x-slot name="left">
                <div class="flex flex-row items-stretch justify-start w-8">
                    <button type="button" onclick="$('#input_story_image').trigger('click');">
                        <svg aria-label="New Story" class="text-triple38 fill-current w-6 h-6"
                             role="img" viewBox="0 0 48 48">
                            <path clip-rule="evenodd"
                                  d="M38.5 46h-29c-5 0-9-4-9-9V17c0-5 4-9 9-9h1.1c1.1 0 2.2-.6 2.7-1.7l.5-1c1-2 3.1-3.3 5.4-3.3h9.6c2.3 0 4.4 1.3 5.4 3.3l.5 1c.5 1 1.5 1.7 2.7 1.7h1.1c5 0 9 4 9 9v20c0 5-4 9-9 9zm6-29c0-3.3-2.7-6-6-6h-1.1C35.1 11 33 9.7 32 7.7l-.5-1C31 5.6 29.9 5 28.8 5h-9.6c-1.1 0-2.2.6-2.7 1.7l-.5 1c-1 2-3.1 3.3-5.4 3.3H9.5c-3.3 0-6 2.7-6 6v20c0 3.3 2.7 6 6 6h29c3.3 0 6-2.7 6-6V17zM24 38c-6.4 0-11.5-5.1-11.5-11.5S17.6 15 24 15s11.5 5.1 11.5 11.5S30.4 38 24 38zm0-20c-4.7 0-8.5 3.8-8.5 8.5S19.3 35 24 35s8.5-3.8 8.5-8.5S28.7 18 24 18z"
                                  fill-rule="evenodd">

                            </path>
                        </svg>
                    </button>
                </div>
            </x-slot>

            <a href="/" tabindex="0">
                <h1 class="font-cookie font-semibold text-3xl text-triple38 tracking-wider">{{ config('app.name', 'Laragram') }}</h1>
            </a>

            <x-slot name="right">
                <div class="flex flex-row items-stretch justify-end w-8">
                    <a aria-label="Direct messaging"
                       tabindex="0">
                        <svg aria-label="Direct" class="text-triple38 fill-current w-6 h-6" role="img"
                             viewBox="0 0 48 48">
                            <path
                                d="M47.8 3.8c-.3-.5-.8-.8-1.3-.8h-45C.9 3.1.3 3.5.1 4S0 5.2.4 5.7l15.9 15.6 5.5 22.6c.1.6.6 1 1.2 1.1h.2c.5 0 1-.3 1.3-.7l23.2-39c.4-.4.4-1 .1-1.5zM5.2 6.1h35.5L18 18.7 5.2 6.1zm18.7 33.6l-4.4-18.4L42.4 8.6 23.9 39.7z">
                            </path>
                        </svg>
                    </a>
                </div>
            </x-slot>
        </x-app-top-navigation>

        <x-app-bottom-navigation>home</x-app-bottom-navigation>

        <main class="bg-white flex-grow flex flex-col items-stretch" role="main">
            <!--stories-->
            <div class="bg-triple250 flex flex-col items-stretch pt-2.5 pb-1 border-b border-triple219">
                <div
                    class="flex flex-row items-stretch gap-x-2 px-4 overflow-x-auto overflow-y-hidden no-scrollbar">

                    <form id="form_upload_story"
                          class="hidden"
                          action="{{ route('story.store') }}"
                          enctype="multipart/form-data"
                          method="POST">
                        @csrf
                        <input id="input_story_image"
                               class="hidden"
                               name="image"
                               type="file"
                               accept="image/*"/>
                    </form>

                    @once
                        @push('scripts')
                            <script>
                                $(document).ready(function () {
                                    const $inputStoryImage = $('#input_story_image');
                                    const $form = $('#form_upload_story');

                                    $inputStoryImage.change(function () {
                                        $form.submit();
                                    });
                                });
                            </script>
                        @endpush
                    @endonce

                    @component('components.story.create')@endcomponent


                    @foreach($users as $user)
                        @component('components.story.button', ['user' => $user])@endcomponent
                    @endforeach

                </div>
            </div>

            <!--posts-->
            @component('components.post.posts-container', ['posts' => $posts])@endcomponent

            @component('components.success', ['message' => session('success')])@endcomponent

            @component('components.app-bottom-toast')@endcomponent
        </main>
    </section>
</x-base-layout>
