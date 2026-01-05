<?php

namespace App\Livewire\ConsentForms;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Repositories\ConsentFormRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.dashboard')]
class ConsentFormShow extends Component
{
    public ConsentForm $consentForm;
    public bool $showSignModal = false;
    public ?string $signatureData = null;
    public bool $canSign = false;

    public function mount(int $id)
    {
        $professional = auth()->user()->professional;
        
        $this->consentForm = ConsentForm::where('professional_id', $professional->id)
            ->with(['contact', 'professional'])
            ->findOrFail($id);

        // Check if can sign (must be pending and belong to professional)
        $this->canSign = $this->consentForm->isPending() 
            && $this->consentForm->professional_id === $professional->id;
    }

    public function openSignModal()
    {
        if (!$this->canSign) {
            session()->flash('error', 'No se puede firmar este consentimiento');
            return;
        }

        $this->showSignModal = true;
    }

    public function closeSignModal()
    {
        $this->showSignModal = false;
        $this->signatureData = null;
    }

    public function sign()
    {
        if (!$this->canSign) {
            session()->flash('error', 'No se puede firmar este consentimiento');
            return;
        }

        $this->validate([
            'signatureData' => 'required|string|min:100',
        ], [
            'signatureData.required' => 'Debe proporcionar una firma',
            'signatureData.min' => 'La firma no es válida. Por favor, firme en el recuadro.',
        ]);

        // Verificar edad del paciente (menores de 16 años requieren tutor)
        $contact = $this->consentForm->contact;
        $age = $contact->birthdate ? Carbon::parse($contact->birthdate)->age : null;
        
        if ($age !== null && $age < 16) {
            // Requiere firma del tutor legal
            if (empty($this->consentForm->legal_guardian_name)) {
                session()->flash('error', 'Este paciente es menor de 16 años y requiere consentimiento del tutor legal. Por favor, complete los datos del tutor en el formulario de consentimiento.');
                return;
            }
        }

        try {
            // Use the model's sign method directly
            $this->consentForm->sign(
                $this->signatureData,
                request()->ip(),
                request()->userAgent()
            );
            
            // Activity log (TODO: Install spatie/laravel-activitylog for better audit trail)
            \Log::info('Consentimiento firmado digitalmente', [
                'consent_form_id' => $this->consentForm->id,
                'consent_type' => $this->consentForm->consent_type,
                'contact_id' => $this->consentForm->contact_id,
                'user_id' => auth()->id(),
                'ip' => request()->ip(),
            ]);

            session()->flash('success', 'Consentimiento firmado exitosamente');
            $this->closeSignModal();
            
            // Refresh the consent form
            $this->consentForm->refresh();
            $this->canSign = false;
            
            // Dispatch event to update UI
            $this->dispatch('consent-signed', consentFormId: $this->consentForm->id);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al firmar el consentimiento: ' . $e->getMessage());
            \Log::error('Error signing consent form', [
                'consent_form_id' => $this->consentForm->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public bool $showRevokeModal = false;
    public ?string $revocationReason = null;

    public function openRevokeModal()
    {
        if ($this->consentForm->isRevoked()) {
            session()->flash('error', 'Este consentimiento ya está revocado');
            return;
        }

        if (!$this->consentForm->isSigned()) {
            session()->flash('error', 'Solo se pueden revocar consentimientos firmados');
            return;
        }

        $this->showRevokeModal = true;
    }

    public function closeRevokeModal()
    {
        $this->showRevokeModal = false;
        $this->revocationReason = null;
    }

    public function revoke()
    {
        $this->validate([
            'revocationReason' => 'nullable|string|max:500',
        ]);

        try {
            $this->consentForm->revoke($this->revocationReason);
            
            // Activity log
            \Log::info('Consentimiento revocado', [
                'consent_form_id' => $this->consentForm->id,
                'reason' => $this->revocationReason,
                'consent_type' => $this->consentForm->consent_type,
                'user_id' => auth()->id(),
            ]);
            
            // Enviar email al paciente si tiene email
            if (!empty($this->consentForm->contact->email)) {
                try {
                    \Mail::to($this->consentForm->contact->email)
                        ->send(new \App\Mail\ConsentFormRevoked($this->consentForm, $this->revocationReason));
                    
                    \Log::info('Email de revocación enviado', [
                        'consent_form_id' => $this->consentForm->id,
                        'email_sent_to' => $this->consentForm->contact->email,
                    ]);
                    
                    session()->flash('success', 'Consentimiento revocado exitosamente. Se ha enviado un email de notificación al paciente.');
                } catch (\Exception $emailError) {
                    \Log::error('Error sending revocation email', [
                        'consent_form_id' => $this->consentForm->id,
                        'email' => $this->consentForm->contact->email,
                        'error' => $emailError->getMessage(),
                    ]);
                    session()->flash('success', 'Consentimiento revocado exitosamente (el email de notificación no pudo enviarse).');
                }
            } else {
                session()->flash('success', 'Consentimiento revocado exitosamente.');
            }
            
            $this->closeRevokeModal();
            
            // Refresh the consent form
            $this->consentForm->refresh();
        } catch (\Exception $e) {
            session()->flash('error', 'Error al revocar el consentimiento: ' . $e->getMessage());
            \Log::error('Error revoking consent form', [
                'consent_form_id' => $this->consentForm->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function downloadPdf()
    {
        try {
            $consentBodyContent = $this->getConsentBodyContent();
            
            $pdf = Pdf::loadView('modules.psychology.consent-forms.consent-form-pdf', [
                'consentForm' => $this->consentForm,
                'consentBodyContent' => $consentBodyContent,
            ])
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
            
            $filename = 'consentimiento_' . 
                        $this->consentForm->id . '_' . 
                        \Str::slug($this->consentForm->contact->full_name) . '_' .
                        now()->format('Y-m-d') . 
                        '.pdf';
            
            
            // Activity log
            \Log::info('PDF del consentimiento descargado', [
                'consent_form_id' => $this->consentForm->id,
                'user_id' => auth()->id(),
            ]);
            
            return response()->streamDownload(function() use ($pdf) {
                echo $pdf->output();
            }, $filename, [
                'Content-Type' => 'application/pdf',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el PDF: ' . $e->getMessage());
            \Log::error('Error generating consent PDF', [
                'consent_form_id' => $this->consentForm->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Generate PDF for direct printing (opens in new window)
     */
    public function printPdf()
    {
        try {
            $consentBodyContent = $this->getConsentBodyContent();
            
            $pdf = Pdf::loadView('modules.psychology.consent-forms.consent-form-pdf', [
                'consentForm' => $this->consentForm,
                'consentBodyContent' => $consentBodyContent,
            ])
            ->setPaper('a4')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);
            
            $filename = 'consentimiento_' . 
                        $this->consentForm->id . '_' . 
                        \Str::slug($this->consentForm->contact->full_name) . '_' .
                        now()->format('Y-m-d') . 
                        '.pdf';
            
            // Activity log
            \Log::info('PDF del consentimiento abierto para impresión', [
                'consent_form_id' => $this->consentForm->id,
                'user_id' => auth()->id(),
            ]);
            
            // Return PDF inline for printing
            return response()->stream(function() use ($pdf) {
                echo $pdf->output();
            }, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el PDF para imprimir: ' . $e->getMessage());
            \Log::error('Error generating consent PDF for print', [
                'consent_form_id' => $this->consentForm->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    
    /**
     * Send email with PDF to patient
     */
    public function sendEmail()
    {
        // Validar que el consentimiento esté firmado
        if (!$this->consentForm->isSigned()) {
            session()->flash('error', 'Solo se pueden enviar emails de consentimientos firmados');
            return;
        }

        // Validar que el paciente tenga email
        if (empty($this->consentForm->contact->email)) {
            session()->flash('error', 'El paciente no tiene email registrado. Por favor, añade un email en el perfil del paciente.');
            return;
        }

        try {
            // Enviar email con el PDF adjunto
            \Mail::to($this->consentForm->contact->email)
                ->send(new \App\Mail\ConsentFormDelivered($this->consentForm));
            
            // Marcar como entregado automáticamente después de enviar
            if (!$this->consentForm->isDelivered()) {
                $this->consentForm->markAsDelivered();
            }
            
            // Activity log
            \Log::info('Email de consentimiento enviado', [
                'consent_form_id' => $this->consentForm->id,
                'user_id' => auth()->id(),
                'email_sent_to' => $this->consentForm->contact->email,
                'action' => $this->consentForm->wasRecentlyCreated ? 'sent' : 'resent',
            ]);
            
            if ($this->consentForm->delivered_at && $this->consentForm->delivered_at < now()->subMinutes(5)) {
                session()->flash('success', '✅ Email reenviado exitosamente a ' . $this->consentForm->contact->email);
            } else {
                session()->flash('success', '✅ Email enviado exitosamente a ' . $this->consentForm->contact->email);
            }
            
            $this->consentForm->refresh();
        } catch (\Exception $e) {
            session()->flash('error', '❌ Error al enviar el email: ' . $e->getMessage());
            \Log::error('Error sending consent email', [
                'consent_form_id' => $this->consentForm->id,
                'email' => $this->consentForm->contact->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Extract body content from full HTML document
     * This is needed because some templates generate full HTML documents
     * but we only want to display the body content in the view
     */
    public function getConsentBodyContent(): string
    {
        $html = $this->consentForm->consent_text;
        
        if (empty($html)) {
            return '';
        }
        
        // If the HTML contains a full document structure, extract only the body content
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $html, $matches)) {
            $bodyContent = trim($matches[1]);
            // Remove any remaining DOCTYPE, html, head tags that might be nested
            $bodyContent = preg_replace('/<!DOCTYPE[^>]*>/i', '', $bodyContent);
            $bodyContent = preg_replace('/<html[^>]*>/i', '', $bodyContent);
            $bodyContent = preg_replace('/<\/html>/i', '', $bodyContent);
            $bodyContent = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $bodyContent);
            return $bodyContent;
        }
        
        // If it's already just body content, return as is (but clean up any stray tags)
        $html = preg_replace('/<!DOCTYPE[^>]*>/i', '', $html);
        $html = preg_replace('/<html[^>]*>/i', '', $html);
        $html = preg_replace('/<\/html>/i', '', $html);
        $html = preg_replace('/<head[^>]*>.*?<\/head>/is', '', $html);
        $html = preg_replace('/<body[^>]*>/i', '', $html);
        $html = preg_replace('/<\/body>/i', '', $html);
        
        return trim($html);
    }

    /**
     * Extract styles from HTML document head
     */
    public function getConsentStyles(): string
    {
        $html = $this->consentForm->consent_text;
        
        // Extract styles from <style> tags
        if (preg_match('/<style[^>]*>(.*?)<\/style>/is', $html, $matches)) {
            return trim($matches[1]);
        }
        
        return '';
    }

    public function render()
    {
        return view('livewire.consent-forms.consent-form-show');
    }

    public function getTemplateData(): array
    {
        $additionalData = $this->consentForm->additional_data ?? [];
        
        return array_merge([
            'document_version' => $this->consentForm->document_version ?? '1.0',
            'treatment_duration' => $additionalData['treatment_duration'] ?? null,
            'session_frequency' => $additionalData['session_frequency'] ?? null,
            'session_duration' => $additionalData['session_duration'] ?? null,
            'treatment_techniques' => $additionalData['treatment_techniques'] ?? [],
            'platform' => $additionalData['platform'] ?? null,
            'security_info' => $additionalData['security_info'] ?? null,
            'recording_consent' => $additionalData['recording_consent'] ?? false,
            'treatment_goals' => $additionalData['treatment_goals'] ?? null,
        ], $additionalData);
    }
}
