<?php

namespace App\Livewire\Psychologist;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class DashboardHome extends Component
{
    public function render()
    {
        $professionalId = auth()->user()->professional->id;

        // Stats
        $stats = [
            'patients' => Contact::where('professional_id', $professionalId)->count(),
            'appointments_today' => Appointment::where('professional_id', $professionalId)
                ->whereDate('start_time', now())
                ->count(),
            'appointments_week' => Appointment::where('professional_id', $professionalId)
                ->whereBetween('start_time', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
        ];

        // Widgets
        $todaysAppointments = Appointment::where('professional_id', $professionalId)
            ->whereDate('start_time', now())
            ->with('contact')
            ->orderBy('start_time')
            ->get();

        $upcomingAppointments = Appointment::where('professional_id', $professionalId)
            ->where('start_time', '>', now())
            ->with('contact')
            ->orderBy('start_time')
            ->limit(5)
            ->get();

        $recentPatients = Contact::where('professional_id', $professionalId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('livewire.psychologist.dashboard-home', compact(
            'stats', 
            'todaysAppointments', 
            'upcomingAppointments', 
            'recentPatients'
        ));
    }
}
