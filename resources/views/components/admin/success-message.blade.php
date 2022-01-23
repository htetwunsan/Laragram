@if (session('success'))
    <div class="bg-slate-800/25 flex flex-col items-stretch text-sm text-sky-400 text-center font-semibold p-2">
        {{ session('success') }}
    </div>
@endif
