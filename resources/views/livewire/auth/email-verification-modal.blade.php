<div 
    @if($show) style="display: flex;" @else style="display: none;" @endif
    class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4"
>
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6 transform transition-all">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-text-primary">
                        Verifica tu Email
                    </h3>
                </div>
            </div>
            {{-- No permitir cerrar el modal si el email no está verificado --}}
            @if(auth()->check() && !auth()->user()?->hasVerifiedEmail())
                <div class="w-5 h-5"></div>
            @else
                <button 
                    wire:click="close"
                    class="text-text-secondary hover:text-text-primary transition-colors"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Content --}}
        <div class="mb-6">
            <p class="text-text-secondary mb-4">
                Hemos enviado un enlace de verificación a <strong class="text-text-primary">{{ auth()->user()?->email ?? '' }}</strong>
            </p>

            {{-- Success Message --}}
            @if($successMessage)
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium">{{ $successMessage }}</p>
                    </div>
                </div>
            @endif

            {{-- Error Message --}}
            @if($errorMessage)
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                        <p class="text-sm font-medium">{{ $errorMessage }}</p>
                    </div>
                </div>
            @endif

            {{-- Instructions --}}
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <p class="text-sm font-medium text-blue-900 mb-1">Importante</p>
                        <p class="text-sm text-blue-800">
                            Por favor, verifica tu email en las próximas <strong>24 horas</strong> para acceder a todas las funcionalidades de Clinora. 
                            Revisa tu bandeja de entrada y haz clic en el enlace de verificación.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex gap-3">
            <button 
                wire:click="resend"
                class="flex-1 bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors disabled:opacity-50"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Reenviar Email</span>
                <span wire:loading>Enviando...</span>
            </button>
            {{-- No permitir cerrar el modal si el email no está verificado --}}
            @if(auth()->check() && !auth()->user()?->hasVerifiedEmail())
                <div class="px-4 py-2"></div>
            @else
                <button 
                    wire:click="close"
                    class="px-4 py-2 border border-gray-300 text-text-primary rounded-lg hover:bg-gray-50 transition-colors"
                >
                    Cerrar
                </button>
            @endif
        </div>
    </div>
</div>
