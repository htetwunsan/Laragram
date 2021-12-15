@if(auth()->user()->active_stories_count > 0)
    @component('components.story.button', ['user' => auth()->user()])@endcomponent
@else
    <div class="w-20 flex flex-row items-center justify-center px-1">
        <button class="relative w-16" type="button" onclick="$('#input_story_image').trigger('click')">
        <span class="block rounded-full mx-auto mt-2 mb-3"
              role="link"
              tabindex="-1">
            <x-profile-image class="w-14 h-14 rounded-full" draggable="false" alt=""
                             draggable="false">
                {{ auth()->user()->profile_image }}
            </x-profile-image>
        </span>
            <span
                class="block text-xs text-triple38 text-center font-normal leading-4 overflow-ellipsis overflow-hidden whitespace-nowrap  mx-auto">
                                Your Story
        </span>
            <span
                class="block bg-triple250 absolute top-11 right-1 border border-triple250 rounded-full">
                <svg aria-label="Plus icon" class="text-fb_blue fill-current w-4 h-4"
                     role="img" viewBox="0 0 48 48">
                    <path
                        d="M24 0C10.8 0 0 10.7 0 24s10.7 24 24 24 24-10.7 24-24S37.3 0 24 0zm12.3 25.5H25.5v10.7c0 .8-.7 1.5-1.5 1.5s-1.5-.7-1.5-1.5V25.5H11.8c-.8 0-1.5-.7-1.5-1.5s.7-1.5 1.5-1.5h10.7V11.7c0-.8.7-1.5 1.5-1.5s1.5.7 1.5 1.5v10.7h10.7c.8 0 1.5.7 1.5 1.5s-.6 1.6-1.4 1.6z">
                    </path>
                </svg>
        </span>
        </button>
    </div>
@endif
