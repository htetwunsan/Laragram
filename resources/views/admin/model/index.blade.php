<x-admin.base-layout>
    <x-admin.top-nav-bar />

    @php
        $query = Request::query();
    @endphp
    <main class="flex-grow flex overflow-hidden">
        <x-admin.left-nav-bar-with-button :models=$adminModels />

        <button
            class="order-12 hidden basis-6 flex-none md:flex items-center justify-center border-l border-l-sky-400 mr-1 hover:bg-slate-800/25 group"
            type="button" onclick="const element = document.getElementById('right_nav_bar_container');
                element.style.display == 'none' ? element.style.removeProperty('display') : element.style.display = 'none';
                document.getElementById('right_double_arrow').classList.toggle('rotate-180');">
            <span id="right_double_arrow"
                class="material-icons-outlined text-slate-700 group-hover:text-sky-400 rotate-180">
                double_arrow
            </span>
        </button>

        <div id="right_nav_bar_container" class="order-11 hidden basis-80 flex-none md:flex flex-col items-stretch my-4"
            style="display: none;">
            <x-admin.right-nav-bar :models=$adminModels :query=$query :filters=$filters :types=$types />
        </div>

        <div class="order-1 flex-grow flex flex-col items-stretch mx-6 overflow-hidden">
            <x-admin.success-message />

            <div class="flex items-center justify-between gap-x-4 mt-2">
                <h1 class="flex-grow text-lg font-semibold hidden sm:block">
                    Select {{ Str::lower($adminModel['name']) }} to edit
                </h1>

                <x-admin.search-bar />

                <a class="flex items-center border border-slate-50 px-2 py-1 rounded text-sm group hover:border-sky-400"
                    href="{{ $adminModel['create_url'] }}">
                    <span class="material-icons-outlined text-base group-hover:hidden">
                        add
                    </span>
                    <span class="hidden leading-6 group-hover:block text-sky-400">
                        Create {{ Str::lower($adminModel['name']) }}
                    </span>
                </a>
            </div>

            <div class="flex-grow flex flex-col items-stretch mt-4 overflow-auto rounded-t-lg">
                <table class="border-collapse table-auto">
                    <thead class="bg-slate-900 text-sm text-left font-semibold whitespace-nowrap sticky top-0 z-10">
                        <tr>
                            @if (!is_null($models->first()))
                                @foreach (array_keys($models->first()->getOriginal()) as $attribute)
                                    <th class="pt-4 pb-2 px-2">
                                        @if (Arr::exists($query, 'orders'))
                                            @php
                                                $query['newOrders'][$attribute] = Arr::exists($query['orders'], $attribute) ? ($query['orders'][$attribute] == 'ASC' ? 'DESC' : 'ASC') : 'ASC';
                                                $query['newOrders'] = array_merge($query['orders'], Arr::only($query['newOrders'], $attribute));
                                            @endphp
                                            <div class="flex items-center gap-x-0.5 group">
                                                <a
                                                    href="{{ Request::fullUrlWithQuery(['orders' => $query['newOrders']]) }}">
                                                    {{ Str::upper(Str::replace('_', ' ', $attribute)) }}
                                                </a>
                                                @if (Arr::exists($query['orders'], $attribute))
                                                    <a class="invisible flex flex-col group-hover:visible hover:text-sky-400"
                                                        href="{{ Request::fullUrlWithQuery(['orders' => Arr::except($query['orders'], $attribute)]) }}"
                                                        title="Remove from ordering">
                                                        <span class="material-icons-outlined -mb-2 text-sm">
                                                            arrow_drop_up
                                                        </span>
                                                        <span class="material-icons-outlined -mt-2 text-sm">
                                                            arrow_drop_down
                                                        </span>
                                                    </a>
                                                    <a
                                                        href="{{ Request::fullUrlWithQuery(['orders' => $query['newOrders']]) }}">
                                                        @if ($query['orders'][$attribute] == 'ASC')
                                                            <span
                                                                class="block material-icons-outlined text-base hover:text-sky-400"
                                                                title="Toggle ordering">
                                                                arrow_drop_down
                                                            </span>
                                                        @else
                                                            <span
                                                                class="block material-icons-outlined text-base hover:text-sky-400"
                                                                title="Toggle ordering">
                                                                arrow_drop_up
                                                            </span>
                                                        @endif
                                                    </a>
                                                    <span class="block text-[0.5rem] leading-none"
                                                        title="Ordering rank">
                                                        {{ array_search($attribute, array_keys($query['orders'])) + 1 }}
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <a
                                                href="{{ Request::fullUrlWithQuery(['orders' => [$attribute => 'ASC']]) }}">
                                                {{ Str::upper(Str::replace('_', ' ', $attribute)) }}
                                            </a>
                                        @endif
                                    </th>
                                @endforeach
                            @endif
                        </tr>
                    </thead>
                    <tbody class="bg-slate-800 text-sm text-left whitespace-nowrap">
                        @foreach ($models as $model)
                            <tr>
                                @foreach ($model->getOriginal() as $key => $value)
                                    <td class="py-2 px-2">
                                        @if ($key == $adminModel['primaryKey'])
                                            <a class="hover:text-sky-400"
                                                href="{{ route('admin.' . Str::lower(Str::plural($adminModel['name'])) . '.edit', $value) }}">
                                                {{ $value }}</a>
                                        @elseif ($adminModel['foreignKeys'][$key] ?? null)
                                            <a class="hover:text-sky-400"
                                                href="{{ route('admin.' . Str::lower(Str::plural(class_basename($adminModel['foreignKeys'][$key]['model']))) . '.edit', $value) }}">
                                                {{ $value }}</a>
                                        @else
                                            @switch($types[$key])

                                                @case('string')
                                                    @if (filter_var($value, FILTER_VALIDATE_URL))
                                                        <a class="underline hover:text-sky-400"
                                                            href="{{ $value }}">{{ $value }}</a>
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                @break

                                                @case('text')
                                                    <span class="block max-w-md overflow-hidden text-ellipsis">
                                                        {{ $value }}
                                                    </span>
                                                @break

                                                @case('boolean')
                                                    @if ($value)
                                                        <span class="material-icons-outlined text-sky-400">
                                                            check_circle
                                                        </span>
                                                    @else
                                                        <span class="material-icons-outlined text-pink-600">
                                                            highlight_off
                                                        </span>
                                                    @endif
                                                @break

                                                @default
                                                    {{ $value }}

                                            @endswitch
                                        @endif

                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-stretch my-2">
                {{ $models->onEachSide(3)->links('admin.pagination.tailwind') }}
            </div>
        </div>
    </main>
</x-admin.base-layout>
