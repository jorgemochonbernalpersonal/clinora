<?php

namespace App\Core\Appointments\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
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
            'title' => $this->title,
            'type' => [
                'value' => $this->type->value,
                'label' => $this->type->label(),
                'icon' => $this->type->icon(),
            ],
            'status' => [
                'value' => $this->status->value,
                'label' => $this->status->label(),
                'color' => $this->status->color(),
            ],
            'start_time' => $this->start_time->toIso8601String(),
            'end_time' => $this->end_time->toIso8601String(),
            'duration' => $this->duration,
            'notes' => $this->notes,
            'internal_notes' => $this->internal_notes,
            'price' => $this->price,
            'currency' => $this->currency ?? 'EUR',
            'is_paid' => $this->is_paid,
            'cancellation_reason' => $this->cancellation_reason,
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),

            // Computed properties
            'is_upcoming' => $this->isUpcoming(),
            'is_today' => $this->isToday(),
            'can_be_cancelled' => $this->canBeCancelled(),

            // Relationships (when loaded)
            'contact' => $this->whenLoaded('contact', function () {
                return [
                    'id' => $this->contact->id,
                    'full_name' => $this->contact->full_name,
                    'email' => $this->contact->email,
                    'phone' => $this->contact->phone,
                ];
            }),
            'professional' => $this->whenLoaded('professional', function () {
                return [
                    'id' => $this->professional->id,
                    'name' => $this->professional->name,
                ];
            }),
            'clinical_note' => $this->whenLoaded('clinicalNote', function () {
                return [
                    'id' => $this->clinicalNote->id,
                    'session_number' => $this->clinicalNote->session_number,
                    'is_signed' => $this->clinicalNote->is_signed,
                ];
            }),
        ];
    }
}

