@props(['models'])
<nav id="left_nav_bar" class="flex flex-col items-stretch overflow-y-auto rounded-t-lg">
    <table class="border-collapse table-auto">
        <!--bg-slate-800/25-->
        <caption class="bg-slate-900 text-left font-semibold pt-4 pb-2 px-2 sticky top-0 z-10">
            Models
        </caption>
        <tbody class="bg-slate-800 text-sm">
            @foreach ($models as $model)
            <tr>
                <td class="py-1 pl-2 w-full">
                    <a href="{{ $model['index_url'] }}">
                        {{ $model['name'] }}
                    </a>
                </td>
                <td class="py-1 pr-2">
                    <a class="flex items-center group" href="{{ $model['create_url'] }}">
                        <span class="material-icons-outlined text-lg mr-1 group-hover:text-sky-400">
                            add
                        </span>
                        Create
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</nav>
