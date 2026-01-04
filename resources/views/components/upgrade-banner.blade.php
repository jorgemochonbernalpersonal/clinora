@props(['feature' => 'Función Premium', 'currentPlan' => 'gratis'])

<div class="bg-gradient-to-r from-primary-50 to-primary-100 border-l-4 border-primary-500 p-4 rounded-lg mb-6">
    <div class="flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <span class="text-2xl">⭐</span>
            <div>
                <p class="font-semibold text-text-primary">
                    {{ $feature }}
                </p>
                <p class="text-sm text-text-secondary">
                    Actualiza a <span class="font-medium">Pro</span> o <span class="font-medium">Equipo</span> para desbloquear esta funcionalidad
                </p>
            </div>
        </div>
        <a href="#pricing-section" 
           onclick="document.getElementById('upgrade-modal').showModal();"
           class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg font-medium transition-colors whitespace-nowrap">
            Ver Planes
        </a>
    </div>
</div>
