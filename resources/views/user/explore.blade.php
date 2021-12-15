<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">
        <!--header-->
        <x-app-top-navigation>
            <x-slot name="left">
                <div class="flex flex-row w-8">
                    <x-back-button>{{ route('user.show', ['user' => auth()->user()->username]) }}</x-back-button>
                </div>
            </x-slot>
            <h1 class="flex-1 text-base text-triple38 text-center font-semibold leading-18px whitespace-nowrap overflow-ellipsis">
                Discover People</h1>

            <x-slot name="right">
                <div class="flex flex-row w-8"></div>
            </x-slot>
        </x-app-top-navigation>

        <!--footer-->
        <x-app-bottom-navigation>profile</x-app-bottom-navigation>

        <!--main content-->
        <main class="flex-grow flex flex-col items-stretch">
            <div class="flex flex-col items-stretch">
                <div class="flex flex-col items-stretch px-3 my-4">
                    <h4 class="text-base text-triple38 font-semibold leading-6" style="margin: -6px 0;">
                        Suggested
                    </h4>
                </div>

                <div class="flex flex-col items-stretch py-2">
                    @component('components.user.users-container', ['users' => $users])@endcomponent
                </div>
            </div>
        </main>
    </section>
</x-base-layout>
