@props(['title', 'submit', 'maxWidth' => '4xl'])

<div class="max-w-{{ $maxWidth }} mx-auto py-10 sm:px-6 lg:px-8">
    <div class="flex items-center justify-between mb-6">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $title }}
        </h2>
        
        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Volver
        </a>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <form wire:submit="{{ $submit }}">
            <div class="p-6 bg-white border-b border-gray-200">
                {{ $slot }}
            </div>

            @if(isset($actions))
            <div class="px-4 py-3 bg-gray-50 flex justify-end items-center gap-4 sm:px-6 border-t border-gray-100">
                {{ $actions }}
            </div>
            @endif
        </form>
    </div>
</div>
