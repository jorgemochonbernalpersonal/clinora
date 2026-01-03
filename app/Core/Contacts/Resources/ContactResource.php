<?php

namespace App\Core\Contacts\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'date_of_birth' => $this->date_of_birth?->format('Y-m-d'),
            'age' => $this->age,
            'gender' => $this->gender,
            'address' => [
                'street' => $this->address_street,
                'city' => $this->address_city,
                'postal_code' => $this->address_postal_code,
                'country' => $this->address_country,
                'full' => $this->full_address,
            ],
            'emergency_contact' => [
                'name' => $this->emergency_contact_name,
                'phone' => $this->emergency_contact_phone,
                'relationship' => $this->emergency_contact_relationship,
            ],
            'notes' => $this->notes,
            'tags' => $this->tags ?? [],
            'is_active' => $this->is_active,
            'archived_at' => $this->archived_at?->toIso8601String(),
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            // Relationships (when loaded)
            'appointments_count' => $this->whenLoaded('appointments', 
                fn() => $this->appointments->count()
            ),
            'clinical_notes_count' => $this->whenLoaded('clinicalNotes', 
                fn() => $this->clinicalNotes->count()
            ),
            'recent_appointments' => $this->whenLoaded('appointments', 
                fn() => $this->appointments->take(5)->map(fn($apt) => [
                    'id' => $apt->id,
                    'start_time' => $apt->start_time->toIso8601String(),
                    'status' => $apt->status->value,
                ])
            ),
        ];
    }
}

