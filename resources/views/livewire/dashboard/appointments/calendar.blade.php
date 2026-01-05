<div class="space-y-6 pb-8 animate-in fade-in duration-500">

    {{-- Stats Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Hoy --}}
        <div class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-secondary-50 text-secondary-600 rounded-xl group-hover:bg-secondary-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary uppercase tracking-wider">Hoy</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-primary-500">{{ $stats['today'] }}</h3>
                    <span class="text-xs text-text-muted">citas</span>
                </div>
            </div>
        </div>

        {{-- Esta Semana --}}
        <div class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-info-50 text-info-600 rounded-xl group-hover:bg-info-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary uppercase tracking-wider">Esta Semana</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-primary-500">{{ $stats['thisWeek'] }}</h3>
                    <span class="text-xs text-text-muted">total</span>
                </div>
            </div>
        </div>

        {{-- Completadas --}}
        <div class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-success-50 text-success-600 rounded-xl group-hover:bg-success-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary uppercase tracking-wider">Completadas (mes)</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-2xl font-bold text-primary-500">{{ $stats['completed'] }}</h3>
                    <span class="text-xs text-text-muted">pacientes</span>
                </div>
            </div>
        </div>

        {{-- Rendimiento --}}
        <div class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md transition-all duration-300">
            <div class="flex items-center justify-between mb-4">
                <div class="p-2.5 bg-accent-50 text-accent-600 rounded-xl group-hover:bg-accent-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
                <div class="text-xs font-bold text-success-600 bg-success-50 px-2 py-0.5 rounded-full">
                    +{{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%
                </div>
            </div>
            <div>
                <p class="text-sm font-medium text-text-secondary uppercase tracking-wider">Ratio de Asistencia</p>
                <div class="w-full bg-gray-100 rounded-full h-1.5 mt-2">
                    <div class="bg-accent-500 h-1.5 rounded-full shadow-sm" style="width: {{ $stats['total'] > 0 ? round(($stats['completed'] / $stats['total']) * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters & Search --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-2">
        <div class="flex flex-col lg:flex-row items-center gap-2">
            <div class="relative flex-1 w-full">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-text-muted pointer-events-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="searchTerm"
                    placeholder="Buscar por nombre de paciente..."
                    class="w-full pl-12 pr-4 py-3 bg-gray-50/50 border-none rounded-xl focus:ring-2 focus:ring-secondary-400 focus:bg-white transition-all text-sm font-medium text-primary-500 placeholder:text-text-muted/60"
                >
            </div>
            
            <div class="flex items-center gap-2 w-full lg:w-auto p-1">
                <select 
                    wire:model.live="statusFilter"
                    class="flex-1 lg:flex-none px-4 py-2.5 bg-gray-50/50 border-none rounded-xl focus:ring-2 focus:ring-secondary-400 focus:bg-white text-sm font-medium text-text-primary transition-all appearance-none cursor-pointer"
                >
                    <option value="all">Filtro: Todos</option>
                    <option value="scheduled">Programadas</option>
                    <option value="confirmed">Confirmadas</option>
                    <option value="completed">Completadas</option>
                    <option value="cancelled">Canceladas</option>
                </select>

                <select 
                    wire:model.live="typeFilter"
                    class="flex-1 lg:flex-none px-4 py-2.5 bg-gray-50/50 border-none rounded-xl focus:ring-2 focus:ring-secondary-400 focus:bg-white text-sm font-medium text-text-primary transition-all appearance-none cursor-pointer"
                >
                    <option value="all">Tipo: Todos</option>
                    <option value="in_person">📍 Presencial</option>
                    <option value="online">💻 Online</option>
                </select>

                @if($searchTerm || $statusFilter !== 'all' || $typeFilter !== 'all')
                    <button 
                        wire:click="clearFilters"
                        class="px-4 py-2.5 bg-error-50 hover:bg-error-100 text-error-600 rounded-xl text-sm font-bold transition-all flex items-center gap-2"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                        Reset
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Main Calendar Container --}}
    <div 
        x-data="calendarComponent(@js($events))"
        x-init="initCalendar()" 
        wire:ignore
        class="bg-white rounded-3xl shadow-xl shadow-primary-500/5 border border-gray-100 p-6 relative"
        @refresh-calendar.window="refreshEvents($event.detail.events || @js($events))"
    >
        <style>
            :root {
                --fc-border-color: #F3F4F6;
                --fc-today-bg-color: #F8FAFC;
                --fc-event-resizer-thickness: 10px;
            }
            .fc {
                font-family: 'Instrument Sans', sans-serif !important;
            }
            .fc-header-toolbar {
                @apply mb-8 !important;
            }
            .fc-toolbar-title {
                @apply text-xl font-bold text-primary-500 !important;
            }
            .fc-button {
                @apply rounded-xl font-semibold text-sm transition-all !important;
            }
            .fc-button-primary {
                @apply bg-white border-gray-200 text-text-primary hover:bg-gray-50 hover:border-gray-300 focus:ring-0 active:bg-gray-100 !important;
            }
            .fc-button-active {
                @apply bg-primary-500 border-primary-500 text-white hover:bg-primary-600 !important;
            }
            .fc-col-header-cell-cushion {
                @apply text-text-secondary font-bold no-underline py-4 uppercase text-[10px] tracking-widest !important;
            }
            .fc-timegrid-slot-label-cushion {
                @apply text-text-muted text-[11px] font-bold uppercase !important;
            }
            .fc-timegrid-axis-frame {
                @apply bg-gray-50/50 !important;
            }
            .fc-scrollgrid {
                @apply rounded-2xl overflow-hidden border-none !important;
            }
            .fc-scrollgrid-section-header > td {
                @apply border-b border-gray-100 !important;
            }
            .fc-theme-standard td, .fc-theme-standard th {
                @apply border-gray-100/60 !important;
            }
            .fc-event {
                @apply rounded-xl border-none shadow-sm transition-transform active:scale-95 !important;
            }
            .fc-v-event {
                @apply p-1.5 !important;
            }
            .fc-event-main {
                @apply p-0.5 !important;
            }
            .fc-timegrid-now-indicator-line {
                @apply border-secondary-400 !important;
            }
            .fc-timegrid-now-indicator-arrow {
                @apply border-secondary-400 border-l-[6px] !important;
            }
            .fc-license-message {
                display: none !important;
            }
        </style>
        
        <div x-ref="calendar" class="h-[750px] min-h-[600px]"></div>

        {{-- Loading Overlay --}}
        <div wire:loading class="absolute inset-0 bg-white/60 backdrop-blur-[2px] z-10 flex items-center justify-center rounded-3xl animate-in fade-in duration-200">
            <div class="flex flex-col items-center gap-3">
                <div class="w-12 h-12 border-4 border-secondary-100 border-t-secondary-500 rounded-full animate-spin"></div>
                <span class="text-sm font-bold text-primary-500">Actualizando agenda...</span>
            </div>
        </div>
    </div>

    {{-- Appointment Detail Modal --}}
    <div 
        x-data="{ 
            open: @entangle('selectedAppointmentId'),
            appointment: null,
            getAppointment(id) {
                const events = @js($events);
                return events.find(e => e.id == id);
            }
        }"
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-primary-900/40 backdrop-blur-sm"
    >
        <div class="bg-white rounded-[2rem] shadow-2xl max-w-lg w-full overflow-hidden border border-white/20 select-none">
            <template x-if="open">
                <div x-init="appointment = getAppointment(open)">
                    {{-- Header con Gradient --}}
                    <div class="relative p-8 pb-32 bg-gradient-to-br from-primary-500 to-primary-600 text-white">
                        <button @click="open = null" class="absolute right-6 top-6 p-2 hover:bg-white/10 rounded-full transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                        <div class="flex items-center gap-3 mb-2">
                            <span 
                                class="px-3 py-1 bg-white/10 backdrop-blur-md border border-white/20 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                x-text="appointment?.extendedProps?.type"
                            ></span>
                            <span 
                                class="px-3 py-1 border border-white/20 rounded-full text-[10px] font-bold uppercase tracking-wider"
                                :style="`background-color: ${appointment?.backgroundColor}40`"
                                x-text="appointment?.extendedProps?.status"
                            ></span>
                        </div>
                        <h2 class="text-3xl font-bold leading-tight" x-text="appointment?.title"></h2>
                    </div>

                    {{-- Content --}}
                    <div class="px-8 -mt-24 relative pb-8">
                        <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100 space-y-6">
                            {{-- Info Row --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-50 rounded-2xl">
                                    <p class="text-[10px] font-bold text-text-muted uppercase tracking-wider mb-1">Fecha</p>
                                    <p class="text-sm font-bold text-primary-500" x-text="new Date(appointment?.start).toLocaleDateString('es-ES', {month: 'long', day: 'numeric', year: 'numeric'})"></p>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-2xl">
                                    <p class="text-[10px] font-bold text-text-muted uppercase tracking-wider mb-1">Horario</p>
                                    <p class="text-sm font-bold text-primary-500">
                                        <span x-text="new Date(appointment?.start).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})"></span>
                                        -
                                        <span x-text="new Date(appointment?.end).toLocaleTimeString('es-ES', {hour: '2-digit', minute: '2-digit'})"></span>
                                    </p>
                                </div>
                            </div>

                            {{-- Patient Info --}}
                            <div class="flex items-center justify-between p-4 border border-gray-100 rounded-2xl">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 bg-secondary-50 text-secondary-500 rounded-full flex items-center justify-center font-bold text-lg">
                                        <span x-text="appointment?.title?.charAt(0)"></span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-bold text-text-muted uppercase tracking-wider mb-0.5">Paciente</p>
                                        <p class="text-base font-bold text-primary-500" x-text="appointment?.extendedProps?.patient"></p>
                                    </div>
                                </div>
                                <a :href="`/dashboard/patients/${appointment?.extendedProps?.patient_id}/edit`" 
                                   class="p-2 text-secondary-500 hover:bg-secondary-50 rounded-xl transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                            </div>

                            {{-- Notes --}}
                            <div x-show="appointment?.extendedProps?.notes" class="p-4 bg-accent-50/30 rounded-2xl border border-accent-100/50">
                                <p class="text-[10px] font-bold text-accent-700 uppercase tracking-wider mb-1">Notas del Profesional</p>
                                <p class="text-sm text-accent-900/80 leading-relaxed" x-text="appointment?.extendedProps?.notes"></p>
                            </div>

                            {{-- Quick Actions --}}
                            <div class="space-y-3">
                                <p class="text-[10px] font-bold text-text-muted uppercase tracking-wider">Gestión de Estado</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <button 
                                        @click="$wire.updateStatus(open, 'confirmed'); open = null"
                                        class="px-4 py-2.5 bg-success-50 hover:bg-success-100 text-success-700 rounded-xl text-xs font-bold transition-all"
                                    >
                                        Marcar Confirmada
                                    </button>
                                    <button 
                                        @click="$wire.updateStatus(open, 'completed'); open = null"
                                        class="px-4 py-2.5 bg-primary-50 hover:bg-primary-100 text-primary-500 rounded-xl text-xs font-bold transition-all"
                                    >
                                        Marcar Completada
                                    </button>
                                </div>
                            </div>

                            {{-- Main Actions --}}
                            <div class="flex gap-2 pt-2">
                                <a :href="`/dashboard/appointments/${open}/edit`" 
                                   class="flex-1 bg-gray-900 hover:bg-black text-white px-6 py-3 rounded-xl font-bold text-center transition-all shadow-lg shadow-black/10">
                                    Editar Cita
                                </a>
                                <button 
                                    @click="if(confirm('¿Seguro que deseas eliminar esta cita?')) { $wire.deleteAppointment(open); open = null; }"
                                    class="w-14 h-12 flex items-center justify-center bg-error-50 hover:bg-error-100 text-error-600 rounded-xl transition-all"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</div>

<script>
    function calendarComponent(initialEvents) {
        return {
            calendar: null,
            events: initialEvents || [],
            initRetries: 0,
            maxRetries: 50,
            
            initCalendar() {
                if (typeof window.Calendar === 'undefined') {
                    this.initRetries++;
                    if (this.initRetries < this.maxRetries) {
                        setTimeout(() => this.initCalendar(), 100);
                    } else {
                        console.error('Calendar failing to load...');
                    }
                    return;
                }

                var calendarEl = this.$refs.calendar;
                if (!calendarEl) return;
                
                this.initRetries = 0;
                const self = this;
                
                this.calendar = new window.Calendar(calendarEl, {
                    plugins: window.CalendarPlugins || [],
                    locale: window.CalendarLocale || 'es',
                    initialView: 'timeGridWeek', 
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    navLinks: true,
                    editable: true,
                    selectable: true,
                    selectMirror: true,
                    dayMaxEvents: true,
                    firstDay: 1, 
                    slotMinTime: '07:30:00',
                    slotMaxTime: '21:30:00',
                    allDaySlot: false,
                    expandRows: true,
                    height: 'auto',
                    events: this.events,
                    nowIndicator: true,
                    handleWindowResize: true,
                    
                    slotLabelFormat: {
                        hour: 'numeric',
                        minute: '2-digit',
                        omitZeroMinute: false,
                        meridiem: 'short'
                    },

                    eventContent: function(arg) {
                        let innerHtml = `
                            <div class="flex flex-col h-full overflow-hidden">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-white/60"></div>
                                    <span class="text-[9px] font-bold text-white/80 uppercase truncate">${arg.event.extendedProps.type}</span>
                                </div>
                                <div class="text-[11px] font-bold text-white leading-tight truncate">${arg.event.title}</div>
                                <div class="mt-auto text-[9px] font-medium text-white/70">${arg.timeText}</div>
                            </div>
                        `;
                        return { html: innerHtml };
                    },
                    
                    select: (info) => {
                        const url = new URL('{{ profession_route('appointments.create') }}');
                        url.searchParams.set('start', info.startStr);
                        url.searchParams.set('end', info.endStr);
                        window.location.href = url.toString();
                    },

                    eventDrop: (info) => {
                        self.$wire.updateAppointmentOrder(
                            info.event.id, 
                            info.event.start.toISOString(), 
                            info.event.end ? info.event.end.toISOString() : info.event.start.toISOString()
                        );
                    },
                    
                    eventResize: (info) => {
                        self.$wire.updateAppointmentOrder(
                            info.event.id, 
                            info.event.start.toISOString(), 
                            info.event.end.toISOString()
                        );
                    },
                    
                    eventClick: (info) => {
                        self.$wire.set('selectedAppointmentId', info.event.id);
                    }
                });
                
                this.calendar.render();
                
                // Observer to re-render on resize if needed
                new ResizeObserver(() => {
                    if (this.calendar) this.calendar.updateSize();
                }).observe(calendarEl);
            },
            
            refreshEvents(newEvents) {
                if (this.calendar) {
                    const events = newEvents || this.events;
                    this.events = events;
                    this.calendar.removeAllEvents();
                    if (events && Array.isArray(events) && events.length > 0) {
                        this.calendar.addEventSource(events);
                    }
                }
            }
        }
    }
</script>
