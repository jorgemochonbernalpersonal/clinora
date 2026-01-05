<?php

namespace App\Core\ConsentForms\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsentFormResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'professional_id' => $this->professional_id,
            'contact_id' => $this->contact_id,
            'consent_type' => $this->consent_type,
            'consent_type_label' => $this->consent_type_label,
            'consent_title' => $this->consent_title,
            'consent_text' => $this->consent_text,
            
            // Status
            'is_signed' => $this->isSigned(),
            'is_pending' => $this->isPending(),
            'is_revoked' => $this->isRevoked(),
            'is_valid' => $this->is_valid,
            'signed_at' => $this->signed_at?->toIso8601String(),
            'revoked_at' => $this->revoked_at?->toIso8601String(),
            'revocation_reason' => $this->revocation_reason,
            
            // For minors
            'is_for_minor' => $this->isForMinor(),
            'legal_guardian_name' => $this->legal_guardian_name,
            'legal_guardian_relationship' => $this->legal_guardian_relationship,
            'minor_assent' => $this->minor_assent,
            
            // Metadata
            'document_version' => $this->document_version,
            'patient_ip_address' => $this->patient_ip_address,
            
            // Relationships
            'professional' => $this->whenLoaded('professional', function () {
                return [
                    'id' => $this->professional->id,
                    'name' => $this->professional->user->name ?? null,
                    'license_number' => $this->professional->license_number,
                ];
            }),
            'contact' => $this->whenLoaded('contact', function () {
                return [
                    'id' => $this->contact->id,
                    'name' => $this->contact->full_name,
                    'email' => $this->contact->email,
                ];
            }),
            
            // Timestamps
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}

