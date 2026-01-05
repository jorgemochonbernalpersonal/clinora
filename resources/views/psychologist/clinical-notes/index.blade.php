@extends('layouts.dashboard')

@section('title', 'Notas Clínicas')

@section('content')
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Notas Clínicas</h1>
            <p class="text-sm text-gray-500">Historial evolutivo de pacientes</p>
        </div>
        <a href="{{ route('clinical-notes.create') }}" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Nueva Nota
        </a>
    </div>

    @livewire('dashboard.clinical-notes.timeline')
@endsection
