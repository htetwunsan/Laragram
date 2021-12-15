<header class="flex flex-row items-stretch mx-4 mt-4 mb-6">
    <div class="flex-shrink-0 flex flex-col items-stretch mr-7">
        <form id="form_update_profile_image" action="{{ route('auth.update.profile-image') }}" method="POST"
              enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <input id="input_profile_image" class="hidden" type="file" accept="image/*"
                   name="profile_image"/>
        </form>
        <button id="btn_add_profile_image"
                style="width: 77px; height: 77px"
                type="button"
                title="Add a profile photo">
            <x-profile-image class="w-full h-full rounded-full" title="Add a profile photo"
                             alt="Add a profile photo">
                {{ $user->profile_image }}
            </x-profile-image>
        </button>
    </div>
    <section class="flex-grow flex flex-col items-stretch">
        <div class="flex flex-col items-stretch mb-3">
            <h2 class="text-triple38 font-light leading-8 -mt-1.5 -mb-1.5 overflow-ellipsis"
                style="font-size: 1.75rem">{{ $user->username }}</h2>
        </div>
        <div class="flex flex-col items-stretch" style="max-width: 250px; tab-index: 0">
            <a class="text-sm text-triple38 text-center font-semibold leading-18px border border-solid border-triple219 rounded"
               href="{{ route('auth.edit') }}"
               style="padding:5px 9px;">Edit
                Profile</a>
        </div>
    </section>
</header>

<div class="px-4 pb-5">
    <h1 class="text-sm text-triple38 font-semibold leading-5 pb-px">{{ $user->name }}</h1>
    @if (! is_null($user->bio))
        <span class="block text-sm text-triple38 font-normal leading-5">{{ $user->bio }}</span>
    @endif
</div>

<ul class="flex flex-row justify-around list-none list-outside py-3 border-t border-solid border-triple219">
    <li class="text-sm text-triple142 text-center font-normal leading-18px w-1/3">
        <span class="block text-triple38 font-semibold">{{ $user->posts_count }}</span>
        @choice('post|posts', $user->posts_count)
    </li>

    <li class="text-sm text-triple142 text-center font-normal leading-18px w-1/3">
        @if($user->followers_count > 0)
            <a href="{{ route('user.followers', ['user' => $user->username]) }}">
                <span class="block text-triple38 font-semibold">{{ $user->followers_count }}</span>
                @choice('follower|followers', $user->followers_count)
            </a>
        @else
            <span class="block text-triple38 font-semibold">{{ $user->followers_count }}</span>
            @choice('follower|followers', $user->followers_count)
        @endif

    </li>

    <li class="text-sm text-triple142 text-center font-normal leading-18px w-1/3">
        @if($user->followings_count > 0)
            <a href="{{ route('user.followings', ['user' => $user->username]) }}">
                <span class="block text-triple38 font-semibold">{{ $user->followings_count }}</span>
                following
            </a>
        @else
            <span class="block text-triple38 font-semibold">{{ $user->followings_count }}</span>
            following
        @endif
    </li>
</ul>

<x-app-dialog id="div_confirm_profile_image_dialog">
    <div class="flex items-center justify-center m-8">
        <h3 class="text-lg text-triple38 text-center font-semibold leading-6"
            style="margin-top: -4px; margin-bottom: -6px;">
            Change Profile Photo
        </h3>
    </div>

    <button class="text-sm text-fb_blue text-center font-bold h-12 py-1 px-2 border-t border-triple219"
            type="button"
            tabindex="0"
            onclick="window.toggleDialog($('#div_confirm_profile_image_dialog')); $('#input_profile_image').trigger('click');">
        Upload photo
    </button>

    <form class="flex flex-col items-stretch"
          method="POST"
          action="{{ route('auth.destroy.profile-image') }}">@csrf @method('DELETE')
        <button class="text-sm text-error text-center font-bold h-12 py-1 px-2 border-t border-triple219"
                type="submit"
                tabindex="0">
            Remove Current Photo
        </button>
    </form>

    <button class="btn_cancel text-sm text-triple38 text-center font-bold h-12 py-1 px-2 border-t border-triple219"
            type="button"
            tabindex="0"
            onclick="window.toggleDialog($('#div_confirm_profile_image_dialog'));">
        Cancel
    </button>
</x-app-dialog>

@once
    @push('scripts')
        <script>
            $(document).ready(function () {
                const profileImage = '{{ $user->profile_image }}';

                const $btnAddProfileImage = $('#btn_add_profile_image');

                $btnAddProfileImage.click(function () {
                    if (profileImage) {
                        window.toggleDialog($('#div_confirm_profile_image_dialog'));
                    } else {
                        $inputProfileImage.trigger('click');
                    }
                });

                const $inputProfileImage = $('#input_profile_image');
                const $form = $('#form_update_profile_image');

                $inputProfileImage.change(function () {
                    $form.submit();
                });
            });
        </script>
    @endpush
@endonce
