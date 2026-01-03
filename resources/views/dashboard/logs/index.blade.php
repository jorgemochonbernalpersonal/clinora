@extends('layouts.dashboard')

@section('title', 'Logs del Sistema')

@section('content')
    <x-toast />
    @livewire('log-viewer')
@endsection
