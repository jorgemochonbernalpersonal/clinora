<div>
    <form wire:submit.prevent="login" class="space-y-6">
        {{-- Error Message --}}
        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <p class="text-sm">{{ $errorMessage }}</p>
            </div>
        @endif

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-text-primary mb-2">
                Email
            </label>
            <input 
                type="email" 
                id="email"
                wire:model="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                placeholder="tu@email.com"
                required
            >
            @error('email') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="block text-sm font-medium text-text-primary mb-2">
                Contraseña
            </label>
            <input 
                type="password" 
                id="password"
                wire:model="password"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"
                placeholder="••••••••"
                required
            >
            @error('password') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember & Forgot --}}
        <div class="flex items-center justify-between">
            <label class="flex items-center">
                <input 
                    type="checkbox" 
                    wire:model="remember"
                    class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                >
                <span class="ml-2 text-sm text-text-secondary">Recordarme</span>
            </label>
            
            <a href="#" class="text-sm text-primary-600 hover:text-primary-700">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

        {{-- Submit Button --}}
        <button 
            type="submit"
            class="w-full bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Iniciar Sesión</span>
            <span wire:loading>Iniciando...</span>
        </button>
    </form>
</div>
