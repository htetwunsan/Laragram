<div class="flex flex-none flex-row">
    <div class="flex-grow flex flex-col items-stretch">
        <button class="border border-triple219 rounded" style="padding: 5px 9px;"
                type="button">
                <span
                    class="block text-sm text-triple38 text-center font-semibold leading-18px overflow-hidden whitespace-nowrap overflow-ellipsis">
                    Message
                </span>
        </button>
    </div>
    <div class="flex flex-col items-stretch ml-2">
        <div class="flex flex-col items-stretch" style="height: 30px">
            <div class="h-full flex gap-x-2">
                <button
                    id="btn_unfollow"
                    class="h-full flex items-center justify-center px-3 border border-triple219 rounded"
                    type="button">
                    <span class="block following"></span>
                </button>

                <button
                    class="h-full flex items-center justify-center px-3 border border-triple219 rounded"
                    type="button">
                        <span
                            class="inline-block transform rotate-180">
                            <svg
                                aria-label="Down Chevron Icon"
                                class="_8-yf5 " color="#262626"
                                fill="#262626" height="12" role="img"
                                viewBox="0 0 48 48"
                                width="12">
                                <path
                                    d="M40 33.5c-.4 0-.8-.1-1.1-.4L24 18.1l-14.9 15c-.6.6-1.5.6-2.1 0s-.6-1.5 0-2.1l16-16c.6-.6 1.5-.6 2.1 0l16 16c.6.6.6 1.5 0 2.1-.3.3-.7.4-1.1.4z">

                                </path>
                            </svg>
                        </span>
                </button>
            </div>
        </div>
    </div>
</div>
@component('components.user.confirm-unfollow-dialog')@endcomponent
@once
    @push('scripts')
        <script>
            var appData = {
                user: @json($user)
            };
            $(document).ready(function () {
                const $btnUnfollow = $('#btn_unfollow');

                $btnUnfollow.click(function () {
                    showConfirmUnfollowDialog(appData.user);
                });
            });
        </script>
    @endpush
@endonce
