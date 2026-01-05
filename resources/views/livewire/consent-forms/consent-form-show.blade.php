<div>
    {{-- Contenedor principal con ancho máximo para mantener alineación con sidebar --}}
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="mb-6 flex flex-wrap justify-between items-center gap-4">
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
                        Descargar PDF
                    </button>

                    <button wire:click="printPdf" 
                            class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        Imprimir
                    </button>

                    @if($consentForm->isSigned() && !$consentForm->isRevoked())
                    <button wire:click="sendEmail" 
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        @if($consentForm->isDelivered())
                            Reenviar Email
                        @else
                            Enviar Email
                        @endif
                    </button>
                    @endif

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
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden w-full">
            {{-- Estilos scoped del consentimiento --}}
            @if($this->getConsentStyles())
            <style>
                .consent-content-wrapper {
                    {!! preg_replace('/body\s*\{/i', '.consent-content-wrapper {', $this->getConsentStyles()) !!}
                }
            </style>
            @endif
            
            {{-- Mostrar el contenido del consentimiento guardado --}}
            <div class="p-8 prose prose-lg max-w-none w-full consent-content-wrapper
                        prose-headings:text-gray-900 prose-p:text-gray-700 prose-strong:text-gray-900
                        prose-ul:text-gray-700 prose-li:text-gray-700
                        [&_h1]:text-2xl [&_h1]:font-bold [&_h1]:mb-4 [&_h1]:border-b [&_h1]:border-primary-500 [&_h1]:pb-2
                        [&_h2]:text-xl [&_h2]:font-semibold [&_h2]:mt-6 [&_h2]:mb-3 [&_h2]:text-gray-800
                        [&_h3]:text-lg [&_h3]:font-semibold [&_h3]:mt-4 [&_h3]:mb-2
                        [&_.section]:mb-6 [&_.section]:p-4 [&_.section]:bg-gray-50 [&_.section]:border-l-4 [&_.section]:border-primary-500 [&_.section]:rounded
                        [&_.info-box]:bg-blue-50 [&_.info-box]:border [&_.info-box]:border-blue-200 [&_.info-box]:p-4 [&_.info-box]:rounded-lg [&_.info-box]:my-4
                        [&_.warning-box]:bg-yellow-50 [&_.warning-box]:border [&_.warning-box]:border-yellow-200 [&_.warning-box]:p-4 [&_.warning-box]:rounded-lg [&_.warning-box]:my-4
                        [&_.security-box]:bg-green-50 [&_.security-box]:border [&_.security-box]:border-green-200 [&_.security-box]:p-4 [&_.security-box]:rounded-lg [&_.security-box]:my-4
                        [&_.signature-section]:mt-8 [&_.signature-section]:pt-6 [&_.signature-section]:border-t [&_.signature-section]:border-gray-300
                        [&_ul]:ml-6 [&_ul]:list-disc [&_ul]:space-y-2
                        [&_ol]:ml-6 [&_ol]:list-decimal [&_ol]:space-y-2
                        [&_meta]:!hidden
                        [&_style]:!hidden
                        [&_head]:!hidden
                        [&_title]:!hidden
                        [&_html]:contents
                        [&_body]:contents">
                {!! $this->getConsentBodyContent() !!}
            </div>

            {{-- Sección de Firma Visible (solo si está firmado) --}}
            @if($consentForm->isSigned())
            <div class="p-8 border-t-4 border-green-500 bg-green-50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="flex items-center justify-center w-12 h-12 bg-green-600 text-white rounded-full">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Documento Firmado Digitalmente</h3>
                        <p class="text-sm text-gray-600">Este consentimiento ha sido firmado electrónicamente</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-lg border border-green-200">
                    {{-- Información del Paciente y Firma --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Paciente</h4>
                        <p class="text-gray-700 font-medium">{{ $consentForm->contact->full_name }}</p>
                        @if($consentForm->contact->dni)
                        <p class="text-sm text-gray-600">DNI: {{ $consentForm->contact->dni }}</p>
                        @endif

                        <div class="mt-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Firma digital:</p>
                            @if($consentForm->patient_signature_data)
                            <div class="border-2 border-gray-300 bg-white p-2 rounded inline-block">
                                <img src="{{ $consentForm->patient_signature_data }}" 
                                     alt="Firma del paciente" 
                                     class="max-w-xs max-h-24 object-contain">
                            </div>
                            @else
                            <p class="text-sm text-gray-500 italic">Sin imagen de firma</p>
                            @endif
                        </div>
                    </div>

                    {{-- Metadatos de la Firma --}}
                    <div>
                        <h4 class="font-semibold text-gray-900 mb-3">Información de Firma</h4>
                        
                        <div class="space-y-3">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Fecha y hora</p>
                                    <p class="font-medium text-gray-900">{{ $consentForm->signed_at->format('d/m/Y H:i:s') }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Dirección IP</p>
                                    <p class="font-mono text-sm text-gray-900">{{ $consentForm->patient_ip_address ?? 'No disponible' }}</p>
                                </div>
                            </div>

                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600">Dispositivo</p>
                                    <p class="text-xs text-gray-900 break-all">{{ Str::limit($consentForm->patient_device_info ?? 'No disponible', 80) }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Estado de Entrega --}}
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            @if($consentForm->isDelivered())
                            <div class="flex items-center gap-2 text-green-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm font-medium">Documento entregado</p>
                                    <p class="text-xs">{{ $consentForm->delivered_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            @else
                            <div class="flex items-center gap-2 text-amber-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-sm font-medium">Pendiente de entrega al paciente</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Firma del Tutor (si aplica) --}}
                @if($consentForm->isForMinor() && $consentForm->legal_guardian_name)
                <div class="mt-6 p-6 bg-amber-50 border border-amber-200 rounded-lg">
                    <h4 class="font-semibold text-gray-900 mb-3">Tutor Legal</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Nombre</p>
                            <p class="font-medium text-gray-900">{{ $consentForm->legal_guardian_name }}</p>
                            @if($consentForm->legal_guardian_relationship)
                            <p class="text-sm text-gray-600 mt-1">Relación: {{ $consentForm->legal_guardian_relationship }}</p>
                            @endif
                            @if($consentForm->legal_guardian_id_document)
                            <p class="text-sm text-gray-600">DNI: {{ $consentForm->legal_guardian_id_document }}</p>
                            @endif
                        </div>
                        @if($consentForm->guardian_signature_data)
                        <div>
                            <p class="text-sm text-gray-600 mb-2">Firma del tutor:</p>
                            <div class="border-2 border-gray-300 bg-white p-2 rounded inline-block">
                                <img src="{{ $consentForm->guardian_signature_data }}" 
                                     alt="Firma del tutor" 
                                     class="max-w-xs max-h-24 object-contain">
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- Signature Drawer (desde la derecha, similar al sidebar) --}}
    @if($showSignModal)
    <div class="fixed inset-0 z-50 overflow-hidden" 
         x-data="{ show: @entangle('showSignModal') }" 
         x-show="show" 
         x-cloak
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        {{-- Background overlay --}}
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="show = false"></div>
        
        {{-- Drawer Panel (desde la derecha) --}}
        <div class="fixed inset-y-0 right-0 flex max-w-full pl-10"
             x-transition:enter="transform transition ease-in-out duration-500"
             x-transition:enter-start="translate-x-full"
             x-transition:enter-end="translate-x-0"
             x-transition:leave="transform transition ease-in-out duration-500"
             x-transition:leave-start="translate-x-0"
             x-transition:leave-end="translate-x-full">
            <div class="w-screen max-w-md">
                <div class="h-full flex flex-col bg-white shadow-xl">
                    {{-- Header --}}
                    <div class="px-6 py-6 border-b border-gray-200 bg-primary-50">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Firmar Consentimiento</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $consentForm->consent_title ?? $consentForm->consent_type_label }}</p>
                            </div>
                            <button type="button" 
                                    @click="show = false"
                                    wire:click="closeSignModal"
                                    class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    {{-- Content --}}
                    <form wire:submit="sign" class="flex-1 overflow-y-auto">
                        <div class="px-6 py-6">
                            <p class="text-sm text-gray-600 mb-6">
                                Por favor, firme el consentimiento para confirmar que ha leído y acepta los términos.
                            </p>

                            {{-- Signature Canvas --}}
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-3">Firma</label>
                                <div class="border-2 border-gray-300 rounded-lg p-4 bg-white" 
                                     x-data="{ 
                                         canvas: null,
                                         ctx: null,
                                         drawing: false,
                                         hasDrawn: false,
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
                                             this.hasDrawn = true;
                                             const rect = this.canvas.getBoundingClientRect();
                                             this.ctx.lineTo(e.clientX - rect.left, e.clientY - rect.top);
                                             this.ctx.stroke();
                                         },
                                         stopDrawing() {
                                             this.drawing = false;
                                             
                                             // Validar que no esté vacía
                                             if (!this.hasDrawn) {
                                                 return;
                                             }
                                             
                                             const signatureData = this.canvas.toDataURL();
                                             
                                             // Crear un canvas en blanco para comparar
                                             const blankCanvas = document.createElement('canvas');
                                             blankCanvas.width = this.canvas.width;
                                             blankCanvas.height = this.canvas.height;
                                             const blankData = blankCanvas.toDataURL();
                                             
                                             // Si es igual al canvas en blanco, no guardar
                                             if (signatureData === blankData) {
                                                 alert('Por favor, firme en el recuadro antes de continuar');
                                                 return;
                                             }
                                             
                                             this.$wire.signatureData = signatureData;
                                         },
                                         clear() {
                                             this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                                             this.hasDrawn = false;
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
                                            width="400" 
                                            height="200" 
                                            class="w-full border border-gray-300 rounded cursor-crosshair bg-white"></canvas>
                                    <button type="button" 
                                            @click="clear()"
                                            class="mt-3 text-sm text-gray-600 hover:text-gray-800 font-medium">
                                        Limpiar firma
                                    </button>
                                </div>
                                @error('signatureData') 
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> 
                                @enderror
                            </div>
                        </div>
                        
                        {{-- Footer --}}
                        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex gap-3">
                            <button type="button" 
                                    wire:click="closeSignModal"
                                    class="flex-1 inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                Cancelar
                            </button>
                            <button type="submit" 
                                    wire:loading.attr="disabled"
                                    class="flex-1 inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-primary-600 text-sm font-medium text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50">
                                <svg wire:loading.remove wire:target="sign" class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg wire:loading wire:target="sign" class="animate-spin h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Confirmar Firma
                            </button>
                        </div>
                    </form>
                </div>
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
