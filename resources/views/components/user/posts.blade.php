@if($posts->count() > 0)
    @component('components.post.posts-images-container', ['posts' => $posts])@endcomponent
@else
    <div class="flex flex-col items-stretch justify-start">
        <div class="flex flex-col items-center justify-start mx-11 my-15">
            <!-- replace with selected -->
            <div class="camera-circle"></div>
            <div
                class="flex flex-col items-stretch justify-start my-6">
                <h1 class="text-triple38 font-light leading-8 -mt-1.5 -mb-1.5"
                    style="font-size: 1.75rem">Share Photos</h1>
            </div>
            <div
                class="flex flex-col items-stretch justify-start mb-6">
                <div class="text-sm text-triple38 text-center font-normal leading-18px -my-1">
                    When you share photos, they will appear on your profile.
                </div>
            </div>
            <button
                class="justify-self-center text-sm text-fb_blue text-center font-semibold leading-18px"
                type="button"
                onclick="window.location.href = '{{ route('post.create') }}';">
                Share your first photo
            </button>
        </div>
    </div>
@endif
