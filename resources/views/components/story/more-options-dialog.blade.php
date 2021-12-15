<x-app-dialog id="div_more_options_dialog">
    <button class="btn_delete text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Delete
    </button>
    <button class="btn_report text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Report
    </button>
    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
            type="button"
            tabindex="0">Cancel
    </button>
</x-app-dialog>
@component('components.story.confirm-delete-dialog')@endcomponent
@once
    @push('scripts')
        <script>
            function toggleMoreOptionsDialog(story, showCallback) {
                window.toggleDialog($('#div_more_options_dialog'), function ($dialog) {
                    $dialog.data('story', story);
                    const $btnDelete = $dialog.find('.btn_delete');
                    const $btnReport = $dialog.find('.btn_report');

                    if (story.user_id === appData.authUser.id) {
                        $btnReport.addClass('hidden');
                        $btnDelete.removeClass('hidden');
                    } else {
                        $btnReport.removeClass('hidden');
                        $btnDelete.addClass('hidden');
                    }

                    if (_.isFunction(showCallback)) {
                        showCallback();
                    }
                });
            }

            $(document).ready(function () {
                // for more options dialog
                const $divMoreOptionsDialog = $('#div_more_options_dialog');
                const $btnReport = $divMoreOptionsDialog.find('.btn_report');
                const $btnDelete = $divMoreOptionsDialog.find('.btn_delete');
                const $btnCancel = $divMoreOptionsDialog.find('.btn_cancel');

                $btnDelete.click(function () {
                    const story = $divMoreOptionsDialog.data('story');

                    window.toggleDialog($divMoreOptionsDialog);

                    const $divConfirmDeleteDialog = $('#div_confirm_delete_dialog');

                    window.toggleDialog($divConfirmDeleteDialog, function () {
                        $divConfirmDeleteDialog.find('.form_delete_story').attr('action', `/stories/${story.id}`);
                    });
                });

                $btnCancel.add($btnReport).click(function () {
                    window.toggleDialog($divMoreOptionsDialog);
                    $('#btn_pause_resume').trigger('click');
                });
            });

            $(document).ready(function () {
                // for confirm delete dialog
                const $divConfirmDeleteDialog = $('#div_confirm_delete_dialog');
                const $btnCancel = $divConfirmDeleteDialog.find('.btn_cancel');

                $btnCancel.click(function () {
                    window.toggleDialog($divConfirmDeleteDialog);
                    $('#btn_pause_resume').trigger('click');
                });
            });
        </script>
    @endpush
@endonce
