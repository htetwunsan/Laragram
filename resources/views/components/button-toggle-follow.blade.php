<div class="flex items-center justify-center">
    <button
        @class([
        'btn_follow',
        'bg-fb_blue',
        'border',
        'border-transparent',
        'rounded',
        'text-sm',
        'text-white',
        'text-center',
        'font-semibold',
        'leading-18px',
        'relative',
        'hidden' => $is_following
        ])
        style="padding: 5px 9px;"
        type="button">
        <span>Follow</span>
        <svg class="absolute inset-0 m-auto w-4 h-4 text-white animate-spin hidden"
             xmlns="http://www.w3.org/2000/svg" fill="none"
             viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor"
                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
            </path>
        </svg>
    </button>
    <button
        @class([
          'btn_unfollow',
          'bg-transparent',
          'border',
          'border-triple219',
          'rounded',
          'text-sm',
          'text-triple38',
          'text-center',
          'font-semibold',
          'leading-18px',
          'hidden' => ! $is_following
        ])
        style="padding: 5px 9px;"
        type="button">
        Following
    </button>
</div>
