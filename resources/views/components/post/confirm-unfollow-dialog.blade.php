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
