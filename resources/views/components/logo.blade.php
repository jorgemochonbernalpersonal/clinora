@props([
    'size' => 'md', // sm: 48px, md: 64px, lg: 80px
    'class' => '',
    'loading' => 'eager', // eager for above-fold, lazy for below-fold
])

@php
    $sizes = [
        'sm' => ['h' => 48, 'w' => 48, 'class' => 'h-12'],
        'md' => ['h' => 64, 'w' => 64, 'class' => 'h-16'],
        'lg' => ['h' => 80, 'w' => 80, 'class' => 'h-20'],
    ];
    $config = $sizes[$size] ?? $sizes['md'];
    
    // Calculate actual display size considering scale transforms
    $scale = 1.5; // Default scale-150
    if (str_contains($class, 'scale-125')) $scale = 1.25;
    if (str_contains($class, 'scale-150')) $scale = 1.5;
    
    $displayHeight = (int)($config['h'] * $scale);
    $displayWidth = (int)($config['w'] * $scale);
    
    // Generate srcset with appropriate sizes
    // For retina displays, we need 2x the size
    $srcset = [
        asset('images/logo.png') . ' 1x',
        asset('images/logo.png') . ' 2x',
    ];
@endphp

<img 
    src="{{ asset('images/logo.png') }}"
    srcset="{{ implode(', ', $srcset) }}"
    alt="Clinora"
    width="{{ $displayWidth }}"
    height="{{ $displayHeight }}"
    class="{{ $config['class'] }} w-auto mix-blend-multiply {{ $class }}"
    loading="{{ $loading }}"
    decoding="async"
>

