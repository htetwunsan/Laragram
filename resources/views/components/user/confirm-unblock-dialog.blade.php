<x-app-dialog id="div_confirm_unblock_dialog">
    <div class="flex flex-col items-stretch justify-center mx-8 mt-8 mb-4">
        <h3 class="text-lg text-triple38 text-center font-semibold leading-6 -mt-1" style="margin-bottom: -6px;">
            Unblock <span class="span_username">username</span>?</h3>
        <div class="text-sm text-triple142 text-center leading-18px pt-4 -mb-1" style="margin-top: -3px">
            They will now be able to see your posts and follow you on Instagram. Instagram won't let them known you
            unblocked them.
        </div>
    </div>
    <form class="form_unblock_user flex items-center justify-center border-b border-t border-triple219"
          method="POST">@csrf
        <button class="flex-grow text-sm text-fb_blue text-center font-bold h-12 py-1 px-2"
                type="submit"
                tabindex="0">Unblock
        </button>
    </form>
    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
            type="button"
            tabindex="0">
        Cancel
    </button>
</x-app-dialog>
@once
    @push('scripts')
        <script>
            $(document).ready(function () {
                const $divConfirmUnblockDialog = $('#div_confirm_unblock_dialog');
                const $btnCancel = $divConfirmUnblockDialog.find('.btn_cancel');

                $btnCancel.click(function () {
                    window.toggleDialog($divConfirmUnblockDialog);
                });
            });
        </script>
    @endpush
@endonce
