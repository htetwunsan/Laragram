<x-app-dialog id="div_confirm_delete_dialog">
    <div class="flex flex-col items-stretch justify-center mx-8 mt-8 mb-4">
        <h3 class="text-lg text-triple38 text-center font-semibold leading-6 -mt-1" style="margin-bottom: -6px;">
            Delete
            Post?</h3>
        <div class="text-sm text-triple142 text-center leading-18px pt-4 -mb-1" style="margin-top: -3px">
            Are your sure you want to delete this post?
        </div>
    </div>
    <form class="form_delete_post flex items-center justify-center border-b border-t border-triple219"
          method="POST">@csrf @method('DELETE')
        <button class="flex-grow text-sm text-error text-center font-bold h-12 py-1 px-2"
                type="submit"
                tabindex="0">Delete
        </button>
    </form>
    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2"
            type="button"
            tabindex="0">
        Cancel
    </button>
</x-app-dialog>
