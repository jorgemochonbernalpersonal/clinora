@section('title', 'Dashboard')

<div class="space-y-8">
    {{-- Welcome Header --}}
    <div class="flex justify-between items-end">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                ¡Hola, {{ auth()->user()->first_name }}! 👋
            </h1>
            <p class="text-gray-500 mt-1">
                Aquí tienes el resumen de tu consulta para hoy, {{ now()->isoFormat('D [de] MMMM') }}.
            </p>
        </div>
        <div class="text-sm text-gray-400">
            Actualizado {{ now()->format('H:i') }}
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Citas Hoy</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['appointments_today'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Esta Semana</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['appointments_week'] }}</p>
            </div>
             <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center text-purple-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex items-center justify-between">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Pacientes Totales</p>
                <p class="text-3xl font-bold text-gray-900">{{ $stats['patients'] }}</p>
            </div>
             <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Agenda Today --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-bold text-gray-900">Agenda para Hoy</h2>
                    <a href="{{ route('appointments.index') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Ver Calendario &rarr;</a>
                </div>
                <div class="divide-y divide-gray-100">
                     @forelse($todaysAppointments as $appt)
                        <div class="p-4 flex items-center hover:bg-gray-50 transition-colors">
                            <div class="flex-shrink-0 w-16 text-center">
                                <p class="text-sm font-bold text-gray-900">{{ $appt->start_time->format('H:i') }}</p>
                                <p class="text-xs text-gray-500">{{ $appt->duration_minutes }} min</p>
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-sm font-medium text-gray-900">{{ $appt->contact->full_name }}</p>
                                <p class="text-xs text-gray-500">{{ $appt->type->label() }}</p>
                            </div>
                            <div>
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $appt->status->color() }}-100 text-{{ $appt->status->color() }}-800">
                                    {{ $appt->status->label() }}
                                </span>
                            </div>
                            <div class="ml-4">
                                <a href="{{ route('clinical-notes.create', ['contact_id' => $appt->contact_id]) }}" class="text-gray-400 hover:text-primary-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center text-gray-500">
                            <p>No hay más citas programadas para hoy.</p>
                            <a href="{{ route('appointments.create') }}" class="text-primary-600 font-medium text-sm mt-2 block">Agendar Cita</a>
                        </div>
                    @endforelse
                </div>
            </div>

             <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-bold text-gray-900">Pacientes Recientes</h2>
                </div>
                 <div class="divide-y divide-gray-100">
                    @foreach($recentPatients as $patient)
                         <a href="{{ route('patients.edit', $patient) }}" class="block p-4 hover:bg-gray-50 transition-colors flex items-center">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs ring-2 ring-white">
                                {{ $patient->initials }}
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-900">{{ $patient->full_name }}</p>
                                <p class="text-xs text-gray-500">Registrado {{ $patient->created_at->diffForHumans() }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
             </div>
        </div>

        {{-- Sidebar Widgets --}}
        <div class="space-y-6">
             {{-- Quick Actions --}}
            <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-xl shadow-sm border border-primary-800 p-6 text-white">
                <h2 class="text-lg font-bold mb-4">Acciones Rápidas</h2>
                <div class="space-y-3">
                    <a href="{{ route('patients.create') }}" class="block w-full text-center py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg backdrop-blur-sm transition-colors text-sm font-medium">
                        + Nuevo Paciente
                    </a>
                    <a href="{{ route('appointments.create') }}" class="block w-full text-center py-2 bg-white/10 hover:bg-white/20 border border-white/20 rounded-lg backdrop-blur-sm transition-colors text-sm font-medium">
                        + Agendar Cita
                    </a>
                    <a href="{{ route('clinical-notes.create') }}" class="block w-full text-center py-2 bg-white text-primary-700 hover:bg-gray-50 rounded-lg shadow-sm transition-colors text-sm font-bold">
                        ✍️ Nueva Nota
                    </a>
                </div>
            </div>

            {{-- Upcoming Preview --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest">Próximas Citas</h3>
                </div>
                 <div class="divide-y divide-gray-100">
                     @forelse($upcomingAppointments as $upAppt)
                        <div class="p-4">
                            <p class="text-xs font-bold text-primary-600 mb-1">
                                {{ $upAppt->start_time->isoFormat('ddd D, H:i') }}
                            </p>
                             <p class="text-sm font-medium text-gray-900">{{ $upAppt->contact->full_name }}</p>
                             <p class="text-xs text-gray-500">{{ $upAppt->type->label() }}</p>
                        </div>
                    @empty
                        <div class="p-4 text-center text-xs text-gray-500">
                            Agenda libre próximamente
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
