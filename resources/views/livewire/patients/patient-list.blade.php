<div>
    {{-- Header --}}
    <div x-data class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-text-primary">Pacientes</h2>
            <p class="text-text-secondary">Gestiona tus pacientes</p>
        </div>
        <a href="{{ profession_route('patients.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nuevo Paciente
        </a>
    </div>

    {{-- Search --}}
    <div class="mb-6">
        <input 
            type="text" 
            wire:model.live="search"
            placeholder="Buscar paciente por nombre..."
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
    </div>


    {{-- Patient Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($patients as $patient)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 overflow-hidden group flex flex-col">
                {{-- Header with Photo --}}
                <div class="p-6 flex flex-col items-center border-b border-gray-100 flex-1">
                    <div class="w-24 h-24 rounded-full mb-4 relative overflow-hidden">
                        @if($patient->profile_photo_path)
                            <img 
                                src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($patient->profile_photo_path) }}" 
                                alt="{{ $patient->full_name }}"
                                class="w-full h-full object-cover rounded-full border-4 border-gray-50 bg-gray-100"
                                onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($patient->full_name) }}&color=0EA5E9&background=E0F2FE'"
                            >
                        @else
                            <img 
                                src="https://ui-avatars.com/api/?name={{ urlencode($patient->full_name) }}&color=0EA5E9&background=E0F2FE&size=96" 
                                alt="{{ $patient->full_name }}"
                                class="w-full h-full object-cover rounded-full border-4 border-gray-50 bg-gray-100"
                            >
                        @endif
                        {{-- Status Dot --}}
                        <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 text-center mb-1">
                        {{ $patient->full_name ?: '-' }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-3">
                        {{ $patient->age ? $patient->age . ' años' : '-' }}
                    </p>

                    <div class="flex gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Paciente
                        </span>
                    </div>
                </div>

                {{-- Contact Info (Complete) --}}
                <div class="px-6 py-4 bg-gray-50 space-y-2 text-sm">
                    {{-- DNI --}}
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path></svg>
                        <span class="truncate">{{ $patient->dni ?: '-' }}</span>
                    </div>

                    {{-- Email --}}
                    <div class="flex items-center text-gray-600 truncate" title="{{ $patient->email ?: '-' }}">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="truncate">{{ $patient->email ?: '-' }}</span>
                    </div>
                    
                    {{-- Phone --}}
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        <span>{{ $patient->phone ?: '-' }}</span>
                    </div>

                    {{-- Gender --}}
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        <span>{{ $patient->gender ? ucfirst(str_replace('_', ' ', $patient->gender)) : '-' }}</span>
                    </div>

                    {{-- Marital Status --}}
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>{{ $patient->marital_status ? ucfirst(str_replace('_', ' ', $patient->marital_status)) : '-' }}</span>
                    </div>

                    {{-- Occupation --}}
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="truncate">{{ $patient->occupation ?: '-' }}</span>
                    </div>

                    {{-- Education Level --}}
                    <div class="flex items-center text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        <span class="truncate">{{ $patient->education_level ? ucfirst($patient->education_level) : '-' }}</span>
                    </div>

                    {{-- Address --}}
                    @if($patient->address_street || $patient->address_city)
                    <div class="flex items-start text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate text-xs">
                            {{ $patient->address_street ?: '' }}
                            @if($patient->address_city)
                                {{ ($patient->address_street ? ', ' : '') . $patient->address_city }}
                            @endif
                            @if($patient->address_postal_code)
                                {{ ' (' . $patient->address_postal_code . ')' }}
                            @endif
                        </span>
                    </div>
                    @endif

                    {{-- Emergency Contact --}}
                    @if($patient->emergency_contact_name || $patient->emergency_contact_phone)
                    <div class="flex items-center text-red-600 text-xs">
                        <svg class="w-4 h-4 mr-2 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        <span class="truncate">
                            {{ $patient->emergency_contact_name ?: '-' }}
                            @if($patient->emergency_contact_phone)
                                - {{ $patient->emergency_contact_phone }}
                            @endif
                        </span>
                    </div>
                    @endif

                    {{-- Referral Source --}}
                    @if($patient->referral_source)
                    <div class="flex items-center text-gray-600 text-xs">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="truncate">{{ $patient->referral_source }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-200 flex ml-auto w-full">
                    <a href="{{ profession_route('clinical-notes.create', ['contact_id' => $patient->id]) }}" class="flex-1 py-3 text-sm font-medium text-center text-primary-600 hover:bg-primary-50 transition-colors border-r border-gray-200" title="Nueva Nota">
                        Nota
                    </a>
                    <a href="{{ profession_route('patients.edit', $patient) }}" class="flex-1 py-3 text-sm font-medium text-center text-gray-600 hover:bg-gray-50 transition-colors border-r border-gray-200">
                        Editar
                    </a>
                    <a href="{{ profession_route('clinical-notes.index', ['patient' => $patient->id]) }}" class="flex-1 py-3 text-sm font-medium text-center text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
                        Historial
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center bg-white rounded-lg border-2 border-dashed border-gray-300">
                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                <p class="text-lg font-medium text-gray-900">No hay pacientes</p>
                <p class="text-sm text-gray-500 mt-1">Añade tu primer paciente para empezar</p>
                <a href="{{ profession_route('patients.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    <svg class="-ml-1 mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Añadir Paciente
                </a>
            </div>
        @endforelse
    </div>
</div>
