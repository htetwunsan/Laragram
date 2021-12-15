<x-app-dialog id="div_user_more_options_dialog">
    @if($user->is_blocked_by_auth_user)
        <button class="btn_unblock text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
                type="button"
                tabindex="0">Unblock this user
        </button>
    @else
        <button class="btn_block text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
                type="button"
                tabindex="0">Block this user
        </button>

    @endif
    <button class="btn_restrict text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Restrict
    </button>
    <button class="btn_report text-sm text-error text-center font-bold h-12 py-1 px-2 border-b border-triple219"
            type="button"
            tabindex="0">Report User
    </button>
    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
            type="button"
            tabindex="0">Cancel
    </button>
</x-app-dialog>
@if($user->is_blocked_by_auth_user)
    @component('components.user.confirm-unblock-dialog')@endcomponent
@else
    @component('components.user.confirm-block-dialog')@endcomponent
@endif
@once
    @push('scripts')
        <script>
            $(document).ready(function () {
                $divMoreOptionsDialog = $('#div_user_more_options_dialog');
                $btnUnblock = $divMoreOptionsDialog.find('.btn_unblock');
                $btnBlock = $divMoreOptionsDialog.find('.btn_block');
                $btnRestrict = $divMoreOptionsDialog.find('.btn_restrict');
                $btnReport = $divMoreOptionsDialog.find('.btn_report');
                $btnCancel = $divMoreOptionsDialog.find('.btn_cancel');

                $btnUnblock.click(function () {
                    const user = $divMoreOptionsDialog.data('user');
                    window.toggleDialog($('#div_confirm_unblock_dialog'), function ($dialog) {
                        $dialog.find('.span_username').text(user.username);
                        $dialog.find('.form_unblock_user').attr('action', `/users/${user.id}/unblock`);
                    });
                })

                $btnBlock.click(function () {
                    const user = $divMoreOptionsDialog.data('user');
                    window.toggleDialog($('#div_confirm_block_dialog'), function ($dialog) {
                        $dialog.find('.span_username').text(user.username);
                        $dialog.find('.form_block_user').attr('action', `/users/${user.id}/block`);
                    });
                });

                $btnUnblock.add($btnBlock).add($btnRestrict).add($btnReport).add($btnCancel).click(function () {
                    window.toggleDialog($divMoreOptionsDialog);
                });

                $btnRestrict.add($btnReport).click(function () {
                    showBottomToast('This feature is not available now.');
                });
            });
        </script>
    @endpush
@endonce
