@props([
    'type' => 'text',
    'name' => null,
    'placeholder' => null,
    'value' => null,
    'required' => false,
    'error' => false,
    'size' => 'base', // sm, base, lg
])

@php
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'base' => 'px-4 py-2.5 text-base',
        'lg' => 'px-5 py-3 text-lg',
    ];
    
    $baseClasses = 'block w-full rounded-lg border border-gray-300 shadow-sm focus:border-primary-500 focus:ring-2 focus:ring-primary-500 focus:ring-opacity-20';
    $errorClasses = 'border-red-300 focus:border-red-500 focus:ring-red-500';
    
    // Check for error if name is provided
    if ($name && !$error) {
        $error = $errors->has($name);
    }
    
    $classes = $baseClasses . ' ' . $sizeClasses[$size] . ($error ? ' ' . $errorClasses : '');
    
    // Merge with custom classes from attributes
    if ($attributes->has('class')) {
        $classes = $classes . ' ' . $attributes->get('class');
    }
@endphp

<input 
    type="{{ $type }}"
    @if($name) wire:model.blur="{{ $name }}" @endif
    @if($value) value="{{ $value }}" @endif
    @if($placeholder) placeholder="{{ $placeholder }}" @endif
    @if($required) required @endif
    {{ $attributes->merge(['class' => $classes])->except('class') }}
    class="{{ $classes }}"
>
