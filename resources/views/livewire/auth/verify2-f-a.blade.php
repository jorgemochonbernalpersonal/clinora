<div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-xl p-8">
        {{-- Icon --}}
        <div class="flex justify-center mb-6">
            <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
        </div>

        {{-- Title --}}
        <h2 class="text-2xl font-bold text-center text-text-primary mb-2">
            Verificación en Dos Pasos
        </h2>
        <p class="text-center text-text-secondary mb-6">
            @if($useRecoveryCode)
                Introduce un código de recuperación
            @else
                Introduce el código de tu aplicación de autenticación
            @endif
        </p>

        {{-- Error Message --}}
        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                <p class="text-sm">{{ $errorMessage }}</p>
            </div>
        @endif

        {{-- Verification Form --}}
        <form wire:submit.prevent="verify" class="space-y-6">
            <div>
                <label for="code" class="block text-sm font-medium text-text-primary mb-2">
                    @if($useRecoveryCode)
                        Código de Recuperación
                    @else
                        Código de Verificación
                    @endif
                </label>
                <input 
                    type="text" 
                    id="code"
                    wire:model="code"
                    maxlength="{{ $useRecoveryCode ? 10 : 6 }}"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center text-2xl tracking-widest font-mono"
                    placeholder="{{ $useRecoveryCode ? 'xxxxx-xxxxx' : '000000' }}"
                    required
                    autofocus
                >
                @error('code') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                @enderror
            </div>

            {{-- Submit Button --}}
            <button 
                type="submit"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors disabled:opacity-50"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Verificar</span>
                <span wire:loading>Verificando...</span>
            </button>
        </form>

        {{-- Toggle Recovery Code --}}
        <div class="mt-6 text-center">
            <button 
                type="button"
                wire:click="toggleRecoveryCode"
                class="text-sm text-primary-600 hover:text-primary-700"
            >
                @if($useRecoveryCode)
                    Usar código de autenticación
                @else
                    Usar código de recuperación
                @endif
            </button>
        </div>

        {{-- Back to Login --}}
        <div class="mt-4 text-center">
            <a href="{{ route('login') }}" class="text-sm text-text-secondary hover:text-text-primary">
                ← Volver al inicio de sesión
            </a>
        </div>
    </div>
</div>
