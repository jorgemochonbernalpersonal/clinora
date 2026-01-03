<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-text-primary mb-4">Autenticación de Dos Factores (2FA)</h3>
    
    {{-- Success Message --}}
    @if($successMessage)
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
            <p class="text-sm">{{ $successMessage }}</p>
        </div>
    @endif

    {{-- Error Message --}}
    @if($errorMessage)
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            <p class="text-sm">{{ $errorMessage }}</p>
        </div>
    @endif

    {{-- 2FA Disabled State --}}
    @if(!$twoFactorEnabled && !$qrCodeUrl)
        <div class="space-y-4">
            <p class="text-text-secondary text-sm">
                La autenticación de dos factores añade una capa extra de seguridad a tu cuenta. 
                Necesitarás tu contraseña y un código de tu teléfono para iniciar sesión.
            </p>
            
            <button 
                wire:click="startEnable2FA"
                class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="startEnable2FA">Activar 2FA</span>
                <span wire:loading wire:target="startEnable2FA">Preparando...</span>
            </button>
        </div>
    @endif

    {{-- Setup QR Code --}}
    @if($qrCodeUrl && !$twoFactorEnabled)
        <div class="space-y-4">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p class="text-sm text-blue-800 mb-3">
                    <strong>Paso 1:</strong> Escanea este código QR con Google Authenticator o una app similar
                </p>
                
                <div class="flex justify-center bg-white p-4 rounded">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrCodeUrl) }}" 
                         alt="QR Code" 
                         class="w-48 h-48">
                </div>
                
                @if($secret)
                    <div class="mt-4 text-center">
                        <p class="text-xs text-text-secondary mb-1">O introduce este código manualmente:</p>
                        <code class="bg-gray-100 px-3 py-1 rounded text-sm font-mono">{{ $secret }}</code>
                    </div>
                @endif
            </div>

            <form wire:submit.prevent="verifyAndEnable" class="space-y-4">
                <div>
                    <label for="verificationCode" class="block text-sm font-medium text-text-primary mb-2">
                        <strong>Paso 2:</strong> Introduce el código de 6 dígitos de tu app
                    </label>
                    <input 
                        type="text" 
                        id="verificationCode"
                        wire:model="verificationCode"
                        maxlength="6"
                        pattern="[0-9]{6}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 text-center text-2xl tracking-widest font-mono"
                        placeholder="000000"
                        required
                    >
                    @error('verificationCode') 
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit"
                        class="flex-1 bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors disabled:opacity-50"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove wire:target="verifyAndEnable">Verificar y Activar</span>
                        <span wire:loading wire:target="verifyAndEnable">Verificando...</span>
                    </button>
                    
                    <button 
                        type="button"
                        wire:click="cancelSetup"
                        class="px-6 py-2.5 border border-gray-300 rounded-lg font-medium hover:bg-gray-50 transition-colors"
                    >
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    @endif

    {{-- 2FA Enabled State --}}
    @if($twoFactorEnabled)
        <div class="space-y-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                <p class="text-sm text-green-800">
                    ✓ La autenticación de dos factores está <strong>activa</strong>
                </p>
            </div>

            {{-- Recovery Codes --}}
            @if($showRecoveryCodes && count($recoveryCodes) > 0)
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-amber-800 font-semibold mb-2">
                        ⚠️ Códigos de Recuperación
                    </p>
                    <p class="text-xs text-amber-700 mb-3">
                        Guarda estos códigos en un lugar seguro. Puedes usar uno si pierdes acceso a tu app de autenticación.
                    </p>
                    
                    <div class="grid grid-cols-2 gap-2 bg-white p-3 rounded">
                        @foreach($recoveryCodes as $code)
                            <code class="text-xs font-mono bg-gray-100 px-2 py-1 rounded">{{ $code }}</code>
                        @endforeach
                    </div>
                    
                    <button 
                        wire:click="$set('showRecoveryCodes', false)"
                        class="mt-3 text-xs text-amber-700 hover:text-amber-800 underline"
                    >
                        Ocultar códigos
                    </button>
                </div>
            @elseif(count($recoveryCodes) > 0)
                <button 
                    wire:click="$set('showRecoveryCodes', true)"
                    class="text-sm text-primary-600 hover:text-primary-700 underline"
                >
                    Ver códigos de recuperación ({{ count($recoveryCodes) }} disponibles)
                </button>
            @endif

            <button 
                wire:click="disable2FA"
                wire:confirm="¿Estás seguro de que quieres desactivar la autenticación de dos factores?"
                class="bg-red-500 hover:bg-red-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="disable2FA">Desactivar 2FA</span>
                <span wire:loading wire:target="disable2FA">Desactivando...</span>
            </button>
        </div>
    @endif
</div>
