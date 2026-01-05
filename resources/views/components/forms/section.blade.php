@props([
    'section' => null,
    'title',
    'icon' => null,
    'open' => false,
    'highlighted' => false,
    'iconSvg' => null,
])

@php
    $sectionId = $section ?? Str::slug($title);
    $isOpen = $open;
@endphp

<div class="bg-white rounded-xl shadow-sm {{ $highlighted ? 'border-2 border-primary-200' : 'border border-gray-200' }} overflow-hidden"
     x-data="{ 
         open: {{ $isOpen ? 'true' : 'false' }},
         toggle() { this.open = !this.open; }
     }">
    <button 
        type="button"
        @click="toggle()"
        class="w-full px-6 py-4 flex items-center justify-between {{ $highlighted ? 'hover:bg-primary-50 bg-primary-50/50' : 'hover:bg-gray-50' }} transition-colors"
    >
        <h3 class="text-lg font-semibold {{ $highlighted ? 'text-primary-900' : 'text-gray-900' }} flex items-center gap-2">
            @if($iconSvg)
                {!! $iconSvg !!}
            @elseif($icon)
                @if($icon === 'user')
                    <svg class="w-5 h-5 {{ $highlighted ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                @elseif($icon === 'contact')
                    <svg class="w-5 h-5 {{ $highlighted ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                @elseif($icon === 'clinical')
                    <svg class="w-5 h-5 {{ $highlighted ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                @elseif($icon === 'social')
                    <svg class="w-5 h-5 {{ $highlighted ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                @elseif($icon === 'schedule' || $icon === 'time')
                    <svg class="w-5 h-5 {{ $highlighted ? 'text-primary-500' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                @endif
            @endif
            {{ $title }}
        </h3>
        <svg class="w-5 h-5 {{ $highlighted ? 'text-primary-500' : 'text-gray-400' }} transition-transform" 
             :class="{ 'rotate-180': open }" 
             fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="px-6 pb-6">
        {{ $slot }}
    </div>
</div>

