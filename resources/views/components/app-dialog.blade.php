<div
    {{ $attributes }} class="bg-black bg-opacity-65 fixed inset-0 flex flex-col items-center justify-around z-1000 dialog-container">
    <div
        class="bg-white flex flex-col items-stretch justify-center m-5 dialog"
        style="width: 260px; border-radius: 12px">
        {{ $slot }}
    </div>
</div>
