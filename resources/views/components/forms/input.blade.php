{{-- format-ignore-start --}}
@props([
    // The name of the input field for use in the form
    'name',

    // The type of input field
    // This can be text, email, password, number, search, tel
    'type' => 'text',

    // The label for the display of the input field
    'label' => '',

    // The class to apply to the input field
    'class' => '',

    // Value of the input field
    'selectedValue' => '',

    // is this a required field. Default is false
    'required' => false,

    // placeholder text
    'placeholder' => '',

    // This is used to specify the autocomplete attribute for the input field
    // This can be on, off, or a specific value
    'autocomplete' => 'on',

    'disabled' => false,

    'readonly' => false,
])

@php
    $name = normalize_name($name);
    $required = parse_variable($required);
    $disabled = parse_variable($disabled);
    $readonly = parse_variable($readonly);
@endphp
{{-- format-ignore-end --}}

    <input
        {{
            $attributes
                ->class('text-xs sm:text-sm border border-[#ccc] bg-[#fff] px-3 py-3 w-full rounded-md text-[#666] focus:outline-none focus:ring-2 focus:ring-[#4d6d66]')
                ->merge([
                    'type' => $type,
                    'id' => $name,
                    'name' => $name,
                    'placeholder' => $placeholder,
                    'autocomplete' => $autocomplete,
                    'aria-label' => $label,
                    'aria-labelledby' => $name . '-label',
                    'aria-describedby' => $name . '-feedback',
                ])
                ->when($required, fn ($attrs) => $attrs->merge(['required' => true, 'aria-required' => true]))
                ->when($readonly, fn ($attrs) => $attrs->merge(['readonly' => true, 'aria-readonly' => true]))
                ->when($disabled, fn ($attrs) => $attrs->merge(['disabled' => true, 'aria-disabled' => true]))
        }}
    />

    @error($name)
        <p class="text-red-600 text-xs mt-2 mb-3">
            {{ $message }}
        </p>
    @enderror