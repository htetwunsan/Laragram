<x-admin.base-layout>
    <x-admin.top-nav-bar />
    <main class="flex-grow flex px-8">
        <div class="flex-grow flex flex-col items-stretch my-4">
            <x-admin.left-nav-bar :models=$adminModels />
        </div>

        <div class="flex-grow ml-2 my-4">
            Recent Actions
        </div>
    </main>
</x-admin.base-layout>
