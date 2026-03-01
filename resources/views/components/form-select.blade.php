@props(['name', 'options' => [], 'value' => null])

<select name="{{ $name }}" id="{{ $name }}"
    {{ $attributes->merge(['class' => 'border mt-1 border-gray-300 rounded-lg w-full p-2 bg-white']) }}>

    {{-- Dacă există slot, îl folosim; dacă nu, folosim options --}}
    @if ($slot->isNotEmpty())
        {{ $slot }}
    @else
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @selected(old($name, $value) == $key)>
                {{ $option }}
            </option>
        @endforeach
    @endif

</select>

@error($name)
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
@enderror
