<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-text-primary mb-4">Preferencias</h3>

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

    <form wire:submit.prevent="save" class="space-y-6">
        {{-- Apariencia --}}
        <div>
            <h4 class="font-medium text-text-primary mb-3">Apariencia</h4>
            <div>
                <label for="theme" class="block text-sm font-medium text-text-primary mb-2">
                    Tema
                </label>
                <select 
                    id="theme"
                    wire:model="theme"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                >
                    <option value="light">Claro</option>
                    <option value="dark">Oscuro</option>
                    <option value="auto">Automático (Sistema)</option>
                </select>
                <p class="mt-1 text-xs text-text-secondary">
                    Elige el tema de la interfaz
                </p>
            </div>
        </div>

        {{-- Notificaciones --}}
        <div>
            <h4 class="font-medium text-text-primary mb-3">Notificaciones</h4>
            <div class="space-y-3">
                <label class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        wire:model="notifications_enabled"
                        class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                    >
                    <div>
                        <span class="text-sm font-medium text-text-primary">Activar notificaciones</span>
                        <p class="text-xs text-text-secondary">Recibe alertas dentro de la aplicación</p>
                    </div>
                </label>

                <label class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        wire:model="email_notifications"
                        class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                    >
                    <div>
                        <span class="text-sm font-medium text-text-primary">Notificaciones por email</span>
                        <p class="text-xs text-text-secondary">Recibe correos sobre eventos importantes</p>
                    </div>
                </label>

                <label class="flex items-center gap-3">
                    <input 
                        type="checkbox" 
                        wire:model="sms_notifications"
                        class="w-5 h-5 text-primary-600 border-gray-300 rounded focus:ring-primary-500"
                    >
                    <div>
                        <span class="text-sm font-medium text-text-primary">Notificaciones por SMS</span>
                        <p class="text-xs text-text-secondary">Recibe mensajes SMS (próximamente)</p>
                    </div>
                </label>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="flex justify-end pt-2">
            <button 
                type="submit"
                class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-medium transition-colors disabled:opacity-50"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Guardar Preferencias</span>
                <span wire:loading>Guardando...</span>
            </button>
        </div>
    </form>
</div>
