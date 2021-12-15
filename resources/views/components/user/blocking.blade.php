<div class="flex flex-none flex-row gap-x-2" style="height: 30px;">
    <div class="flex-grow flex flex-col items-stretch">
        <form class="h-full flex flex-col items-stretch" action="{{ route('user.unblock', ['user' => $user]) }}"
              method="POST">@csrf
            <button
                class="bg-fb_blue h-full text-sm text-center text-white font-semibold px-3 border rounded"
                type="submit"
                style="line-height: 26px;">
                Unblock
            </button>
        </form>
    </div>
</div>
