<x-admin.base-layout>
    <x-admin.top-nav-bar />

    <main class="flex-grow flex overflow-hidden">
        <x-admin.left-nav-bar-with-button :models=$adminModels />

        <div class="order-1 flex-grow flex flex-col items-stretch mx-6 overflow-hidden">
            <x-admin.success-message />

            <div class="flex items-center justify-between gap-x-4 mt-2">
                <h1 class="flex-grow text-lg font-semibold hidden sm:block">
                    Create {{ Str::lower($adminModel['name']) }}
                </h1>
            </div>

            <form id="form_create" class="flex flex-col items-stretch overflow-auto mt-3"
                action="{{ route('admin.' . Str::lower(Str::plural($adminModel['name'])) . '.store') }}" method="POST"
                enctype="multipart/form-data">@csrf
                <input type="hidden" name="next" value="save" />
                <x-admin.fields :rules=$createRules :model=[] />
                <input class="hidden" type="submit" />
            </form>

            <div class="order-2 flex flex-col items-stretch py-2 px-4 mb-2">
                <div class="flex items-center justify-end gap-x-2">
                    <button class="bg-slate-800 text-sm rounded p-2 hover:text-sky-400" type="button"
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
    </main>
</x-admin.base-layout>
