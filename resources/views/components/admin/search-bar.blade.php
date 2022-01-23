<div class="bg-slate-800 flex-grow flex flex-col items-stretch rounded-lg max-w-sm">
    <form id="form_search" class="flex-none flex items-center justify-center" method="GET">
        <input class="flex-grow bg-transparent text-sm border-none focus:ring-0 px-2" name="search" type="search"
            autocapitalize="none" autocomplete="off" placeholder="Search..." spellcheck="true"
            value="{{ Request::get('search') }}" />

        <button class="flex items-center justify-center mr-1 hover:text-sky-400" type="submit">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                stroke-linejoin="round" class="flex-non" aria-hidden="true">
                <path d="m19 19-3.5-3.5"></path>
                <circle cx="11" cy="11" r="6"></circle>
            </svg>
        </button>

        @if (Request::get('search'))
            <a class="flex items-center justify-center mr-1 hover:text-sky-400"
                href="{{ Request::fullUrlWithoutQuery('search') }}" title="Clear all search">
                <span class="material-icons-outlined">
                    close
                </span>
            </a>
        @endif
    </form>

    @push('scripts')
        <script>
            $(document).ready(function() {
                const $form = $('#form_search');
                const fullUrl = "{{ Request::fullUrlWithoutQuery('search') }}";
                $form.submit(function(e) {
                    e.preventDefault();
                    const input = $(this).find('input[type=search]');
                    const query = input.attr('name') + '=' + input.val();
                    const action = fullUrl.includes('?') ? '&' + query : '?' + query;
                    $(this).attr('action', fullUrl + action);
                    this.submit();
                });
            });
        </script>
    @endpush
</div>
