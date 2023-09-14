@props([
        'value'=>'','name','id' =>null
])

<textarea
        name = "{{ $name }}"
        id="{{ $id ?? $name }}"
        {{ $attributes->merge([
            ])
            ->class(['form-control','is-invalid'=>$errors->has($name)]) }}>
                {{ old($name,$value) }}</textarea>