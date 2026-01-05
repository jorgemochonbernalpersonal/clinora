<?php

namespace App\Core\ConsentForms\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsentFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization handled in controller
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'consent_title' => 'sometimes|string|max:255',
            'consent_text' => 'sometimes|string',
            'document_version' => 'sometimes|string|max:20',
        ];
    }
}

