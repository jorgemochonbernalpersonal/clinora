<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
    {{-- Header --}}
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Consentimientos Informados</h1>
            <p class="text-sm text-gray-500 mt-1">Gestione los consentimientos de sus pacientes</p>
        </div>
        <a href="{{ profession_route('consent-forms.create', ['contactId' => $contactId]) }}" 
           class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Consentimiento
        </a>
    </div>

    {{-- Filters --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Search --}}
            <div>
                <input type="text" 
                       wire:model.live.debounce.300ms="search" 
                       placeholder="Buscar por paciente o título..."
                       class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
            </div>

            {{-- Contact Filter --}}
            <div>
                <select wire:model.live="contactId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos los pacientes</option>
                    @foreach(\App\Core\Contacts\Models\Contact::where('professional_id', auth()->user()->professional->id)->get() as $contact)
                        <option value="{{ $contact->id }}">{{ $contact->full_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Type Filter --}}
            <div>
                <select wire:model.live="consentType" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="">Todos los tipos</option>
                    @foreach($availableTypes as $type => $label)
                        <option value="{{ $type }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <select wire:model.live="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                    <option value="all">Todos los estados</option>
                    <option value="pending">Pendientes</option>
                    <option value="signed">Firmados</option>
                    <option value="revoked">Revocados</option>
                </select>
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

    {{-- Consent Forms List --}}
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        @if($consentForms->count() > 0)
            <div class="divide-y divide-gray-200">
                @foreach($consentForms as $consent)
                    <div class="p-6 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        {{ $consent->consent_title ?? $consent->consent_type_label }}
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($consent->isSigned()) bg-green-100 text-green-800
                                        @elseif($consent->isPending()) bg-yellow-100 text-yellow-800
                                        @elseif($consent->isRevoked()) bg-red-100 text-red-800
                                        @endif">
                                        @if($consent->isSigned()) Firmado
                                        @elseif($consent->isPending()) Pendiente
                                        @elseif($consent->isRevoked()) Revocado
                                        @endif
                                    </span>
                                </div>
                                
                                <div class="flex items-center gap-4 text-sm text-gray-600 mb-2">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $consent->contact->full_name }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        {{ $consent->consent_type_label }}
                                    </span>
                                    @if($consent->signed_at)
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Firmado: {{ $consent->signed_at->format('d/m/Y') }}
                                    </span>
                                    @endif
                                </div>

                                <p class="text-sm text-gray-500 line-clamp-2">
                                    {{ Str::limit(strip_tags($consent->consent_text), 150) }}
                                </p>
                            </div>

                            <div class="flex items-center gap-2 ml-4">
                                <a href="{{ profession_route('consent-forms.show', $consent->id) }}" 
                                   class="px-3 py-2 text-sm font-medium text-primary-600 hover:text-primary-700 hover:bg-primary-50 rounded-lg transition-colors">
                                    Ver
                                </a>
                                
                                @if($consent->isPending())
                                    <button wire:click="delete({{ $consent->id }})" 
                                            wire:confirm="¿Está seguro de eliminar este consentimiento?"
                                            class="px-3 py-2 text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg transition-colors">
                                        Eliminar
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $consentForms->links() }}
            </div>
        @else
            <div class="p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay consentimientos</h3>
                <p class="mt-1 text-sm text-gray-500">Comience creando un nuevo consentimiento informado.</p>
                <div class="mt-6">
                    <a href="{{ profession_route('consent-forms.create') }}" 
                       class="inline-flex items-center px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                        Nuevo Consentimiento
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
