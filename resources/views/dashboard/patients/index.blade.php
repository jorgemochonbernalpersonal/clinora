@extends('layouts.dashboard')

@section('title', 'Pacientes')

@section('content')
    @livewire('patients.patient-list')
@endsection
