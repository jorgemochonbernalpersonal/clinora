<div class="space-y-6">
    {{-- Header --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Historial Clínico</h2>
            <p class="text-sm text-gray-500">Cronología de sesiones y evolución</p>
        </div>
        <a href="{{ profession_route('clinical-notes.create', $selectedPatientId ? ['contact_id' => $selectedPatientId] : []) }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2 shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Nota
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8 h-[calc(100vh-250px)]">
    
    {{-- Sidebar: Patient List --}}
    <div class="lg:col-span-1 flex flex-col bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        
        {{-- Search Header --}}
        <div class="p-4 border-b border-gray-100 bg-gray-50">
            <label for="search" class="sr-only">Buscar Paciente</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    wire:model.live.debounce.300ms="search"
                    id="search" 
                    class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition duration-150 ease-in-out" 
                    placeholder="Buscar paciente..."
                    type="search"
                >
            </div>
        </div>

        {{-- Scrollable List --}}
        <div class="flex-1 overflow-y-auto p-2 space-y-1 custom-scrollbar">
            @forelse($this->patients as $patient)
                <button 
                    wire:click="selectPatient({{ $patient->id }})"
                    class="w-full text-left px-3 py-3 rounded-lg flex items-center space-x-3 transition-colors {{ $selectedPatientId === $patient->id ? 'bg-primary-50 border-primary-100 ring-1 ring-primary-200' : 'hover:bg-gray-50' }}"
                >
                    <div class="flex-shrink-0">
                        @if($patient->profile_photo_path)
                            <img class="h-8 w-8 rounded-full object-cover" src="{{ asset('storage/'.$patient->profile_photo_path) }}" alt="{{ $patient->initials }}">
                        @else
                            <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-medium text-gray-500">
                                {{ substr($patient->first_name, 0, 1) }}{{ substr($patient->last_name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            {{ $patient->full_name }}
                        </p>
                        <p class="text-xs text-gray-500 truncate">
                            {{ $patient->dni ?? 'Sin DNI' }}
                        </p>
                    </div>
                    @if($selectedPatientId === $patient->id)
                        <div class="flex-shrink-0 text-primary-600">
                             <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>
                        </div>
                    @endif
                </button>
            @empty
                <div class="text-center py-6">
                    <p class="text-sm text-gray-500">No se encontraron pacientes.</p>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Main: Timeline --}}
    <div class="lg:col-span-3 h-full overflow-y-auto pr-2 custom-scrollbar">
        
        @if(!$selectedPatientId)
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded-r-md">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Mostrando actividad reciente de <strong>todos los pacientes</strong>. Selecciona uno en la lista para filtrar su historial completo.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-300 before:to-transparent">
            @forelse($groupedNotes as $month => $notes)
                <div class="relative">
                    <div class="sticky top-0 z-10 mb-4 ml-12 md:ml-0 md:text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-200 text-gray-800 shadow-sm backdrop-blur-sm bg-opacity-90">
                            {{ ucfirst($month) }}
                        </span>
                    </div>

                    @foreach($notes as $note)
                        <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-8">
                            <!-- Icon/Dot -->
                            <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-primary-100 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 transform -translate-x-1/2 z-10">
                                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>

                            <!-- Card -->
                            <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-5 rounded-2xl shadow-sm border border-gray-200 ml-12 md:ml-0 hover:shadow-md transition-all duration-200 cursor-pointer relative" x-data="{ expanded: false }">
                                
                                {{-- Connector Line for Desktop aesthetic --}}
                                <div class="hidden md:block absolute top-5 w-5 h-px bg-gray-300 group-odd:right-[-20px] group-even:left-[-20px]"></div>

                                <div class="flex justify-between items-start mb-3" @click="expanded = !expanded">
                                    <div>
                                        <h4 class="font-bold text-gray-900 text-base">
                                            Sesión #{{ $note->session_number }}
                                            @if(!$selectedPatientId)
                                                <span class="text-gray-500 font-normal text-sm block sm:inline"> | {{ $note->contact->full_name }}</span>
                                            @endif
                                        </h4>
                                        <time class="text-xs font-medium text-primary-600">{{ $note->session_date->format('d/m/Y') }}</time>
                                        <span class="text-xs text-gray-400 mx-1">·</span>
                                        <span class="text-xs text-gray-500">{{ $note->duration_minutes }} min</span>
                                    </div>
                                    @if($note->risk_assessment && $note->risk_assessment !== 'none')
                                        <span class="shrink-0 px-2.5 py-0.5 text-xs font-bold rounded-full bg-red-100 text-red-700 border border-red-200">
                                            @if($note->risk_assessment == 'high' || $note->risk_assessment == 'imminent') ⚠️ @endif
                                            {{ ucfirst(str_replace('_', ' ', $note->risk_assessment)) }}
                                        </span>
                                    @endif
                                </div>
                                
                                <div @click="expanded = !expanded">
                                    <div class="text-sm text-gray-600 line-clamp-3 leading-relaxed mb-2">
                                        <span class="font-bold text-gray-800">S:</span> {{ $note->subjective }}
                                    </div>
                                    <button class="text-xs text-primary-600 font-medium hover:underline flex items-center">
                                        <span x-text="expanded ? 'Ver menos' : 'Leer nota completa'"></span>
                                    </button>
                                </div>
                                
                                <div x-show="expanded" class="mt-4 pt-4 border-t border-gray-100 space-y-4" x-collapse>
                                    @if($note->objective)
                                    <div class="pl-3 border-l-2 border-green-200">
                                        <span class="block text-xs font-bold text-green-700 uppercase mb-1">Objetivo</span>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $note->objective }}</p>
                                    </div>
                                    @endif
                                    @if($note->assessment)
                                    <div class="pl-3 border-l-2 border-yellow-200">
                                        <span class="block text-xs font-bold text-yellow-700 uppercase mb-1">Análisis</span>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $note->assessment }}</p>
                                    </div>
                                    @endif
                                    @if($note->plan)
                                    <div class="pl-3 border-l-2 border-purple-200">
                                        <span class="block text-xs font-bold text-purple-700 uppercase mb-1">Plan</span>
                                        <p class="text-sm text-gray-700 leading-relaxed">{{ $note->plan }}</p>
                                    </div>
                                    @endif

                                     <div class="flex justify-end mt-4 pt-3 border-t border-gray-50">
                                        <a 
                                            href="{{ profession_route('clinical-notes.edit', $note) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-gray-50 hover:bg-white border border-gray-200 rounded text-xs font-medium text-gray-700 shadow-sm transition-colors"
                                        >
                                            <svg class="w-3 h-3 mr-1.5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                            Editar Ficha
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @empty
                <div class="flex flex-col items-center justify-center h-64 text-center">
                    <div class="bg-gray-50 rounded-full p-4 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    @if($selectedPatientId)
                         <h3 class="text-lg font-medium text-gray-900">Sin historial</h3>
                         <p class="text-gray-500 mt-1 max-w-sm">Este paciente aún no tiene notas clínicas registradas.</p>
                         <a href="{{ profession_route('clinical-notes.create', ['patient' => $selectedPatientId]) }}" class="mt-4 text-primary-600 hover:text-primary-700 font-medium text-sm">Crear primera nota &rarr;</a>
                    @else
                        <h3 class="text-lg font-medium text-gray-900">No hay notas recientes</h3>
                        <p class="text-gray-500 mt-1">Comienza seleccionando un paciente o creando una nueva nota.</p>
                    @endif
                </div>
            @endforelse
        </div>
    </div>
</div>
</div>
