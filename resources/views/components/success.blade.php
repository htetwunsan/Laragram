@if($message)
    <div id="div_success"
         class="bg-black absolute bottom-11 left-0 right-0 flex flex-row justify-center z-10 px-5 py-2">
        <h1 class="flex-grow flex flex-col items-stretch text-sm text-white font-medium leading-18px">{{ $message }}</h1>
    </div>
    @once
        @push('scripts')
            <script>
                $(document).ready(function () {
                    setTimeout(function () {
                        $('#div_success').animate({bottom: '0'}, 'fast', 'linear', function () {
                            $(this).addClass('hidden');
                        });
                    }, 5000);
                });
            </script>
        @endpush
    @endonce
@endif
