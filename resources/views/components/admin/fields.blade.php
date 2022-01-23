@props(['rules', 'model'])
<table class="border-collapse table-auto w-fit">
    <tbody>
        @foreach ($rules as $attribute => $rules)
            <tr>
                <x-admin.field :attribute=$attribute :rules=$rules :model=$model />
            </tr>
        @endforeach
    </tbody>
</table>
