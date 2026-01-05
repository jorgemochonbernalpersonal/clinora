@props([
    'type' => 'info', // info, warning, success, error
    'title' => null,
    'sticky' => false,
])

@php
    $typeClasses = [
        'info' => 'bg-blue-50 border-blue-200 text-blue-900',
        'warning' => 'bg-yellow-50 border-yellow-200 text-yellow-900',
        'success' => 'bg-green-50 border-green-200 text-green-900',
        'error' => 'bg-red-50 border-red-200 text-red-900',
    ];
    
    $classes = 'border rounded-lg p-4 ' . $typeClasses[$type];
    if ($sticky) {
        $classes .= ' sticky top-24';
    }
@endphp

<div class="{{ $classes }}">
    @if($title)
        <h4 class="font-semibold mb-2">{{ $title }}</h4>
    @endif
    <div class="text-sm">
        {{ $slot }}
    </div>
</div>

