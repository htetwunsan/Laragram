<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar">
        <!--header-->
        <x-app-top-navigation>
            <x-slot name="left">
                <x-back-button></x-back-button>
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

                @component('components.user.feeds', ['posts' => $posts])@endcomponent

                @component('components.success', ['message' => session('success')])@endcomponent
            </div>
        </main>
    </section>

    <x-app-menu/>
</x-base-layout>
