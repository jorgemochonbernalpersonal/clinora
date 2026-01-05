<?php

namespace App\Console\Commands;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Mail\WeeklySummaryMail;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendWeeklySummaryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly-summary {--test : Send only to test email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send weekly activity summary emails to all active professionals';

    /**
     * Weekly tips pool
     */
    private array $weeklyTips = [
        'Programa recordatorios automáticos para tus pacientes 24 horas antes de cada cita. Esto reduce significativamente las ausencias.',
        'Mantén tus notas clínicas actualizadas inmediatamente después de cada sesión. La información fresca es más precisa.',
        'Revisa regularmente el progreso de tus pacientes usando las evaluaciones integradas. Los datos objetivos complementan tu criterio clínico.',
        'Dedica 5 minutos al final de tu día para revisar las citas de mañana y preparar las sesiones.',
        'Utiliza tags en las fichas de pacientes para organizar por tipo de terapia, urgencia o cualquier criterio que te sea útil.',
        'La teleconsulta es ideal para seguimientos rápidos o pacientes con movilidad reducida. ¿Ya la has probado?',
        'Exporta tus datos regularmente como backup. Tu información es valiosa y debe estar protegida.',
        'Personaliza tus plantillas de notas SOAP para cada tipo de consulta. Ahorrarás tiempo sin perder calidad.',
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $previousWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $previousWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        
        $this->info("Sending weekly summaries for week: {$previousWeekStart->format('Y-m-d')} to {$previousWeekEnd->format('Y-m-d')}");
        
        // Get all active professionals
        $professionals = Professional::whereHas('user', function($q) {
            $q->where('is_active', true);
        })->get();
        
        $sent = 0;
        $skipped = 0;
        
        foreach ($professionals as $professional) {
            // Check if user has weekly summary emails enabled
            if (!($professional->user->email_weekly_summary ?? true)) {
                $this->line("  Skipped {$professional->user->email} - preference disabled");
                $skipped++;
                continue;
            }
            
            // Calculate stats for previous week
            $stats = $this->calculateWeeklyStats($professional, $previousWeekStart, $previousWeekEnd);
            
            // Skip if no activity
            if ($stats['appointments_completed'] === 0 && $stats['clinical_notes_created'] === 0) {
                $this->line("  Skipped {$professional->user->email} - no activity");
                $skipped++;
                continue;
            }
            
            // Get upcoming appointments for next week
            $upcomingAppointments = $this->getUpcomingAppointments($professional, $weekStart, $weekEnd);
            
            // Random weekly tip
            $weeklyTip = $this->weeklyTips[array_rand($this->weeklyTips)];
            
            // Send email
            if ($this->option('test')) {
                $this->line("  [TEST MODE] Would send to: {$professional->user->email}");
                $this->line("    Stats: {$stats['appointments_completed']} appointments, {$stats['clinical_notes_created']} notes");
            } else {
                Mail::to($professional->user->email)->send(
                    new WeeklySummaryMail(
                        $professional,
                        $stats,
                        $upcomingAppointments,
                        $previousWeekStart,
                        $previousWeekEnd,
                        $weeklyTip
                    )
                );
                
                $this->info("  ✓ Sent to {$professional->user->email}");
            }
            
            $sent++;
        }
        
        $this->newLine();
        $this->info("Summary:");
        $this->info("  Sent: {$sent}");
        $this->info("  Skipped: {$skipped}");
        $this->info("  Total: " . ($sent + $skipped));
        
        return Command::SUCCESS;
    }
    
    /**
     * Calculate weekly statistics for a professional
     */
    private function calculateWeeklyStats(Professional $professional, Carbon $start, Carbon $end): array
    {
        $appointmentsCompleted = \App\Core\Appointments\Models\Appointment::where('professional_id', $professional->id)
            ->whereBetween('start_time', [$start, $end])
            ->where('status', 'completed')
            ->count();
        
        $clinicalNotesCreated = \App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote::where('professional_id', $professional->id)
            ->whereBetween('created_at', [$start, $end])
            ->count();
        
        $totalPatients = Contact::where('professional_id', $professional->id)
            ->where('is_active', true)
            ->count();
        
        return [
            'appointments_completed' => $appointmentsCompleted,
            'clinical_notes_created' => $clinicalNotesCreated,
            'total_patients' => $totalPatients,
        ];
    }
    
    /**
     * Get upcoming appointments for next week
     */
    private function getUpcomingAppointments(Professional $professional, Carbon $start, Carbon $end): array
    {
        return \App\Core\Appointments\Models\Appointment::where('professional_id', $professional->id)
            ->whereBetween('start_time', [$start, $end])
            ->where('status', 'scheduled')
            ->with('contact')
            ->orderBy('start_time')
            ->limit(5)
            ->get();
    }
}
