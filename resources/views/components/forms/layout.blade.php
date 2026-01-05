@props([
    'maxWidth' => 'max-w-7xl',
])

<div class="{{ $maxWidth }} mx-auto px-4 sm:px-6 lg:px-8 py-6">
    <form {{ $attributes->whereStartsWith('wire:') }}>
        {{ $slot }}
        
        @if(isset($main) || isset($sidebar))
            {{-- Main Content and Sidebar Layout --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Main Content (2/3 width) --}}
                @if(isset($main))
                <div class="lg:col-span-2 space-y-6">
                    {{ $main }}
                </div>
                @endif
                
                {{-- Sidebar (1/3 width, sticky) --}}
                @if(isset($sidebar))
                <div class="space-y-6">
                    {{ $sidebar }}
                </div>
                @endif
            </div>
        @else
            {{-- No layout, just render slot --}}
            {{ $slot }}
        @endif
    </form>
</div>

