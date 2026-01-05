@props([
    'title',
    'description' => null,
    'cancelRoute' => null,
    'cancelLabel' => 'Cancelar',
    'submitLabel' => null,
    'isEditing' => false,
    'submitIcon' => 'check',
    'loadingTarget' => 'save',
])

<div class="sticky top-0 z-10 bg-gray-50/95 backdrop-blur border-b border-gray-200 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-4 mb-8 flex justify-between items-center shadow-sm">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
        @if($description)
            <p class="text-sm text-gray-500">{{ $description }}</p>
        @endif
    </div>
    <div class="flex gap-4">
        @if($cancelRoute)
            <a href="{{ $cancelRoute }}" 
               class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
                {{ $cancelLabel }}
            </a>
        @endif
        
        <button 
            type="submit" 
            wire:loading.attr="disabled"
            wire:target="{{ $loadingTarget }}"
            class="inline-flex items-center px-6 py-2 bg-primary-600 border border-transparent rounded-lg font-medium text-sm text-white shadow-sm hover:bg-primary-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        >
            @if($submitIcon === 'check')
                <svg wire:loading.remove wire:target="{{ $loadingTarget }}" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            @elseif($submitIcon === 'save')
                <svg wire:loading.remove wire:target="{{ $loadingTarget }}" class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                </svg>
            @endif
            
            <svg wire:loading wire:target="{{ $loadingTarget }}" class="animate-spin h-4 w-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            
            {{ $submitLabel ?? ($isEditing ? 'Guardar Cambios' : 'Crear') }}
        </button>
    </div>
</div>

