@props([
    'class' => '',
    'id' => $id ?? $name,
])


{{-- Input --}}
<input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" value="{{ old($name, $value) }}"
    {{ $attributes->merge([
        'class' =>
            'mt-1 w-full border rounded-lg border-gray-300 p-2 focus:border-pink-500 focus:ring-pink-500 ' .
            ($errors->has($name) ? 'border-red-500 focus:border-red-500 focus:ring-red-500 ' : '') .
            $class,
    ]) }} />

{{-- Error --}}
<p class="text-red-500 text-sm mt-1 empty:hidden">{{ $errors->first($name) }}</p>
