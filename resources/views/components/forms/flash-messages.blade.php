@props([])

@if (session()->has('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
@endif

@if (session()->has('error'))
    <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
@endif

@if (session()->has('warning'))
    <div class="mb-4 bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-3 rounded-lg">
        {{ session('warning') }}
    </div>
@endif

@if (session()->has('info'))
    <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg">
        {{ session('info') }}
    </div>
@endif

