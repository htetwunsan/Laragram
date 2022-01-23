<nav id="nav_top_bar" class="flex flex-col items-center md:flex-row md:items-start py-4 border-b border-b-sky-400 px-4">
    <div class="flex-grow flex">
        <h1 class="text-xl font-semibold">{{ config('app.name', 'Laravel') }} Administration</h1>
    </div>

    <div class="flex items-center text-sm">
        <h2 class="tracking-tight">WELCOME,
            <span class="text-sky-400 uppercase">{{ auth()->user()->name }}</span>.&nbsp
        </h2>
        <a class="block tracking-tight hover:text-sky-400" href="{{ route('post.index') }}">VIEW SITE</a>
        &nbsp/&nbsp
        <form class="flex items-center" action="{{ route('logout') }}" method="POST">@csrf
            <button class="tracking-tight hover:text-sky-400" type="submit">LOG OUT</button>
        </form>
    </div>
</nav>
