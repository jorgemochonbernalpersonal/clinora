<?php

namespace App\Core\Billing\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'contact_id' => 'required|exists:contacts,id',
            'appointment_id' => 'nullable|exists:appointments,id',
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string|max:1000',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:500',
            'items.*.notes' => 'nullable|string|max:500',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'contact_id.required' => 'Debes seleccionar un paciente',
            'contact_id.exists' => 'El paciente seleccionado no existe',
            'items.required' => 'Debes agregar al menos un item a la factura',
            'items.*.description.required' => 'Cada item debe tener una descripción',
            'items.*.quantity.required' => 'Cada item debe tener una cantidad',
            'items.*.unit_price.required' => 'Cada item debe tener un precio unitario',
        ];
    }
}
