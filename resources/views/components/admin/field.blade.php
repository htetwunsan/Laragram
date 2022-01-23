@props(['attribute', 'rules', 'model'])
@php
$type = null;
$required = false;
$min = null;
$max = null;
$choices = null;
$formattedAttribute = Str::upper(Str::replace('_', ' ', $attribute));
foreach ($rules as $rule) {
    [$rule, $parameters] = Illuminate\Validation\ValidationRuleParser::parse($rule);

    switch ($rule) {
        case 'String':
            $type = 'text';
            break;
        case 'Numeric':
            $type = 'number';
            break;
        case 'Email':
            $type = 'email';
            break;
        case 'Boolean':
            $type = 'checkbox';
            break;
        case 'Image':
            $type = 'image';
            break;
        case 'Date':
            $type = 'date';
            break;
        case 'Url':
            $type = 'url';
            break;
        case 'In':
            $type = 'select';
            break;
    }

    switch ($rule) {
        case 'Min':
            $min = $parameters[0];
            break;
        case 'Max':
            $max = $parameters[0];
            break;
        case 'BeforeOrEqual':
            $max = $parameters[0];
            break;
        case 'In':
            $choices = $parameters;
            break;
        case 'Required':
            $required = true;
            break;
    }
}

@endphp

<td class="py-2 px-2">
    @if ($adminModel['foreignKeys'][$attribute] ?? null)
        <span class="block text-sm">
            {{ Str::upper(class_basename($adminModel['foreignKeys'][$attribute]['model'])) }}@if ($required)*@endif
        </span>
    @else
        <span class="block text-sm">{{ $formattedAttribute }}@if ($required)*@endif</span>
    @endif
</td>
<td class="py-2 px-2 w-auto max-w-sm">
    <div class="flex flex-col items-stretch gap-y-2">
        @if ($adminModel['foreignKeys'][$attribute] ?? null)
            <select class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}">
                @foreach ($adminModel['foreignKeys'][$attribute]['model']::all() as $foreignModel)
                    <option value="{{ $foreignModel->id }}" @if (old($attribute) ?? ($model[$attribute] ?? null) == $foreignModel->id) selected @endif>
                        {{ $foreignModel[$adminModel['foreignKeys'][$attribute]['fieldToShow']] }}</option>
                @endforeach
            </select>
        @elseif ($type == 'text' && !$min && !$max)
            <textarea class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}"
                rows="2" @if ($required) required @endif>{{ old($attribute) ?? ($model[$attribute] ?? null) }}</textarea>
        @elseif ($type == 'select')
            <select class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}"
                @if ($required) required @endif>
                @foreach ($choices as $choice)
                    <option value="{{ $choice }}" @if ($loop->first) selected @endif>{{ Str::upper($choice) }}</option>
                @endforeach
            </select>
        @elseif ($type == 'checkbox')
            <input class="bg-slate-800 text-sm text-sky-400 border-none rounded focus:ring-0 focus:ring-offset-0"
                name="{{ $attribute }}" type="{{ $type }}" @if (old($attribute) ?? ($model[$attribute] ?? null)) checked @endif
                @if ($required) required @endif />
        @elseif ($type == 'image')
            <div class="flex items-center gap-x-2">
                @if (old($attribute) ?? ($model[$attribute] ?? null))
                    <div class="text-sm hover:text-sky-400">
                        <a href="{{ old($attribute) ?? $model[$attribute] }}" target="_blank">View</a>
                    </div>
                @endif
                <input class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}"
                    type="file" accept="image" value="{{ old($attribute) ?? ($model[$attribute] ?? null) }}"
                    @if ($required) required @endif />
            </div>
        @elseif ($type == 'date')
            <input class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}"
                type="{{ $type }}" @if ($min) min="{{ explode(' ', $min)[0] }}" @endif @if ($max) max="{{ explode(' ', $max)[0] }}" @endif
                value="{{ old($attribute) ?? ($model[$attribute] ?? null) }}" @if ($required) required @endif />
        @elseif ($type == 'number')
            <input class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}"
                type="{{ $type }}" @if ($min) min="{{ $min }}" @endif @if ($max) max="{{ $max }}" @endif value="{{ old($attribute) ?? ($model[$attribute] ?? null) }}" @if ($required) required @endif />
        @else
            <input class="w-full bg-slate-800 text-sm border-none rounded focus:ring-0" name="{{ $attribute }}"
                type="{{ $type }}" @if ($min) minlength="{{ $min }}" @endif @if ($max) maxlength="{{ $max }}" @endif value="{{ old($attribute) ?? ($model[$attribute] ?? null) }}" @if ($required) required @endif />
        @endif
        @error($attribute)
            <div class="text-xs text-red-500 text-justify px-2">
                {{ $message }}
            </div>
        @enderror
    </div>
</td>
