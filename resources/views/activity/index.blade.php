<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <x-app-top-navigation>
            <x-slot name="left">
                <div class="w-8"></div>
            </x-slot>

            <h1 class="flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">
                Activity</h1>

            <x-slot name="right">
                <div class="w-8"></div>
            </x-slot>
        </x-app-top-navigation>

        <x-app-bottom-navigation>
            activity
        </x-app-bottom-navigation>

        <main class="bg-white flex-grow flex flex-col items-stretch" role="main">
            <!--activities container-->
            @component('components.activity.activities-container', ['notifications' => $notifications])
            @endcomponent
        </main>
    </section>
</x-base-layout>
