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
    <button class="btn_go_to_post text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Go to post
    </button>
    <button class="text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Share to ...
    </button>
    <button class="text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-b border-triple219"
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
    @push('scripts')
        <script>
            $(document).ready(function () {
                const $divMoreOptionsDialog = $('#div_more_options_dialog');
                const $btnUnfollow = $divMoreOptionsDialog.find('.btn_unfollow');
                const $btnGoToPost = $divMoreOptionsDialog.find('.btn_go_to_post');
                const $btnCancel = $divMoreOptionsDialog.find('.btn_cancel');

                function dismissDialog() {
                    $divMoreOptionsDialog.addClass('hidden');
                    $('body').removeClass('overflow-y-hidden');
                }

                $btnUnfollow.click(function () {
                    const post = $divMoreOptionsDialog.data('post');
                    const profileImage = post.user.profile_image;
                    const name = post.user.name;

                    $divMoreOptionsDialog.addClass('hidden');

                    const $divUnfollowDialog = $('#div_unfollow_dialog');
                    $divUnfollowDialog.data('post', post);
                    $divUnfollowDialog.removeClass('hidden')
                    if (profileImage) {
                        $divUnfollowDialog.find('.img_profile').attr('src', 'storage/' + profileImage);
                    }
                    $divUnfollowDialog.find('.div_name').text(`Unfollow@${name}?`);
                });

                $btnGoToPost.click(function () {
                    const post = $divMoreOptionsDialog.data('post');
                    window.location.href = '/posts/' + post.id;
                });

                $btnCancel.click(dismissDialog);
            });
        </script>
    @endpush
</x-app-dialog>
