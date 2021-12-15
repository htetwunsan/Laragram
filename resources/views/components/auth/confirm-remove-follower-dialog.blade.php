<x-app-dialog id="div_confirm_remove_follower_dialog">
    <div class="flex items-center justify-center m-4 mt-8">
        <div class="rounded-full" style="width: 88px; height: 88px">
            <x-profile-image class="img_profile w-full h-full rounded-full">

            </x-profile-image>
        </div>
    </div>

    <div class="flex flex-col items-stretch mx-8 my-4">
        <h2 class="text-triple38 text-center font-light"
            style="line-height: 26px; margin-top: -4px; margin-bottom: -5px; font-size: 22px;">
            Remove Follower?
        </h2>

        <div class="text-sm text-triple142 text-center leading-18px pt-4"
             style="margin-top: -3px; margin-bottom: -4px;">Instagram wont tell <span class="span_username"></span> they
            were removed from your followers
        </div>
    </div>

    <div class="flex flex-col items-stretch mt-4">
        <button
            class="btn_remove text-sm text-error text-center font-bold h-12 px-2 py-1 border-t border-triple219"
            type="button">Remove
        </button>
        <button
            class="btn_cancel text-sm text-triple38 text-center h-12 px-2 py-1 border-t border-triple219"
            type="button">Cancel
        </button>
    </div>
</x-app-dialog>
