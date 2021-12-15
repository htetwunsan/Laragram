@foreach($posts as $post)
    <article>
        <a href="{{ route('post.show', ['post' => $post]) }}">
            <div class="relative">
                <x-post.image class="object-cover" alt="{{ $post->images->first()->alternate_text }}">
                    {{ $post->images->first()->image }}
                </x-post.image>

                @if($post->images->count() > 1)
                    <svg class="absolute top-0 right-0 m-2" aria-label="Carousel" color="#ffffff" fill="#ffffff"
                         height="22"
                         role="img" viewBox="0 0 48 48"
                         width="22">
                        <path
                            d="M34.8 29.7V11c0-2.9-2.3-5.2-5.2-5.2H11c-2.9 0-5.2 2.3-5.2 5.2v18.7c0 2.9 2.3 5.2 5.2 5.2h18.7c2.8-.1 5.1-2.4 5.1-5.2zM39.2 15v16.1c0 4.5-3.7 8.2-8.2 8.2H14.9c-.6 0-.9.7-.5 1.1 1 1.1 2.4 1.8 4.1 1.8h13.4c5.7 0 10.3-4.6 10.3-10.3V18.5c0-1.6-.7-3.1-1.8-4.1-.5-.4-1.2 0-1.2.6z">

                        </path>
                    </svg>
                @endif
            </div>
        </a>
    </article>
@endforeach
