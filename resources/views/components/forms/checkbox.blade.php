@props([
    'name' => null,
    'label' => null,
    'value' => null,
    'checked' => false,
    'help' => null,
])

<div class="flex items-start gap-3">
    <div class="flex h-5 items-center mt-0.5">
        <input 
            type="checkbox"
            @if($name) wire:model="{{ $name }}" @endif
            @if($value) value="{{ $value }}" @endif
            @if($checked) checked @endif
            class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500"
            {{ $attributes }}
        >
    </div>
    @if($label || $slot->isNotEmpty())
        <div class="flex-1">
            @if($label)
                <label class="font-medium text-gray-700 block">{{ $label }}</label>
            @endif
            @if($slot->isNotEmpty())
                <div class="text-sm text-gray-500 mt-1">
                    {{ $slot }}
                </div>
            @endif
            @if($help)
                <p class="text-sm text-gray-500 mt-1">{{ $help }}</p>
            @endif
        </div>
    @endif
</div>

