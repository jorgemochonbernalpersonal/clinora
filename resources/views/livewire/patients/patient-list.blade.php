<div>
    {{-- Header --}}
    <div x-data class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-text-primary">Pacientes</h2>
            <p class="text-text-secondary">Gestiona tus pacientes</p>
        </div>
        <a href="{{ route('patients.create') }}" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
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

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-success-50 border border-success-200 text-success-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    {{-- Patient Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($patients as $patient)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 hover:shadow-md transition-shadow duration-200 overflow-hidden group flex flex-col">
                {{-- Header with Photo --}}
                <div class="p-6 flex flex-col items-center border-b border-gray-100 flex-1">
                    <div class="w-24 h-24 rounded-full mb-4 relative">
                        <img 
                            src="{{ $patient->profile_photo_url }}" 
                            alt="{{ $patient->full_name }}"
                            class="w-full h-full object-cover rounded-full border-4 border-gray-50 bg-gray-100"
                        >
                        {{-- Status Dot --}}
                        <div class="absolute bottom-1 right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
                    </div>
                    
                    <h3 class="text-lg font-bold text-gray-900 text-center mb-1">
                        {{ $patient->full_name }}
                    </h3>
                    <p class="text-sm text-gray-500 mb-3">
                        {{ $patient->age ? $patient->age . ' años' : 'Sin edad' }}
                    </p>

                    <div class="flex gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            Paciente
                        </span>
                    </div>
                </div>

                {{-- Contact Info (Compact) --}}
                <div class="px-6 py-4 bg-gray-50 space-y-2">
                    @if($patient->email)
                    <div class="flex items-center text-sm text-gray-600 truncate" title="{{ $patient->email }}">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        <span class="truncate">{{ $patient->email }}</span>
                    </div>
                    @endif
                    
                    @if($patient->phone)
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        {{ $patient->phone }}
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                <div class="border-t border-gray-200 flex ml-auto w-full">
                    <a href="{{ route('patients.edit', $patient) }}" class="flex-1 py-3 text-sm font-medium text-center text-primary-600 hover:bg-primary-50 transition-colors border-r border-gray-200">
                        Editar
                    </a>
                    <a href="{{ route('clinical-notes.index', ['patient' => $patient->id]) }}" class="flex-1 py-3 text-sm font-medium text-center text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-colors">
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
                <a href="{{ route('patients.create') }}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                    <svg class="-ml-1 mr-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Añadir Paciente
                </a>
            </div>
        @endforelse
    </div>
</div>
