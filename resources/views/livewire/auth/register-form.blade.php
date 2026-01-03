<div>
    <form wire:submit.prevent="register" class="space-y-5">
        {{-- Error Message --}}
        @if($errorMessage)
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                <p class="text-sm">{{ $errorMessage }}</p>
            </div>
        @endif

        {{-- Name Fields --}}
        <div class="grid md:grid-cols-2 gap-4">
            <div>
                <label for="first_name" class="block text-sm font-medium text-text-primary mb-2">
                    Nombre *
                </label>
                <input 
                    type="text" 
                    id="first_name"
                    wire:model="first_name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    required
                >
                @error('first_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="last_name" class="block text-sm font-medium text-text-primary mb-2">
                    Apellidos *
                </label>
                <input 
                    type="text" 
                    id="last_name"
                    wire:model="last_name"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                    required
                >
                @error('last_name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>

        {{-- Email --}}
        <div>
            <label for="email" class="block text-sm font-medium text-text-primary mb-2">
                Email *
            </label>
            <input 
                type="email" 
                id="email"
                wire:model="email"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="tu@email.com"
                required
            >
            @error('email') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Phone --}}
        <div>
            <label for="phone" class="block text-sm font-medium text-text-primary mb-2">
                Teléfono *
            </label>
            <input 
                type="tel" 
                id="phone"
                wire:model="phone"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="+34 600 123 456"
                required
            >
            @error('phone') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Professional Number (Optional) --}}
        <div>
            <label for="professional_number" class="block text-sm font-medium text-text-primary mb-2">
                Nº Colegiado <span class="text-text-secondary font-normal">(opcional)</span>
            </label>
            <input 
                type="text" 
                id="professional_number"
                wire:model="professional_number"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                placeholder="Ej: M-12345, B-67890">
            <p class="mt-1 text-xs text-text-secondary">
                Número del Colegio Oficial de Psicólogos. Puedes añadirlo más tarde.
            </p>
            @error('professional_number') 
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password Fields --}}
        <div x-data="{ showPassword: false, showPasswordConfirmation: false }">
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-text-primary mb-2">
                    Contraseña *
                </label>
                <div class="relative">
                    <input 
                        :type="showPassword ? 'text' : 'password'" 
                        id="password"
                        wire:model="password"
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        required
                        minlength="8">
                    <button 
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                <p class="mt-1 text-xs text-text-secondary">
                    Mínimo 8 caracteres
                </p>
                @error('password') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-text-primary mb-2">
                    Confirmar Contraseña *
                </label>
                <div class="relative">
                    <input 
                        :type="showPasswordConfirmation ? 'text' : 'password'" 
                        id="password_confirmation"
                        wire:model="password_confirmation"
                        class="w-full px-4 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                        required
                        minlength="8">
                    <button 
                        type="button"
                        @click="showPasswordConfirmation = !showPasswordConfirmation"
                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                    </button>
                </div>
                @error('password_confirmation') 
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        {{-- Terms --}}
        <div>
            <label class="flex items-start gap-3">
                <input 
                    type="checkbox" 
                    wire:model="terms_accepted"
                    class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500 mt-0.5"
                    required
                >
                <span class="text-sm text-text-secondary">
                    Acepto los <a href="#" class="text-primary-600 hover:text-primary-700">términos y condiciones</a> 
                    y la <a href="#" class="text-primary-600 hover:text-primary-700">política de privacidad</a>
                </span>
            </label>
            @error('terms_accepted') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Submit Button --}}
        <button 
            type="submit"
            class="w-full bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors shadow-lg hover:shadow-xl disabled:opacity-50"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>Crear Cuenta Gratis</span>
            <span wire:loading>Creando cuenta...</span>
        </button>
    </form>
</div>
