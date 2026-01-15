<?php

namespace App\Livewire\Psychologist;

use App\Core\Appointments\Models\Appointment;
use App\Core\Billing\Models\Invoice;
use App\Core\Contacts\Models\Contact;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class DashboardHome extends Component
{
    public function render()
    {
        $professionalId = auth()->user()->professional->id;

        // Cache stats for 5 minutes (they don't change frequently)
        $stats = Cache::remember(
            "dashboard.stats.{$professionalId}",
            now()->addMinutes(5),
            fn() => [
                'patients' => Contact::forProfessional($professionalId)->count(),
                'appointments_today' => Appointment::forProfessional($professionalId)
                    ->today()
                    ->count(),
                'appointments_week' => Appointment::forProfessional($professionalId)
                    ->thisWeek()
                    ->count(),
            ]
        );

        // Use scopes for cleaner queries
        $todaysAppointments = Appointment::forProfessional($professionalId)
            ->today()
            ->withContactBasic()
            ->get();

        $upcomingAppointments = Appointment::forProfessional($professionalId)
            ->upcoming()
            ->withContactBasic()
            ->limit(5)
            ->get();

        $recentPatients = Contact::forProfessional($professionalId)
            ->recent(5)
            ->withCount('appointments')
            ->get();

        // Get invoice statistics
        $invoiceStats = Cache::remember(
            "dashboard.invoice_stats.{$professionalId}",
            now()->addMinutes(5),
            fn() => [
                'pending' => Invoice::forProfessional($professionalId)
                    ->whereIn('status', ['draft', 'sent'])
                    ->count(),
                'overdue' => Invoice::forProfessional($professionalId)
                    ->overdue()
                    ->count(),
                'pending_amount' => Invoice::forProfessional($professionalId)
                    ->whereIn('status', ['draft', 'sent'])
                    ->sum('total'),
                'overdue_amount' => Invoice::forProfessional($professionalId)
                    ->overdue()
                    ->sum('total'),
            ]
        );

        $recentInvoices = Invoice::forProfessional($professionalId)
            ->with('contact')
            ->recent(5)
            ->get();

        return view('livewire.psychologist.dashboard-home', compact(
            'stats', 
            'todaysAppointments', 
            'upcomingAppointments', 
            'recentPatients',
            'invoiceStats',
            'recentInvoices'
        ));
    }
}
