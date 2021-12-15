<x-app-dialog id="div_confirm_unfollow_dialog">
    <div class="mt-8 mx-4 mb-4 flex items-center justify-center">
        <x-profile-image class="img_profile rounded-full" style="width: 90px; height: 90px"/>
    </div>
    <div class="mx-8 my-4">
        <div class="text-sm text-triple38 text-center leading-18px break-words">
            Unfollow@<span class="span_username">username</span>?
        </div>
    </div>
    <form class="form_unfollow_user flex items-center justify-center border-b border-t border-triple219"
          method="POST">@csrf
        <button class="flex-grow text-sm text-error text-center font-bold h-12 py-1 px-2"
                type="submit"
                tabindex="0">Unfollow
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

            function showConfirmUnfollowDialog(user) {
                window.toggleDialog($('#div_confirm_unfollow_dialog'), function ($dialog) {
                    if (user.profileImage) {
                        $dialog.find('.img_profile').attr('src', user.profileImage);
                    }
                    $dialog.find('.span_username').text(user.username);
                    $dialog.find('.form_unfollow_user').attr('action', `/users/${user.id}/unfollow`);
                });
            }

            $(document).ready(function () {
                const $divConfirmUnfollowDialog = $('#div_confirm_unfollow_dialog');
                const $btnCancel = $divConfirmUnfollowDialog.find('.btn_cancel');

                $btnCancel.click(function () {
                    window.toggleDialog($divConfirmUnfollowDialog);
                });
            });
        </script>
    @endpush
@endonce
