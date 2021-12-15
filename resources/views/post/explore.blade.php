<x-base-layout>
    <section id="section_main" class="h-full flex flex-col items-stretch overflow-y-auto no-scrollbar relative">
        <!--header-->
        <nav class="flex flex-col items-stretch order-first z-50">
            <div class="flex flex-col items-stretch h-11">
                <header
                    class="mx-auto absolute top-0 left-0 right-0 flex flex-col items-stretch border-b border-solid border-triple219 z-10 bg-white">
                    <div class="w-full flex flex-row items-center h-11 px-4">
                        <label class="flex-grow flex relative">
                            <input
                                id="input_search"
                                class="flex-grow text-sm text-triple38 leading-5 placeholder-transparent whitespace-nowrap pr-3 py-1 border border-triple219 rounded-md focus:ring-0 focus:border-triple219"
                                style="padding-left: 22px;"
                                autocapitalize="none"
                                autocomplete="off"
                                placeholder="Search"
                                spellcheck="true"
                                type="search"
                                value="">
                            <div
                                class="absolute top-0 left-1/2 flex flex-row items-center justify-center px-2 transform -translate-x-1/2 transition-all duration-150 ease-out">
                                <span class="block search"></span>
                                <span class="block text-sm text-triple142 text-center leading-7"
                                      style="margin-left: 5px;">
                                    Search
                                </span>
                            </div>
                            <button id="btn_search_clear"
                                    class="absolute top-0 right-0 bottom-0 flex items-center justify-center pr-1 hidden"
                                    type="button">
                                <span class="block input-clear"></span>
                            </button>
                        </label>
                        <button id="btn_search_cancel"
                                class="text-sm text-triple38 text-center font-semibold leading-18px ml-3 hidden"
                                type="button">
                            Cancel
                        </button>
                    </div>
                </header>
            </div>
        </nav>

        <x-app-bottom-navigation>search</x-app-bottom-navigation>

        <main class="flex-grow flex flex-col items-stretch overflow-y-hidden" role="main">
            @component('components.post.posts-images-container', ['posts' => $posts])@endcomponent

            <ul id="div_search_results"
                class="flex flex-col items-stretch justify-start mt-2 list-none list-outside overflow-y-hidden hidden">
            </ul>
        </main>
    </section>
    @once
        @push('scripts')
            <script>
                $(document).ready(function () {
                    const $inputSearch = $('#input_search');
                    const $btnSearchClear = $('#btn_search_clear');
                    const $btnSearchCancel = $('#btn_search_cancel');

                    const $postsContainer = $('#posts_container');
                    const $divSearchResults = $('#div_search_results');

                    function togglePlaceholder() {
                        if ($inputSearch.val().length > 0) {
                            $inputSearch.next().find('span').last().addClass('opacity-0 pointer-events-none');
                        } else {
                            $inputSearch.next().find('span').last().removeClass('opacity-0 pointer-events-none');
                        }
                    }

                    function toggleButtonClear() {
                        if ($inputSearch.val().length > 0) {
                            $btnSearchClear.removeClass('hidden');
                        } else {
                            $btnSearchClear.addClass('hidden');
                        }
                    }

                    let initialPageUrl = '{{ route('user.search') }}';

                    let nextPageUrl = null;
                    let paginatingInProgress = false;

                    function paginateSearch() {
                        if (paginatingInProgress) return;
                        if (!nextPageUrl) return;

                        axios.get(nextPageUrl, {params: {q: $inputSearch.val()}})
                            .then(response => {
                                nextPageUrl = response.data.next_page_url;
                                $divSearchResults.append(response.data.html);
                            })
                            .finally(function () {
                                paginatingInProgress = false;
                            });
                    }

                    function initialSearch() {
                        const q = $inputSearch.val();

                        if (q.length <= 0) return;
                        axios.get(initialPageUrl, {params: {q: q}})
                            .then(response => {
                                nextPageUrl = response.data.next_page_url;
                                $divSearchResults.html(response.data.html);
                            });
                    }

                    function showDivSearch() {
                        $postsContainer.addClass('hidden');
                        $postsContainer.removeClass('overflow-y-auto');
                        $postsContainer.addClass('overflow-y-hidden');

                        $divSearchResults.removeClass('hidden');
                        $divSearchResults.addClass('overflow-y-auto');
                        $divSearchResults.removeClass('overflow-y-hidden');
                    }

                    function dismissDivSearch() {
                        $postsContainer.removeClass('hidden');
                        $postsContainer.addClass('overflow-y-auto');
                        $postsContainer.removeClass('overflow-y-hidden');

                        $divSearchResults.addClass('hidden');
                        $divSearchResults.removeClass('overflow-y-auto');
                        $divSearchResults.addClass('overflow-y-hidden');
                    }

                    $inputSearch.focusin(function () {
                        $inputSearch.next().css('left', 0);
                        $inputSearch.next().css('transform', 'translateX(0)');
                        $btnSearchCancel.removeClass('hidden');

                        showDivSearch();
                    });

                    $inputSearch.focusout(function () {
                        if ($inputSearch.val().length > 0) return;
                        $inputSearch.next().removeAttr('style');
                        $btnSearchCancel.addClass('hidden');

                        dismissDivSearch();
                    });

                    $inputSearch.on('input change', function () {
                        togglePlaceholder();
                        toggleButtonClear();
                    });

                    $inputSearch.on('input change', _.debounce(initialSearch, 1000));

                    $btnSearchClear.click(function () {
                        $inputSearch.val("").change();
                    });

                    $btnSearchCancel.click(function () {
                        $inputSearch.val("").change();
                        $inputSearch.blur();
                    });

                    $divSearchResults.scroll(_.throttle(paginateSearch, 500));
                });
            </script>
        @endpush
    @endonce
</x-base-layout>
