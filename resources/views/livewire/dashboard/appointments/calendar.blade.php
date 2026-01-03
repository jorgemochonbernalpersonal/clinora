<div class="h-full w-full">
    <div 
        x-data="calendarComponent(@js($events))"
        x-init="initCalendar()" 
        wire:ignore
        class="h-[calc(100vh-12rem)]"
    >
        <style>
            .fc-event {
                @apply shadow-sm;
            }
            .fc-toolbar-title {
                @apply text-xl font-bold text-gray-900 !important;
            }
            .fc-button-primary {
                @apply bg-white border-gray-300 text-gray-700 hover:bg-gray-50 focus:ring-2 focus:ring-primary-500 !important;
            }
            .fc-button-active {
                @apply bg-primary-50 text-primary-600 border-primary-200 !important;
            }
            .fc-col-header-cell-cushion {
                @apply text-gray-600 font-semibold no-underline py-3 !important;
            }
            .fc-timegrid-slot-label-cushion {
                @apply text-gray-500 text-sm font-medium !important;
            }
            /* Hiding licensing warning if any */
            .fc-license-message {
                display: none;
            }
        </style>
        
        <div x-ref="calendar" class="h-full bg-white rounded-xl shadow-sm border border-gray-200 p-4"></div>
    </div>
</div>

<script>
    function calendarComponent(events) {
        return {
            calendar: null,
            events: events || [],
            initCalendar() {
                // Ensure FullCalendar is loaded
                if (!window.Calendar) {
                    console.error('FullCalendar not loaded');
                    return;
                }

                var calendarEl = this.$refs.calendar;
                
                this.calendar = new window.Calendar(calendarEl, {
                    plugins: window.CalendarPlugins,
                    initialView: 'timeGridWeek', 
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    locale: 'es',
                    firstDay: 1, 
                    slotMinTime: '08:00:00',
                    slotMaxTime: '21:00:00',
                    allDaySlot: false,
                    editable: true,
                    selectable: true,
                    height: '100%',
                    events: this.events,
                    
                    // Styling
                    eventClassNames: function(arg) {
                        return [
                            'cursor-pointer',
                            'hover:opacity-90',
                            'transition-opacity',
                            'rounded-md',
                            'border-0',
                            'shadow-sm',
                            'text-sm',
                            'font-medium'
                        ];
                    },
                    
                    // Event Handlers
                    select: (info) => {
                        // Redirect to create page with query params
                        const url = new URL('{{ route('appointments.create') }}');
                        url.searchParams.set('start', info.startStr);
                        url.searchParams.set('end', info.endStr);
                        window.location.href = url.toString();
                    },

                    eventDrop: (info) => {
                        this.$wire.updateAppointmentOrder(
                            info.event.id, 
                            info.event.start.toISOString(), 
                            info.event.end ? info.event.end.toISOString() : info.event.start.toISOString()
                        );
                    },
                    
                    eventResize: (info) => {
                         this.$wire.updateAppointmentOrder(
                            info.event.id, 
                            info.event.start.toISOString(), 
                            info.event.end.toISOString()
                        );
                    },
                    
                    eventClick: (info) => {
                        // Redirect to edit page
                        const url = '{{ route('appointments.edit', ['id' => 'ID_PLACEHOLDER']) }}'.replace('ID_PLACEHOLDER', info.event.id);
                        window.location.href = url;
                    }
                });
                
                this.calendar.render();
            }
        }
    }
</script>

