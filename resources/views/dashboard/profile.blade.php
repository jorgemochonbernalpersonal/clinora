@extends('layouts.dashboard')

@section('title', 'Mi Perfil')

@section('content')
<div class="max-w-4xl mx-auto">
    <livewire:profile.profile-settings />
</div>
@endsection
