@props(['models', 'query', 'filters', 'types'])
<nav id="right_nav_bar" class="flex flex-col items-stretch overflow-y-auto rounded-t-lg">
    <table class="border-collapse table-auto">
        <!--bg-slate-800/25-->
        <caption class="bg-slate-900 text-left font-semibold pt-4 pb-2 px-2 sticky top-0 z-10">
            Filter
        </caption>
        <tbody class="bg-slate-800 text-sm">
            @if (Arr::exists($query, 'filters'))
                <tr>
                    <td class="py-1 pl-2 w-full hover:text-sky-400 leading-4">
                        <a class="flex items-center" href={{ Request::fullUrlWithQuery(['filters' => null]) }}>
                            <span class="material-icons-outlined text-base mr-0.5">
                                close
                            </span>
                            Clear all filters
                        </a>
                    </td>
                </tr>
            @endif
            @foreach ($filters as $filter)
                @if (Arr::exists($types, $filter))
                    <tr>
                        <td class="py-1 pl-2 w-full">
                            <div class="flex flex-col gap-y-1">
                                <div>
                                    By {{ $filter }}
                                </div>
                                <div class="flex flex-col gap-y-1 ml-2">
                                    @php
                                        $value = Arr::get($query, 'filters.' . $filter, -1);
                                        if (!Arr::exists($query, 'filters')) {
                                            $query['filters'] = [];
                                        }
                                        $defaultHref = Request::fullUrlWithQuery(['filters' => Arr::except($query['filters'], $filter)]);
                                    @endphp
                                    @switch($types[$filter])
                                        @case('boolean')
                                            @php
                                                $trueHref = Request::fullUrlWithQuery(['filters' => [...$query['filters'], $filter => true]]);
                                                $falseHref = Request::fullUrlWithQuery(['filters' => [...$query['filters'], $filter => false]]);
                                            @endphp
                                            @if ($value == -1)
                                                <a class="text-sky-400">All</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $defaultHref }}">All</a>
                                            @endif
                                            @if ($value == 1)
                                                <a class="text-sky-400">True</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $trueHref }}">True</a>
                                            @endif
                                            @if ($value == 0)
                                                <a class="text-sky-400">False</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $falseHref }}">False</a>
                                            @endif
                                        @break

                                        @case('datetime')
                                            @php
                                                $todayHref = Request::fullUrlWithQuery(['filters' => [...$query['filters'], $filter => 'today']]);
                                                $past7daysHref = Request::fullUrlWithQuery(['filters' => [...$query['filters'], $filter => 'past 7 days']]);
                                                $thismonthHref = Request::fullUrlWithQuery(['filters' => [...$query['filters'], $filter => 'this month']]);
                                                $thisyearHref = Request::fullUrlWithQuery(['filters' => [...$query['filters'], $filter => 'this year']]);
                                            @endphp
                                            @if ($value == -1)
                                                <a class="text-sky-400">Any date</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $defaultHref }}">Any date</a>
                                            @endif
                                            @if ($value == 'today')
                                                <a class="text-sky-400">Today</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $todayHref }}">Today</a>
                                            @endif
                                            @if ($value == 'past 7 days')
                                                <a class="text-sky-400">Past 7 days</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $past7daysHref }}">Past 7 days</a>
                                            @endif
                                            @if ($value == 'this month')
                                                <a class="text-sky-400">This month</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $thismonthHref }}">This month</a>
                                            @endif
                                            @if ($value == 'this year')
                                                <a class="text-sky-400">This year</a>
                                            @else
                                                <a class="hover:text-sky-400" href="{{ $thisyearHref }}">This year</a>
                                            @endif
                                        @break
                                    @endswitch
                                </div>
                            </div>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</nav>
