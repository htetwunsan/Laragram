<x-app-dialog id="div_more_options_dialog">
    <button class="text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Report
    </button>
    <button class="btn_unfollow text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">
        Unfollow
    </button>
    <button class="btn_delete text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">
        Delete
    </button>
    <button class="btn_go_to_post text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Go to post
    </button>
    <button class="text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Share to ...
    </button>
    <input class="input_link hidden" value=""/>
    <button class="btn_copy_link text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Copy Link
    </button>
    <button class="text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Embed
    </button>
    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
            type="button"
            tabindex="0">
        Cancel
    </button>
</x-app-dialog>
@component('components.post.confirm-unfollow-dialog')@endcomponent
@component('components.post.confirm-delete-dialog')@endcomponent
@push('scripts')
    <script>

        function toggleMoreOptionsDialog(post) {
            const $divMoreOptionsDialog = $('#div_more_options_dialog');
            const $btnUnfollow = $divMoreOptionsDialog.find('.btn_unfollow');
            const $btnDelete = $divMoreOptionsDialog.find('.btn_delete');

            window.toggleDialog($divMoreOptionsDialog, function ($dialog) {
                $dialog.data('post', post);
                if (post.user.id === appAuthUser.id) {
                    $btnUnfollow.addClass('hidden');
                    $btnDelete.removeClass('hidden');
                } else {
                    $btnUnfollow.removeClass('hidden');
                    $btnDelete.addClass('hidden');
                }
            });
        }

        $(document).ready(function () {
            // for more options dialog
            const $divMoreOptionsDialog = $('#div_more_options_dialog');
            const $btnDelete = $divMoreOptionsDialog.find('.btn_delete');
            const $btnUnfollow = $divMoreOptionsDialog.find('.btn_unfollow');
            const $btnCopyLink = $divMoreOptionsDialog.find('.btn_copy_link');
            const $btnGoToPost = $divMoreOptionsDialog.find('.btn_go_to_post');
            const $btnCancel = $divMoreOptionsDialog.find('.btn_cancel');

            const $inputLink = $divMoreOptionsDialog.find('.input_link');

            $btnDelete.click(function () {
                const post = $divMoreOptionsDialog.data('post');

                window.toggleDialog($('#div_confirm_delete_dialog'), function ($dialog) {
                    $dialog.data('post', post);
                    $dialog.find('.form_delete_post').attr('action', `/posts/${post.id}`);
                });
            });

            $btnUnfollow.click(function () {
                const post = $divMoreOptionsDialog.data('post');
                const profileImage = post.user.profile_image;
                const username = post.user.username;

                showConfirmUnfollowDialog(post.user, function (response) {
                    $(`.article_post[data-user_id=${post.user.id}] .btn_follow`).removeClass('hidden');
                });
            });

            $btnCopyLink.click(function () {
                const post = $divMoreOptionsDialog.data('post');
                $inputLink.val(window.location.origin + '/posts/' + post.id);
                $inputLink[0].select();
                $inputLink[0].setSelectionRange(0, 99999);
                navigator.clipboard.writeText($inputLink.val());
                showBottomToast('Link copied to clipboard.');
            });

            $btnGoToPost.click(function () {
                const post = $divMoreOptionsDialog.data('post');
                window.location.href = '/posts/' + post.id;
            });

            $btnDelete.add($btnUnfollow).add($btnCopyLink).add($btnGoToPost).add($btnCancel).click(function () {
                window.toggleDialog($divMoreOptionsDialog);
            });
        });

        $(document).ready(function () {
            // for confirm delete dialog
            const $divConfirmDeleteDialog = $('#div_confirm_delete_dialog');
            const $btnCancel = $divConfirmDeleteDialog.find('.btn_cancel');

            $btnCancel.click(function () {
                toggleDialog($divConfirmDeleteDialog);
            });
        });
    </script>
@endpush
