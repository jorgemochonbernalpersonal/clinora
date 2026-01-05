<?php

namespace App\Livewire\ConsentForms;

use App\Core\ConsentForms\Models\ConsentForm;
use App\Core\ConsentForms\Repositories\ConsentFormRepository;
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
            'signatureData' => 'required|string',
        ], [
            'signatureData.required' => 'Debe proporcionar una firma',
        ]);

        try {
            // Use the model's sign method directly
            $this->consentForm->sign(
                $this->signatureData,
                request()->ip(),
                request()->userAgent()
            );

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
            session()->flash('success', 'Consentimiento revocado exitosamente');
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
        // TODO: Implement PDF generation
        session()->flash('info', 'La generación de PDF estará disponible próximamente');
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
