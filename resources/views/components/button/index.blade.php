@props([
    'type' => 'submit',
    'isFullWidth' => false,
    'tag' => 'button',
    'disabled' => false
])

@php
    $tag = ($tag !== 'a' && $tag !== 'button') ? 'button' : $tag;
    $isFullWidth = parse_variable($isFullWidth);
    $disabled = parse_variable($disabled);
    $baseClasses = 'px-4 bg-[#4d6d66] hover:bg-[#3b524b] transition-colors duration-300 text-white text-xs sm:text-sm py-3 rounded-md font-semibold';
    $fullWidthClass = $isFullWidth ? 'w-full' : '';
@endphp
                        
<{{ $tag }}
    @if($tag === 'button') type="{{ $type }}" @endif
    @if($disabled) disabled @endif
    {{ $attributes->merge(['class' => "$baseClasses $fullWidthClass"]) }}
>
    {{ $slot }}
</{{ $tag }}>
