<?php

namespace App\Core\Appointments\Requests;

use App\Shared\Enums\AppointmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->isProfessional();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact_id' => ['required', 'integer', 'exists:contacts,id'],
            'start_time' => ['required', 'date', 'after:now'],
            'end_time' => ['required', 'date', 'after:start_time'],
            'type' => ['required', Rule::enum(AppointmentType::class)],
            'title' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'internal_notes' => ['nullable', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'contact_id.required' => 'El paciente es obligatorio.',
            'contact_id.exists' => 'El paciente seleccionado no existe.',
            'start_time.required' => 'La fecha y hora de inicio es obligatoria.',
            'start_time.after' => 'La fecha de inicio debe ser en el futuro.',
            'end_time.required' => 'La fecha y hora de fin es obligatoria.',
            'end_time.after' => 'La fecha de fin debe ser posterior a la de inicio.',
            'type.required' => 'El tipo de cita es obligatorio.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Verify contact belongs to professional
        if ($this->has('contact_id')) {
            $professional = $this->user()->professional;
            $contact = \App\Core\Contacts\Models\Contact::find($this->contact_id);
            
            if ($contact && $contact->professional_id !== $professional->id) {
                $this->merge(['contact_id' => null]);
            }
        }
    }
}

