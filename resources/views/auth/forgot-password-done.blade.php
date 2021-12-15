<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto">

        <!--header-->
        <nav class="flex flex-col items-stretch order-first z-50">
            <div class="flex flex-col items-stretch h-11">
                <header
                    class="max-w-screen-sm mx-auto fixed top-0 left-0 right-0 flex flex-col items-stretch border-b border-solid border-triple219 z-10 bg-white">
                    <div class="flex flex-row items-center justify-between h-11 px-4">
                        <div class="flex items-center justify-start w-8">
                            <x-back-button>
                                {{ route('password.request') }}
                            </x-back-button>
                        </div>

                        <h1 class="font-cookie font-semibold text-3xl text-triple38 tracking-wider">{{ config('app.name', 'Laragram') }}</h1>


                        <div class="flex items-center justify-end w-8">
                        </div>
                    </div>
                </header>
            </div>
        </nav>

        <!--footer-->
        <nav class="flex flex-col items-stretch order-last z-50">
            <div class="flex flex-col items-stretch h-11">
                <header
                    class="max-w-screen-sm mx-auto fixed bottom-0 left-0 right-0 flex flex-col items-stretch border-t border-solid border-triple219 z-10 bg-white">
                    <a href="{{ route('login') }}">
                        <div class="flex flex-row items-center justify-between h-11 px-4">
                            <div class="flex items-center justify-start w-8">
                            </div>

                            <h1 class="text-sm text-triple38 text-center font-semibold leading-18px">
                                Log Into Another Account
                            </h1>


                            <div class="flex items-center justify-end w-8">
                            </div>
                        </div>
                    </a>
                </header>
            </div>
        </nav>

        <main class="bg-white flex-grow flex flex-col items-stretch">
            <div class="m-10 mb-6">
                <h2 class="text-base text-triple38 text-center font-semibold leading-6 -my-1.5">
                    Email Sent
                </h2>
            </div>

            <div class="text-sm text-triple142 text-center leading-18px mx-10 mb-6">
                <span class="block"
                      style="margin-top: -3px; margin-bottom: -4px;">
                    We sent an email to<span
                        class="text-triple38 font-semibold"> {{ $email }} </span>
                    with a link to get back into your account.
                </span>
            </div>

            <div class="flex items-center justify-center mx-10 mb-6">
                <a class="text-sm text-fb_blue text-center font-semibold leading-18px"
                   href="{{ route('password.request') }}">
                    OK
                </a>
            </div>
        </main>
    </section>
</x-base-layout>
