<x-app-dialog id="div_confirm_unfollow_dialog">
    <div class="mt-8 mx-4 mb-4 flex items-center justify-center">
        <x-profile-image class="img_profile rounded-full" style="width: 90px; height: 90px"/>
    </div>
    <div class="mx-8 my-4">
        <div class="text-sm text-triple38 text-center leading-18px break-words">
            Unfollow@<span class="span_username">username</span>?
        </div>
    </div>
    <button
        class="btn_unfollow text-sm text-error text-center font-bold h-12 py-1 px-2 border-t border-b border-triple219"
        type="button"
        tabindex="0">Unfollow
    </button>
    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
            type="button"
            tabindex="0">
        Cancel
    </button>
</x-app-dialog>
@once
    @push('scripts')
        <script>
            function showConfirmUnfollowDialog(user, unfollowCompleteCallback) {
                window.toggleDialog($('#div_confirm_unfollow_dialog'), function ($dialog) {
                    if (user.profile_image) {
                        $dialog.find('.img_profile').attr('src', user.profile_image);
                    }
                    $dialog.find('.span_username').text(user.username);

                    let requestInProgress = false;

                    $dialog.find('.btn_unfollow').off('click').click(function () {
                        if (requestInProgress) return;
                        requestInProgress = true;

                        axios.post(`/api/users/${user.id}/unfollow`)
                            .then(response => {
                                if (_.isFunction(unfollowCompleteCallback)) {
                                    unfollowCompleteCallback(response);
                                }
                                window.toggleDialog($dialog);
                            })
                            .finally(function () {
                                requestInProgress = false;
                            })
                    });
                });
            }
            $(document).ready(function () {
                const $dialog = $('#div_confirm_unfollow_dialog');
                const $btnCancel = $dialog.find('.btn_cancel');

                $btnCancel.click(function () {
                    window.toggleDialog($dialog);
                });
            });
        </script>
    @endpush
@endonce
