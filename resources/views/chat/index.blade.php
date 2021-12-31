<x-base-layout>
    <div id="chat_app" class="h-full flex flex-col items-stretch overflow-hidden"></div>
    <div id="div_bottom_toast"
        class="bg-black absolute -bottom-44 left-0 right-0 flex items-center justify-center z-10 px-5 py-2 hidden">
        <h1 class="flex-grow flex flex-col items-stretch text-sm text-white font-medium leading-18px">Message</h1>
    </div>
    @once
    @push('scripts')
    <script>
        function showBottomToast(message) {
                const $toast = $('#div_bottom_toast');
                $toast.find('h1').text(message);
                $toast.removeClass('hidden');
                $toast.animate({bottom: '0'}, 'slow', 'linear', function () {
                    setTimeout(function () {
                    $toast.animate({bottom: '-44'}, 'slow', 'linear', function () {
                        $toast.addClass('hidden');
                    });
                }, 5000);
                });
            }
    </script>
    @endpush
    @endonce
</x-base-layout>
