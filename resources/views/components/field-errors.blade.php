@if($errors->any())
    <div id="div_errors" class="bg-black absolute left-0 right-0 bottom-11 flex flex-row justify-center z-10 px-5 py-2">
        <div class="flex-grow flex flex-col items-stretch">
            @foreach($errors->all() as $error)
                <h1 class="text-sm text-white font-medium leading-18px">{{ $error }}</h1>
            @endforeach
        </div>
        <button class="self-start text-white leading-18px px-1" type="button"
                onclick="$('#div_errors').addClass('hidden')">
            X
        </button>
    </div>
@endif
