<?php

namespace App\Core\Appointments\Requests;

use App\Shared\Enums\AppointmentStatus;
use App\Shared\Enums\AppointmentType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAppointmentRequest extends FormRequest
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
            'start_time' => ['sometimes', 'required', 'date'],
            'end_time' => ['sometimes', 'required', 'date', 'after:start_time'],
            'type' => ['sometimes', 'required', Rule::enum(AppointmentType::class)],
            'status' => ['sometimes', 'required', Rule::enum(AppointmentStatus::class)],
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
            'start_time.required' => 'La fecha y hora de inicio es obligatoria.',
            'end_time.required' => 'La fecha y hora de fin es obligatoria.',
            'end_time.after' => 'La fecha de fin debe ser posterior a la de inicio.',
            'type.required' => 'El tipo de cita es obligatorio.',
            'status.required' => 'El estado de la cita es obligatorio.',
        ];
    }
}

