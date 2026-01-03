<div class="space-y-6">
    {{-- Header with date label --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-text-primary">Logs del Sistema</h1>
            <p class="text-text-secondary mt-1">{{ $dateLabel }}</p>
        </div>
        @if($logExists)
            <button wire:click="downloadLog" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Descargar Log
            </button>
        @endif
    </div>

    {{-- Stats Cards --}}
    @if($logExists && $stats['total'] > 0)
        <div class="grid grid-cols-2 md:grid-cols-4 xl:grid-cols-5 gap-3">
            <div class="bg-surface border border-gray-200 rounded-lg p-4">
                <div class="text-2xl font-bold text-text-primary">{{ $stats['total'] }}</div>
                <div class="text-xs text-text-secondary font-medium mt-1">Total</div>
            </div>
            @if($stats['emergency'] > 0)
                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['emergency'] }}</div>
                    <div class="text-xs text-purple-700 font-medium mt-1">Emergency</div>
                </div>
            @endif
            @if($stats['alert'] > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['alert'] }}</div>
                    <div class="text-xs text-red-700 font-medium mt-1">Alert</div>
                </div>
            @endif
            @if($stats['critical'] > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['critical'] }}</div>
                    <div class="text-xs text-red-700 font-medium mt-1">Critical</div>
                </div>
            @endif
            @if($stats['error'] > 0)
                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['error'] }}</div>
                    <div class="text-xs text-red-700 font-medium mt-1">Error</div>
                </div>
            @endif
            @if($stats['warning'] > 0)
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-yellow-600">{{ $stats['warning'] }}</div>
                    <div class="text-xs text-yellow-700 font-medium mt-1">Warning</div>
                </div>
            @endif
            @if($stats['notice'] > 0)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['notice'] }}</div>
                    <div class="text-xs text-blue-700 font-medium mt-1">Notice</div>
                </div>
            @endif
            @if($stats['info'] > 0)
                <div class="bg-cyan-50 border border-cyan-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-cyan-600">{{ $stats['info'] }}</div>
                    <div class="text-xs text-cyan-700 font-medium mt-1">Info</div>
                </div>
            @endif
            @if($stats['debug'] > 0)
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <div class="text-2xl font-bold text-gray-600">{{ $stats['debug'] }}</div>
                    <div class="text-xs text-gray-700 font-medium mt-1">Debug</div>
                </div>
            @endif
        </div>
    @endif

    {{-- Filters --}}
    <div class="bg-surface border border-gray-200 rounded-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Date Selector --}}
            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">Fecha</label>
                <input 
                    type="date" 
                    wire:model.live="selectedDate"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>

            {{-- Level Filter --}}
            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">Nivel</label>
                <select 
                    wire:model.live="logLevel"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
                    <option value="all">Todos los niveles</option>
                    <option value="emergency">Emergency</option>
                    <option value="alert">Alert</option>
                    <option value="critical">Critical</option>
                    <option value="error">Error</option>
                    <option value="warning">Warning</option>
                    <option value="notice">Notice</option>
                    <option value="info">Info</option>
                    <option value="debug">Debug</option>
                </select>
            </div>

            {{-- Search --}}
            <div>
                <label class="block text-sm font-medium text-text-primary mb-2">Buscar</label>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar en mensajes..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                >
            </div>
        </div>

        @if($logLevel !== 'all' || $search !== '')
            <div class="mt-4 flex items-center justify-between">
                <span class="text-sm text-text-secondary">
                    Mostrando {{ $logs->count() }} de {{ $stats['total'] }} entradas
                </span>
                <button 
                    wire:click="resetFilters"
                    class="text-sm text-primary-600 hover:text-primary-700 font-medium"
                >
                    Limpiar filtros
                </button>
            </div>
        @endif
    </div>

    {{-- Logs Table --}}
    @if(!$logExists)
        <div class="bg-surface border border-gray-200 rounded-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="text-lg font-semibold text-text-primary mb-2">No hay logs para esta fecha</h3>
            <p class="text-text-secondary">Selecciona otra fecha para ver los logs disponibles.</p>
        </div>
    @elseif($logs->isEmpty())
        <div class="bg-surface border border-gray-200 rounded-lg p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="text-lg font-semibold text-text-primary mb-2">No se encontraron resultados</h3>
            <p class="text-text-secondary">Intenta ajustar los filtros para ver más logs.</p>
        </div>
    @else
        <div class="bg-surface border border-gray-200 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider whitespace-nowrap">
                                Timestamp
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider whitespace-nowrap">
                                Nivel
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider">
                                Mensaje
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-text-secondary uppercase tracking-wider whitespace-nowrap">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($logs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-text-secondary font-mono">
                                    {{ \Carbon\Carbon::parse($log['timestamp'])->format('H:i:s') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $badgeColors = [
                                            'EMERGENCY' => 'bg-purple-100 text-purple-800 border-purple-200',
                                            'ALERT' => 'bg-red-100 text-red-800 border-red-200',
                                            'CRITICAL' => 'bg-red-100 text-red-800 border-red-200',
                                            'ERROR' => 'bg-red-100 text-red-800 border-red-200',
                                            'WARNING' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'NOTICE' => 'bg-blue-100 text-blue-800 border-blue-200',
                                            'INFO' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                            'DEBUG' => 'bg-gray-100 text-gray-800 border-gray-200',
                                        ];
                                        $colorClass = $badgeColors[$log['level']] ?? 'bg-gray-100 text-gray-800 border-gray-200';
                                    @endphp
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full border {{ $colorClass }}">
                                        {{ $log['level'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-text-primary">
                                    <div class="max-w-2xl">
                                        <p class="break-words">{{ Str::limit($log['message'], 200) }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($log['stack_trace'] || strlen($log['message']) > 200)
                                        <button 
                                            onclick="document.getElementById('log-{{ $log['id'] }}').classList.toggle('hidden')"
                                            class="text-primary-600 hover:text-primary-700 text-sm font-medium"
                                        >
                                            Ver detalles
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @if($log['stack_trace'] || strlen($log['message']) > 200)
                                <tr id="log-{{ $log['id'] }}" class="hidden bg-gray-50">
                                    <td colspan="4" class="px-6 py-4">
                                        <div class="space-y-3">
                                            <div>
                                                <h4 class="text-sm font-semibold text-text-primary mb-2">Mensaje completo:</h4>
                                                <pre class="text-xs text-text-secondary bg-white p-4 rounded border border-gray-200 overflow-x-auto">{{ $log['message'] }}</pre>
                                            </div>
                                            @if($log['stack_trace'])
                                                <div>
                                                    <h4 class="text-sm font-semibold text-text-primary mb-2">Stack trace:</h4>
                                                    <pre class="text-xs text-text-secondary bg-white p-4 rounded border border-gray-200 overflow-x-auto">{{ $log['stack_trace'] }}</pre>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $logs->links() }}
            </div>
        </div>
    @endif
</div>
