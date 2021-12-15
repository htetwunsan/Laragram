<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <!--header-->
        <x-app-top-navigation>
            <x-slot name="left">
                <button class="flex flex-row" tabindex="0" onclick="window.history.back()">
                    <span class="block transform -rotate-90">
                        <svg aria-label="Back"
                             class="text-triple38 fill-current w-6 h-6"
                             role="img"
                             viewBox="0 0 48 48">
                            <path
                                d="M40 33.5c-.4 0-.8-.1-1.1-.4L24 18.1l-14.9 15c-.6.6-1.5.6-2.1 0s-.6-1.5 0-2.1l16-16c.6-.6 1.5-.6 2.1 0l16 16c.6.6.6 1.5 0 2.1-.3.3-.7.4-1.1.4z">

                            </path>
                        </svg>
                    </span>
                </button>
            </x-slot>

            <h1 class="flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">{{ $user->username }}</h1>

            <x-slot name="right">
                <div class="w-8"></div>
            </x-slot>
        </x-app-top-navigation>

        <!--footer-->
        <x-app-bottom-navigation>home</x-app-bottom-navigation>
        <!--main-content-->
        <main class="bg-white flex-grow flex flex-col items-stretch">
            <div class="flex-grow flex flex-col items-stretch">

                @component('components.user.profile', ['user' => $user])@endcomponent

                @component('components.user.tab-header', ['user' => $user, 'activeTab' => $activeTab])@endcomponent

                @component('components.user.posts', ['posts' => $posts])@endcomponent

                @component('components.success', ['message' => session('success')])@endcomponent
            </div>
        </main>
    </section>

    <x-app-menu/>
</x-base-layout>
