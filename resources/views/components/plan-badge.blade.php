@props(['plan' => 'pro'])

@php
    $styles = match(strtolower($plan)) {
        'pro' => 'bg-gradient-to-r from-primary-500 to-primary-600 text-white',
        'equipo' => 'bg-gradient-to-r from-gray-700 to-gray-800 text-white',
        default => 'bg-gray-100 text-gray-700',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-semibold $styles"]) }}>
    {{ strtoupper($plan) }}
</span>
