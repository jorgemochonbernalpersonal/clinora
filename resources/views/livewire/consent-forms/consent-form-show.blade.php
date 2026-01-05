<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $consentForm->consent_title ?? $consentForm->consent_type_label }}</h1>
            <p class="text-sm text-gray-500 mt-1">
                Paciente: <strong>{{ $consentForm->contact->full_name }}</strong>
                @if($consentForm->signed_at)
                    | Firmado el {{ $consentForm->signed_at->format('d/m/Y H:i') }}
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Status Badge --}}
            <span class="px-3 py-1 text-sm font-medium rounded-full
                @if($consentForm->isSigned()) bg-green-100 text-green-800
                @elseif($consentForm->isPending()) bg-yellow-100 text-yellow-800
                @elseif($consentForm->isRevoked()) bg-red-100 text-red-800
                @endif">
                @if($consentForm->isSigned()) Firmado
                @elseif($consentForm->isPending()) Pendiente
                @elseif($consentForm->isRevoked()) Revocado
                @endif
            </span>

            {{-- Actions --}}
            <div class="flex gap-2">
                @if($canSign)
                <button wire:click="openSignModal" 
                        class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Firmar
                </button>
                @endif

                @if($consentForm->isSigned() && !$consentForm->isRevoked())
                <button wire:click="openRevokeModal" 
                        class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Revocar
                </button>
                @endif

                <button wire:click="downloadPdf" 
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    PDF
                </button>

                <a href="{{ profession_route('consent-forms.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                    Volver
                </a>
            </div>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    {{-- Consent Form Content --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
        @include($templateView, [
            'professional' => $consentForm->professional,
            'contact' => $consentForm->contact,
            'contactName' => $consentForm->contact->full_name,
            'data' => $this->getTemplateData(),
        ])
    </div>

    {{-- Signature Modal --}}
    @if($showSignModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showSignModal') }" x-show="show" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="show = false"></div>

            {{-- Modal panel --}}
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="sign">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Firmar Consentimiento</h3>
                        
                        <p class="text-sm text-gray-500 mb-4">
                            Por favor, firme el consentimiento para confirmar que ha leído y acepta los términos.
                        </p>

                        {{-- Signature Canvas --}}
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Firma</label>
                            <div class="border-2 border-gray-300 rounded-lg p-4 bg-white" 
                                 x-data="{ 
                                     canvas: null,
                                     ctx: null,
                                     drawing: false,
                                     init() {
                                         this.canvas = this.$refs.signatureCanvas;
                                         this.ctx = this.canvas.getContext('2d');
                                         this.ctx.strokeStyle = '#000';
                                         this.ctx.lineWidth = 2;
                                         this.ctx.lineCap = 'round';
                                         this.ctx.lineJoin = 'round';
                                     },
                                     startDrawing(e) {
                                         this.drawing = true;
                                         const rect = this.canvas.getBoundingClientRect();
                                         this.ctx.beginPath();
                                         this.ctx.moveTo(e.clientX - rect.left, e.clientY - rect.top);
                                     },
                                     draw(e) {
                                         if (!this.drawing) return;
                                         const rect = this.canvas.getBoundingClientRect();
                                         this.ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
                                         this.ctx.stroke();
                                     },
                                     stopDrawing() {
                                         this.drawing = false;
                                         this.$wire.signatureData = this.canvas.toDataURL();
                                     },
                                     clear() {
                                         this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                         this.$wire.signatureData = null;
                                     }
                                 }">
                                <canvas wire:ignore 
                                        x-ref="signatureCanvas"
                                        @mousedown="startDrawing($event)"
                                        @mousemove="draw($event)"
                                        @mouseup="stopDrawing()"
                                        @mouseleave="stopDrawing()"
                                        @touchstart="startDrawing($event.touches[0])"
                                        @touchmove="draw($event.touches[0])"
                                        @touchend="stopDrawing()"
                                        width="500" 
                                        height="200" 
                                        class="w-full border border-gray-300 rounded cursor-crosshair"></canvas>
                                <button type="button" 
                                        @click="clear()"
                                        class="mt-2 text-sm text-gray-600 hover:text-gray-800">
                                    Limpiar firma
                                </button>
                            </div>
                            @error('signatureData') 
                                <span class="text-red-500 text-xs">{{ $message }}</span> 
                            @enderror
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-base font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <svg wire:loading.remove wire:target="sign" class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <svg wire:loading wire:target="sign" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Confirmar Firma
                        </button>
                        <button type="button" 
                                wire:click="closeSignModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- Revoke Modal --}}
    @if($showRevokeModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" x-data="{ show: @entangle('showRevokeModal') }" x-show="show" x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            {{-- Background overlay --}}
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" @click="show = false"></div>

            {{-- Modal panel --}}
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit="revoke">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Revocar Consentimiento</h3>
                        
                        <p class="text-sm text-gray-500 mb-4">
                            ¿Está seguro de que desea revocar este consentimiento? Esta acción no se puede deshacer.
                        </p>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Razón de la revocación (opcional)</label>
                            <textarea wire:model="revocationReason" 
                                      rows="3"
                                      class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500"
                                      placeholder="Explique la razón de la revocación..."></textarea>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                            <svg wire:loading.remove wire:target="revoke" class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <svg wire:loading wire:target="revoke" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Confirmar Revocación
                        </button>
                        <button type="button" 
                                wire:click="closeRevokeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
