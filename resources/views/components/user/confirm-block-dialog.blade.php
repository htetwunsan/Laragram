<x-app-dialog id="div_confirm_block_dialog">
    <div class="flex flex-col items-stretch justify-center mx-8 mt-8 mb-4">
        <h3 class="text-lg text-triple38 text-center font-semibold leading-6 -mt-1" style="margin-bottom: -6px;">
            Block <span class="span_username">username</span>?</h3>
        <div class="text-sm text-triple142 text-center leading-18px pt-4 -mb-1" style="margin-top: -3px">
            They won't be able to find your profile, posts or story on Instagram. Instagram won't let them know you
            blocked them.
        </div>
    </div>
    <form class="form_block_user flex items-center justify-center border-b border-t border-triple219"
          method="POST">@csrf
        <button class="flex-grow text-sm text-fb_blue text-center font-bold h-12 py-1 px-2"
                type="submit"
                tabindex="0">Block
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
                const $divConfirmBlockDialog = $('#div_confirm_block_dialog');
                const $btnCancel = $divConfirmBlockDialog.find('.btn_cancel');

                $btnCancel.click(function () {
                    window.toggleDialog($divConfirmBlockDialog);
                });
            });
        </script>
    @endpush
@endonce
