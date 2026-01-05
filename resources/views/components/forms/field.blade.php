@props([
    'name',
    'label' => null,
    'required' => false,
    'help' => null,
    'error' => null,
])

@php
    // Get error from Livewire errors if name is provided
    if ($name && !$error) {
        $error = $errors->first($name);
    }
@endphp

<div {{ $attributes->merge(['class' => '']) }}>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-2">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    {{ $slot }}
    
    @if($help && !$error)
        <p class="mt-1 text-xs text-gray-500">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="mt-1 text-xs text-red-600 flex items-center gap-1">
            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ $error }}
        </p>
    @endif
</div>

