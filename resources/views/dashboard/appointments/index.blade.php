@extends('layouts.dashboard')

@section('title', 'Agenda')

@section('content')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Agenda</h2>
            <p class="text-gray-500">Gestiona tus citas y recordatorios</p>
        </div>
        
        <a href="{{ route('appointments.create') }}" class="inline-flex items-center justify-center px-4 py-2 bg-primary-600 border border-transparent rounded-lg font-semibold text-white hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Nueva Cita
        </a>
    </div>

    {{-- Calendar --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden h-[calc(100vh-12rem)]">
        @livewire('dashboard.appointments.calendar')
    </div>
@endsection
