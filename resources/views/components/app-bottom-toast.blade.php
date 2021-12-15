<div id="div_bottom_toast"
     class="bg-black absolute bottom-0 left-0 right-0 flex flex-row justify-center z-10 px-5 py-2 hidden">
    <h1 class="flex-grow flex flex-col items-stretch text-sm text-white font-medium leading-18px">Message</h1>
</div>
@once
    @push('scripts')
        <script>
            function showBottomToast(message) {
                const $toast = $('#div_bottom_toast');
                $toast.find('h1').text(message);
                $toast.removeClass('hidden');
                $toast.animate({bottom: '44'}, 'fast', 'linear', function () {
                });
                setTimeout(function () {
                    $toast.animate({bottom: '0'}, 'fast', 'linear', function () {
                        $(this).addClass('hidden');
                    });
                }, 5000);
            }
        </script>
    @endpush
@endonce
