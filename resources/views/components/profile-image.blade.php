@if($slot == '')
    <img {{ $attributes }} src="{{ asset('images/default-profile.jpeg') }}"/>
@elseif(Storage::disk('public')->exists($slot))
    <img {{ $attributes }} src="/storage/{{ $slot }}"/>
@else
    <img {{ $attributes }} src="{{ $slot }}"/>
@endif
