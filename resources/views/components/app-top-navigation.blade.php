<nav class="flex flex-col items-stretch order-first z-50">
    <div class="flex flex-col items-stretch h-11">
        <header
            class="max-w-screen-sm mx-auto fixed top-0 left-0 right-0 flex flex-col items-stretch border-b border-solid border-triple219 z-10 bg-white">
            <div class="flex flex-row items-center justify-between h-11 px-4">
                <div class="flex items-center justify-start">
                    {{ $left }}
                </div>

                {{ $slot }}

                <div class="flex items-center justify-end">
                    {{ $right }}
                </div>
            </div>
        </header>
    </div>
</nav>
