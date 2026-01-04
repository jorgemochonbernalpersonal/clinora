import './bootstrap';
import { Calendar } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import listPlugin from '@fullcalendar/list';
import interactionPlugin from '@fullcalendar/interaction';
import esLocale from '@fullcalendar/core/locales/es';

window.Calendar = Calendar;
window.CalendarPlugins = [ dayGridPlugin, timeGridPlugin, listPlugin, interactionPlugin ];
window.CalendarLocale = esLocale;
