<?php

namespace App\Core\Billing\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'issue_date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'currency' => 'nullable|string|size:3',
            'notes' => 'nullable|string|max:1000',
            'internal_notes' => 'nullable|string|max:1000',
            'items' => 'nullable|array|min:1',
            'items.*.description' => 'required_with:items|string|max:500',
            'items.*.notes' => 'nullable|string|max:500',
            'items.*.quantity' => 'required_with:items|numeric|min:0.01',
            'items.*.unit_price' => 'required_with:items|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'items.*.description.required_with' => 'Cada item debe tener una descripción',
            'items.*.quantity.required_with' => 'Cada item debe tener una cantidad',
            'items.*.unit_price.required_with' => 'Cada item debe tener un precio unitario',
        ];
    }
}
