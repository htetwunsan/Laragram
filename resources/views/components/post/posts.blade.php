@foreach($posts as $post)
    @component('components.post.post', ['post' => $post])@endcomponent
@endforeach
