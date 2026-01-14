<?php

namespace Tests\Unit;

use App\Core\Contacts\Models\Contact;
use App\Core\Users\Models\Professional;
use App\Models\User;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNoteAccessLog;
use App\Modules\Psychology\ClinicalNotes\Services\ClinicalNoteAccessLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClinicalNoteAccessLogTest extends TestCase
{
    use RefreshDatabase;

    private ClinicalNoteAccessLogService $service;
    private Professional $professional;
    private Contact $contact;
    private ClinicalNote $clinicalNote;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ClinicalNoteAccessLogService::class);

        $this->user = User::factory()->create();
        $this->professional = Professional::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $this->contact = Contact::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        $this->clinicalNote = ClinicalNote::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);
    }

    /** @test */
    public function it_logs_clinical_note_access(): void
    {
        $ipAddress = '192.168.1.1';
        $userAgent = 'Mozilla/5.0 Test Browser';
        $action = 'view';

        $log = $this->service->logAccess(
            $this->clinicalNote,
            $this->user,
            $action,
            $ipAddress,
            $userAgent
        );

        $this->assertInstanceOf(ClinicalNoteAccessLog::class, $log);
        $this->assertEquals($this->clinicalNote->id, $log->clinical_note_id);
        $this->assertEquals($this->user->id, $log->user_id);
        $this->assertEquals($action, $log->action);
        $this->assertEquals($ipAddress, $log->ip_address);
        $this->assertEquals($userAgent, $log->user_agent);
        $this->assertNotNull($log->accessed_at);
    }

    /** @test */
    public function it_stores_access_metadata(): void
    {
        $metadata = [
            'page' => 'clinical-notes.show',
            'duration' => 30, // segundos
            'device' => 'desktop',
        ];

        $log = $this->service->logAccess(
            $this->clinicalNote,
            $this->user,
            'view',
            '192.168.1.1',
            'Test Browser',
            $metadata
        );

        $this->assertNotNull($log->metadata);
        $this->assertEquals('clinical-notes.show', $log->metadata['page']);
        $this->assertEquals(30, $log->metadata['duration']);
        $this->assertEquals('desktop', $log->metadata['device']);
    }

    /** @test */
    public function it_can_list_accesses_by_clinical_note(): void
    {
        // Crear múltiples accesos
        for ($i = 0; $i < 5; $i++) {
            $this->service->logAccess(
                $this->clinicalNote,
                $this->user,
                'view',
                '192.168.1.' . $i,
                'Browser ' . $i
            );
        }

        // Crear accesos a otra nota clínica
        $otherNote = ClinicalNote::factory()->create([
            'professional_id' => $this->professional->id,
            'contact_id' => $this->contact->id,
        ]);

        $this->service->logAccess(
            $otherNote,
            $this->user,
            'view',
            '10.0.0.1',
            'Other Browser'
        );

        $logs = $this->service->getAccessesByNote($this->clinicalNote->id);

        $this->assertCount(5, $logs);
        $this->assertTrue($logs->every(fn($log) => $log->clinical_note_id === $this->clinicalNote->id));
    }

    /** @test */
    public function it_can_list_accesses_by_user(): void
    {
        $otherUser = User::factory()->create();

        // Accesos del usuario principal
        for ($i = 0; $i < 3; $i++) {
            $this->service->logAccess(
                $this->clinicalNote,
                $this->user,
                'view',
                '192.168.1.' . $i,
                'Browser'
            );
        }

        // Accesos de otro usuario
        $this->service->logAccess(
            $this->clinicalNote,
            $otherUser,
            'view',
            '10.0.0.1',
            'Browser'
        );

        $logs = $this->service->getAccessesByUser($this->user->id);

        $this->assertCount(3, $logs);
        $this->assertTrue($logs->every(fn($log) => $log->user_id === $this->user->id));
    }

    /** @test */
    public function it_tracks_different_action_types(): void
    {
        $actions = ['view', 'edit', 'delete', 'export'];

        foreach ($actions as $action) {
            $this->service->logAccess(
                $this->clinicalNote,
                $this->user,
                $action,
                '192.168.1.1',
                'Browser'
            );
        }

        $logs = $this->service->getAccessesByNote($this->clinicalNote->id);

        $this->assertCount(4, $logs);
        $this->assertEquals($actions, $logs->pluck('action')->toArray());
    }

    /** @test */
    public function it_can_get_access_statistics(): void
    {
        // Crear 10 accesos view, 5 edit, 2 delete
        for ($i = 0; $i < 10; $i++) {
            $this->service->logAccess($this->clinicalNote, $this->user, 'view', '192.168.1.1', 'Browser');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->service->logAccess($this->clinicalNote, $this->user, 'edit', '192.168.1.1', 'Browser');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->service->logAccess($this->clinicalNote, $this->user, 'delete', '192.168.1.1', 'Browser');
        }

        $stats = $this->service->getAccessStatistics($this->clinicalNote->id);

        $this->assertEquals(17, $stats['total_accesses']);
        $this->assertEquals(10, $stats['by_action']['view']);
        $this->assertEquals(5, $stats['by_action']['edit']);
        $this->assertEquals(2, $stats['by_action']['delete']);
    }

    /** @test */
    public function it_can_filter_accesses_by_date_range(): void
    {
        // Accesos de hoy
        $this->service->logAccess($this->clinicalNote, $this->user, 'view', '192.168.1.1', 'Browser');

        // Simular accesos de hace 7 días
        $oldLog = $this->service->logAccess($this->clinicalNote, $this->user, 'view', '192.168.1.2', 'Browser');
        $oldLog->update(['accessed_at' => now()->subDays(7)]);

        // Simular accesos de hace 30 días
        $veryOldLog = $this->service->logAccess($this->clinicalNote, $this->user, 'view', '192.168.1.3', 'Browser');
        $veryOldLog->update(['accessed_at' => now()->subDays(30)]);

        $logsLastWeek = $this->service->getAccessesByDateRange(
            $this->clinicalNote->id,
            now()->subDays(7),
            now()
        );

        $this->assertCount(2, $logsLastWeek); // Hoy + hace 7 días
    }

    /** @test */
    public function it_can_get_most_accessed_notes_for_professional(): void
    {
        $note1 = $this->clinicalNote;
        $note2 = ClinicalNote::factory()->create([
            'professional_id' => $this->professional->id,
        ]);
        $note3 = ClinicalNote::factory()->create([
            'professional_id' => $this->professional->id,
        ]);

        // note1: 10 accesos
        for ($i = 0; $i < 10; $i++) {
            $this->service->logAccess($note1, $this->user, 'view', '192.168.1.1', 'Browser');
        }

        // note2: 5 accesos
        for ($i = 0; $i < 5; $i++) {
            $this->service->logAccess($note2, $this->user, 'view', '192.168.1.1', 'Browser');
        }

        // note3: 2 accesos
        for ($i = 0; $i < 2; $i++) {
            $this->service->logAccess($note3, $this->user, 'view', '192.168.1.1', 'Browser');
        }

        $mostAccessed = $this->service->getMostAccessedNotes($this->professional->id, 3);

        $this->assertCount(3, $mostAccessed);
        $this->assertEquals($note1->id, $mostAccessed[0]->id);
        $this->assertEquals(10, $mostAccessed[0]->access_count);
        $this->assertEquals($note2->id, $mostAccessed[1]->id);
        $this->assertEquals(5, $mostAccessed[1]->access_count);
    }

    /** @test */
    public function it_automatically_logs_access_via_model_observer(): void
    {
        $this->actingAs($this->user);

        // Al acceder a la nota (retrieve event), debe loguear automáticamente
        $note = ClinicalNote::find($this->clinicalNote->id);

        $logs = ClinicalNoteAccessLog::where('clinical_note_id', $note->id)->get();

        $this->assertGreaterThan(0, $logs->count());
    }

    /** @test */
    public function it_can_export_access_logs_for_gdpr_compliance(): void
    {
        for ($i = 0; $i < 5; $i++) {
            $this->service->logAccess(
                $this->clinicalNote,
                $this->user,
                'view',
                '192.168.1.' . $i,
                'Browser ' . $i
            );
        }

        $exportData = $this->service->exportAccessLogs($this->clinicalNote->id);

        $this->assertIsArray($exportData);
        $this->assertCount(5, $exportData);
        $this->assertArrayHasKey('user', $exportData[0]);
        $this->assertArrayHasKey('action', $exportData[0]);
        $this->assertArrayHasKey('ip_address', $exportData[0]);
        $this->assertArrayHasKey('accessed_at', $exportData[0]);
    }
}
