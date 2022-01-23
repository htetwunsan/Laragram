<x-admin.base-layout>
    <x-admin.top-nav-bar />

    <main class="flex-grow flex overflow-hidden">
        <x-admin.left-nav-bar-with-button :models=$adminModels />

        <div class="order-1 flex-grow flex flex-col items-stretch mx-6 overflow-hidden">
            <x-admin.success-message />

            <div class="flex items-center justify-between gap-x-4 mt-2">
                <h1 class="flex-grow text-lg font-semibold hidden sm:block">
                    Edit {{ Str::lower($adminModel['name']) }} •
                    <a class="hover:text-sky-400"
                        href="{{ route('admin.users.edit-password', ['user' => $model]) }}">Edit
                        password</a>
                </h1>
            </div>

            <form id="form_create" class="flex flex-col items-stretch overflow-auto mt-3"
                action="{{ route('admin.users.update', ['user' => $model]) }}" method="POST"
                enctype="multipart/form-data">@csrf
                @method('put')
                <input type="hidden" name="next" value="save" />
                <x-admin.fields :rules=$updateRules :model=$model />
                <input class="hidden" type="submit" />
            </form>

            <div class="order-2 flex flex-col items-stretch py-2 px-4 mb-2">
                <div class="flex items-center justify-between gap-x-2">
                    <form class="flex items-center gap-x-2"
                        action="{{ route('admin.' . Str::plural(Str::lower($adminModel['name'])) . '.destroy', [Str::lower($adminModel['name']) => $model]) }}"
                        method="POST">@csrf @method('delete')
                        <button id="form_delete_cancel"
                            class="hidden bg-slate-800 text-sm rounded p-2 hover:text-sky-400" type="button"
                            onclick="$('#form_delete_delete').toggleClass('hidden'); $('#form_delete_cancel, #form_delete_confirmed').toggleClass('hidden');">
                            Cancel
                        </button>
                        <button id="form_delete_confirmed"
                            class="hidden bg-red-500 text-sm rounded p-2 hover:bg-red-600" type="submit">
                            Confirmed
                        </button>
                        <button id="form_delete_delete" class="bg-red-500 text-sm rounded p-2 hover:bg-red-600"
                            type="button"
                            onclick="$('#form_delete_delete').toggleClass('hidden'); $('#form_delete_cancel, #form_delete_confirmed').toggleClass('hidden');">
                            Delete
                        </button>
                    </form>

                    <div class="flex items-center gap-x-2">
                        <button class="align-end bg-slate-800 text-sm rounded p-2 hover:text-sky-400" type="button"
                            onclick="const $form = $('#form_create'); $form.find('input[name=next]').val('save-and-create'); $form.find('input[type=submit]').click();">
                            Save and create another
                        </button>
                        <button class="bg-slate-800 text-sm rounded p-2 hover:text-sky-400" type="button"
                            onclick="const $form = $('#form_create'); $form.find('input[name=next]').val('save-and-continue'); $form.find('input[type=submit]').click();">
                            Save and continue editing
                        </button>
                        <button class="bg-slate-800 text-sm rounded p-2 hover:text-sky-400" type="button"
                            onclick="const $form = $('#form_create'); $form.find('input[name=next]').val('save'); $form.find('input[type=submit]').click();">
                            Save
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>
</x-admin.base-layout>
