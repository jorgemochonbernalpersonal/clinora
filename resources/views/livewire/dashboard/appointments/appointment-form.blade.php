<x-form-card title="{{ $isEditing ? 'Editar Cita' : 'Nueva Cita' }}" submit="save" maxWidth="2xl">
    <div class="space-y-6">
        {{-- Professional (Hidden/Auto) --}}
        
        {{-- Contact Selector --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Paciente</label>
            <select 
                wire:model="contact_id"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                <option value="">Seleccionar Paciente...</option>
                @foreach($contacts as $contact)
                    <option value="{{ $contact->id }}">{{ $contact->full_name }}</option>
                @endforeach
            </select>
            @error('contact_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
        
        {{-- Date and Time --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Inicio</label>
                <input 
                    type="datetime-local" 
                    wire:model="start_time"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                @error('start_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fin</label>
                <input 
                    type="datetime-local" 
                    wire:model="end_time"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                 @error('end_time') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
        
        {{-- Type and Status --}}
        <div class="grid grid-cols-2 gap-4">
            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                 <select 
                    wire:model="type"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @foreach(\App\Shared\Enums\AppointmentType::cases() as $type)
                        <option value="{{ $type->value }}">{{ $type->label() }}</option>
                    @endforeach
                </select>
                @error('type') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                 <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                 <select 
                    wire:model="status"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm">
                    @foreach(\App\Shared\Enums\AppointmentStatus::cases() as $status)
                        <option value="{{ $status->value }}">{{ $status->label() }}</option>
                    @endforeach
                </select>
                @error('status') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>
        </div>
        
        {{-- Notes --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Notas (Opcional)</label>
            <textarea 
                wire:model="notes"
                rows="3"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm"></textarea>
            @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
        </div>
    </div>

    <x-slot name="actions">
        <a href="{{ route('appointments.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
            Cancelar
        </a>
        <button 
            type="submit"
            class="inline-flex items-center px-4 py-2 bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-500 active:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150">
            {{ $isEditing ? 'Guardar Cambios' : 'Agendar Cita' }}
        </button>
    </x-slot>
</x-form-card>
