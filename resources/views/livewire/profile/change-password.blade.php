<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-text-primary mb-4">Cambiar Contraseña</h3>
    
    <form wire:submit.prevent="changePassword" class="space-y-4">
        {{-- Success Message --}}
        @if($successMessage)
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                <p class="text-sm">{{ $successMessage }}</p>
            </div>
        @endif

        {{-- Error Message --}}
        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <p class="text-sm">{{ $errorMessage }}</p>
            </div>
        @endif

        {{-- Current Password --}}
        <div>
            <label for="current_password" class="block text-sm font-medium text-text-primary mb-2">
                Contraseña Actual *
            </label>
            <input 
                type="password" 
                id="current_password"
                wire:model="current_password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                required
            >
            @error('current_password') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        {{-- New Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-text-primary mb-2">
                Nueva Contraseña *
            </label>
            <input 
                type="password" 
                id="password"
                wire:model="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                required
            >
            <p class="mt-1 text-xs text-text-secondary">
                Mínimo 8 caracteres, al menos una mayúscula y un número
            </p>
            @error('password') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Confirm Password --}}
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-text-primary mb-2">
                Confirmar Nueva Contraseña *
            </label>
            <input 
                type="password" 
                id="password_confirmation"
                wire:model="password_confirmation"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                required
            >
            @error('password_confirmation') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
            @enderror
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end pt-2">
            <button 
                type="submit"
                class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors disabled:opacity-50"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Cambiar Contraseña</span>
                <span wire:loading>Cambiando...</span>
            </button>
        </div>
    </form>
</div>
