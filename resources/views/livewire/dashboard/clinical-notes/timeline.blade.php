<div class="space-y-8 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-300 before:to-transparent">
    @forelse($groupedNotes as $month => $notes)
        <div class="relative">
            <div class="sticky top-0 z-10 mb-4 ml-12 md:ml-0 md:text-center">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-white border border-gray-200 text-gray-800 shadow-sm">
                    {{ ucfirst($month) }}
                </span>
            </div>

            @foreach($notes as $note)
                <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active mb-8">
                    <!-- Icon/Dot -->
                    <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-primary-100 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 transform -translate-x-1/2">
                        <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>

                    <!-- Card -->
                    <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] bg-white p-4 rounded-xl shadow-sm border border-gray-200 ml-12 md:ml-0 hover:shadow-md transition-shadow cursor-pointer" x-data="{ expanded: false }" @click="expanded = !expanded">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-bold text-gray-900">
                                    Sesión #{{ $note->session_number }}
                                    @if(!$contactId)
                                        <span class="text-gray-500 font-normal text-sm">- {{ $note->contact->full_name }}</span>
                                    @endif
                                </h4>
                                <time class="text-xs text-gray-500">{{ $note->session_date->format('d/m/Y') }} · {{ $note->duration_minutes }} min</time>
                            </div>
                            @if($note->risk_assessment && $note->risk_assessment !== 'sin_riesgo')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                    Riesgo {{ ucfirst(str_replace('_', ' ', $note->risk_assessment)) }}
                                </span>
                            @endif
                        </div>
                        
                        <p class="text-sm text-gray-600 line-clamp-2" :class="{ 'line-clamp-none': expanded }">
                            <span class="font-semibold text-primary-700">Subjetivo:</span> {{ $note->subjective }}
                        </p>
                        
                        <div x-show="expanded" class="mt-4 pt-4 border-t border-gray-100 space-y-3" x-collapse>
                            @if($note->objective)
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase">Objetivo</span>
                                <p class="text-sm text-gray-700">{{ $note->objective }}</p>
                            </div>
                            @endif
                            @if($note->assessment)
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase">Evaluación</span>
                                <p class="text-sm text-gray-700">{{ $note->assessment }}</p>
                            </div>
                            @endif
                            @if($note->plan)
                            <div>
                                <span class="block text-xs font-semibold text-gray-500 uppercase">Plan</span>
                                <p class="text-sm text-gray-700">{{ $note->plan }}</p>
                            </div>
                            @endif

                             <div class="flex justify-between items-center mt-4 pt-2 border-t border-gray-100">
                                <a 
                                    href="{{ route('clinical-notes.edit', $note) }}"
                                    class="text-gray-500 hover:text-primary-600 text-xs font-medium flex items-center gap-1"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Editar Nota
                                </a>
                                
                                <a href="#" class="text-primary-600 hover:text-primary-800 text-xs font-medium flex items-center">
                                    Ver Detalle Completo &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @empty
        <div class="text-center py-10">
            <div class="bg-gray-50 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900">Sin notas clínicas</h3>
            <p class="text-gray-500 mt-1">No hay historial clínico registrado aún.</p>
        </div>
    @endforelse
</div>
