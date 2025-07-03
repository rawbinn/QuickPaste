{{-- format-ignore-start --}}
@props([
    // The name of the slect field for use in the form
    'name',

    // The class to apply to the input field
    'class' => '',

    // The options for the select field
    'options' => [], 

    // The selected value of the select field
    'selectedValue' => '',

    // Is this a required field. Default is false
    'required' => false,
])

@php
    $name = normalize_name($name);
    $required = parse_variable($required);

    if($attributes->has('readonly') || $attributes->has('disabled')) {
        if($attributes->get('readonly') == 'false') $attributes = $attributes->except('readonly');
        if($attributes->get('disabled') == 'false') $attributes = $attributes->except('disabled');
    }
@endphp
{{-- format-ignore-end --}}
    <select
        {{
            $attributes
                ->class('text-xs sm:text-sm border border-[#ccc] bg-[#fff] px-3 py-3 w-full rounded-md text-[#666] focus:outline-none focus:ring-2 focus:ring-[#4d6d66]')
                ->merge([
                    'id' => $name,
                    'name' => $name
                ])
        }}
    >
        @foreach ($options as $value => $text)
            <option
                value="{{ $value }}"
                @selected(old(key: $name, default: $selectedValue) == $value)
            >
                {{ ucfirst($text) }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-600 text-xs mb-3">
            {{ $message }}
        </p>
    @enderror
