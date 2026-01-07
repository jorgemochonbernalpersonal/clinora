<div>
    {{-- Header --}}
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-text-primary">Agenda</h2>
            <p class="text-text-secondary">Próximas citas programadas</p>
        </div>
        <button 
            wire:click="openCreateModal"
            class="bg-secondary-500 hover:bg-secondary-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Cita
        </button>
    </div>

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="bg-success-50 border border-success-200 text-success-700 px-4 py-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Appointments List --}}
    <div class="space-y-4">
        @forelse($appointments as $appointment)
            <div class="bg-surface rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-12 h-12 bg-secondary-100 rounded-full flex items-center justify-center">
                                <svg class="w-6 h-6 text-secondary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-text-primary">
                                    {{ $appointment['contact']['full_name'] ?? 'Paciente' }}
                                </h3>
                                <div class="flex items-center gap-4 text-sm text-text-secondary">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none"stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($appointment['appointment_datetime'])->format('d M Y') }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        {{ \Carbon\Carbon::parse($appointment['appointment_datetime'])->format('H:i') }}
                                        ({{ $appointment['duration_minutes'] }} min)
                                    </span>
                                </div>
                            </div>
                        </div>
                        @if($appointment['notes'])
                            <p class="text-sm text-text-secondary mt-2">{{ $appointment['notes'] }}</p>
                        @endif
                    </div>
                    <div>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $appointment['status'] === 'scheduled' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $appointment['status'] === 'completed' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $appointment['status'] === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                            {{ ucfirst($appointment['status']) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-surface rounded-lg p-12 border border-gray-200 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-lg font-medium text-text-primary">No hay citas programadas</p>
                <p class="text-sm text-text-secondary mt-1">Crea tu primera cita para empezar</p>
            </div>
        @endforelse
    </div>

    {{-- Create Modal --}}
    @if($showCreateModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-surface rounded-lg p-8 max-w-lg w-full mx-4 max-h-[90vh] overflow-y-auto">
                <h3 class="text-2xl font-bold text-text-primary mb-6">Nueva Cita</h3>
                
                <form wire:submit.prevent="createAppointment" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-2">Paciente *</label>
                        <select 
                            wire:model="contact_id"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500"
                            required>
                            <option value="">Selecciona un paciente</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient['id'] }}">
                                    {{ $patient['first_name'] }} {{ $patient['last_name'] }}
                                </option>
                            @endforeach
                        </select>
                        @error('contact_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">Fecha *</label>
                            <input 
                                type="date" 
                                wire:model="appointment_date"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500"
                                required>
                            @error('appointment_date') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">Hora *</label>
                            <input 
                                type="time" 
                                wire:model="appointment_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500"
                                required>
                            @error('appointment_time') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">Duración (min) *</label>
                            <select 
                                wire:model="duration"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500">
                                <option value="30">30 minutos</option>
                                <option value="45">45 minutos</option>
                                <option value="60">60 minutos</option>
                                <option value="90">90 minutos</option>
                            </select>
                            @error('duration') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-text-primary mb-2">Tipo *</label>
                            <select 
                                wire:model="type"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500">
                                <option value="in_person">Presencial</option>
                                <option value="online">Online</option>
                            </select>
                            @error('type') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-text-primary mb-2">Notas</label>
                        <textarea 
                            wire:model="notes"
                            rows="3"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-secondary-500"
                            placeholder="Notas adicionales sobre la cita..."></textarea>
                        @error('notes') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3 mt-6">
                        <button 
                            type="button"
                            wire:click="closeCreateModal"
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="flex-1 bg-secondary-500 hover:bg-secondary-600 text-white px-4 py-2 rounded-lg transition-colors">
                            Crear Cita
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
