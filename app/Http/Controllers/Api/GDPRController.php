<?php

namespace App\Http\Controllers\Api;

use App\Core\Appointments\Models\Appointment;
use App\Core\Contacts\Models\Contact;
use App\Core\ConsentForms\Models\ConsentForm;
use App\Http\Controllers\Controller;
use App\Modules\Psychology\ClinicalNotes\Models\ClinicalNote;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * GDPR Controller
 * 
 * Handles GDPR/ARCO rights for patients:
 * - Access (Art. 15 RGPD)
 * - Rectification (Art. 16 RGPD)
 * - Erasure/Right to be forgotten (Art. 17 RGPD)
 * - Data Portability (Art. 20 RGPD)
 * - Opposition (Art. 21 RGPD)
 */
class GDPRController extends Controller
{
    /**
     * Right of Access (Art. 15 RGPD)
     * 
     * Returns all personal data stored about the authenticated user
     */
    public function access(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Get patient contact if exists
        $contact = Contact::where('email', $user->email)->first();
        
        if (!$contact) {
            return response()->json([
                'message' => 'No patient data found for this user',
                'data' => [
                    'user' => $user->only(['name', 'email', 'created_at']),
                ]
            ]);
        }
        
        $data = [
            'personal_data' => $contact->only([
                'first_name',
                'last_name',
                'dni',
                'email',
                'phone',
                'date_of_birth',
                'gender',
                'marital_status',
                'occupation',
                'education_level',
                'address_street',
                'address_city',
                'address_postal_code',
                'address_country',
                'created_at',
                'updated_at',
            ]),
            'emergency_contact' => $contact->only([
                'emergency_contact_name',
                'emergency_contact_phone',
                'emergency_contact_relationship',
            ]),
            'medical_information' => $contact->only([
                'initial_consultation_reason',
                'medical_history',
                'psychiatric_history',
                'current_medication',
            ]),
            'consent_forms' => ConsentForm::where('contact_id', $contact->id)
                ->get()
                ->map(fn($consent) => [
                    'type' => $consent->consent_type,
                    'signed_at' => $consent->signed_at,
                    'is_valid' => $consent->is_valid,
                    'revoked_at' => $consent->revoked_at,
                ]),
            'appointments_count' => Appointment::where('contact_id', $contact->id)->count(),
            'clinical_notes_count' => ClinicalNote::where('contact_id', $contact->id)->count(),
        ];
        
        // Log access request
        Log::info('GDPR Access Request', [
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'ip' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'Personal data retrieved successfully',
            'data' => $data,
            'requested_at' => now()->toIso8601String(),
        ]);
    }
    
    /**
     * Right to Data Portability (Art. 20 RGPD)
     * 
     * Exports all personal data in a machine-readable format (JSON)
     */
    public function export(Request $request): JsonResponse
    {
        $user = $request->user();
        
        $contact = Contact::where('email', $user->email)->first();
        
        if (!$contact) {
            return response()->json([
                'message' => 'No patient data found for this user',
            ], 404);
        }
        
        // Gather all data
        $exportData = [
            'export_date' => now()->toIso8601String(),
            'personal_data' => $contact->toArray(),
            'consent_forms' => ConsentForm::where('contact_id', $contact->id)->get()->toArray(),
            'appointments' => Appointment::where('contact_id', $contact->id)
                ->with('professional:id,user_id')
                ->get()
                ->map(fn($apt) => [
                    'start_time' => $apt->start_time,
                    'end_time' => $apt->end_time,
                    'type' => $apt->type,
                    'status' => $apt->status,
                    'notes' => $apt->notes,
                ])
                ->toArray(),
            'clinical_notes' => ClinicalNote::where('contact_id', $contact->id)
                ->get()
                ->map(fn($note) => [
                    'session_date' => $note->session_date,
                    'session_number' => $note->session_number,
                    'subjective' => $note->subjective,
                    'objective' => $note->objective,
                    'assessment' => $note->assessment,
                    'plan' => $note->plan,
                    'created_at' => $note->created_at,
                ])
                ->toArray(),
        ];
        
        // Create filename
        $filename = 'gdpr_export_' . $contact->id . '_' . now()->format('Y-m-d_His') . '.json';
        
        // Store file temporarily
        Storage::disk('local')->put('gdpr_exports/' . $filename, json_encode($exportData, JSON_PRETTY_PRINT));
        
        // Log export request
        Log::info('GDPR Export Request', [
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'filename' => $filename,
            'ip' => $request->ip(),
        ]);
        
        return response()->json([
            'message' => 'Data export created successfully',
            'download_url' => route('gdpr.download', ['filename' => $filename]),
            'expires_at' => now()->addHours(24)->toIso8601String(),
        ]);
    }
    
    /**
     * Download exported data
     */
    public function download(Request $request, string $filename)
    {
        // Validate filename format
        if (!preg_match('/^gdpr_export_\d+_\d{4}-\d{2}-\d{2}_\d{6}\.json$/', $filename)) {
            abort(404);
        }
        
        $path = 'gdpr_exports/' . $filename;
        
        if (!Storage::disk('local')->exists($path)) {
            abort(404, 'Export file not found or expired');
        }
        
        return Storage::disk('local')->download($path, $filename);
    }
    
    /**
     * Right to Erasure / Right to be Forgotten (Art. 17 RGPD)
     * 
     * Anonymizes or deletes personal data
     */
    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'confirmation' => 'required|string|in:DELETE_MY_DATA',
            'reason' => 'nullable|string|max:500',
        ]);
        
        $user = $request->user();
        
        $contact = Contact::where('email', $user->email)->first();
        
        if (!$contact) {
            return response()->json([
                'message' => 'No patient data found for this user',
            ], 404);
        }
        
        DB::beginTransaction();
        
        try {
            // Log deletion request BEFORE deleting
            Log::warning('GDPR Deletion Request', [
                'user_id' => $user->id,
                'contact_id' => $contact->id,
                'reason' => $request->reason,
                'ip' => $request->ip(),
            ]);
            
            // Anonymize instead of hard delete (for legal retention)
            $contact->update([
                'first_name' => 'DELETED',
                'last_name' => 'USER',
                'dni' => null,
                'email' => 'deleted_' . $contact->id . '@anonymized.local',
                'phone' => null,
                'date_of_birth' => null,
                'address_street' => null,
                'address_city' => null,
                'address_postal_code' => null,
                'emergency_contact_name' => null,
                'emergency_contact_phone' => null,
                'medical_history' => 'ANONYMIZED',
                'psychiatric_history' => 'ANONYMIZED',
                'current_medication' => null,
                'notes' => 'Data deleted per GDPR request on ' . now()->toDateString(),
                'is_active' => false,
                'archived_at' => now(),
            ]);
            
            // Anonymize clinical notes (keep for legal retention but anonymize content)
            ClinicalNote::where('contact_id', $contact->id)->update([
                'subjective' => 'ANONYMIZED',
                'objective' => 'ANONYMIZED',
                'assessment' => 'ANONYMIZED',
                'plan' => 'ANONYMIZED',
                'notes' => 'ANONYMIZED',
            ]);
            
            // Soft delete appointments
            Appointment::where('contact_id', $contact->id)->delete();
            
            // Revoke all consent forms
            ConsentForm::where('contact_id', $contact->id)
                ->whereNull('revoked_at')
                ->update([
                    'revoked_at' => now(),
                    'revocation_reason' => 'GDPR deletion request',
                    'is_valid' => false,
                ]);
            
            DB::commit();
            
            return response()->json([
                'message' => 'Personal data has been anonymized successfully',
                'anonymized_at' => now()->toIso8601String(),
                'note' => 'Some data is retained for legal compliance (minimum 5 years) but has been anonymized.',
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('GDPR Deletion Failed', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            
            return response()->json([
                'message' => 'Failed to process deletion request',
                'error' => 'Please contact support',
            ], 500);
        }
    }
    
    /**
     * Right to Rectification (Art. 16 RGPD)
     * 
     * Allows users to request correction of their personal data
     */
    public function rectify(Request $request): JsonResponse
    {
        $request->validate([
            'field' => 'required|string|in:first_name,last_name,email,phone,address_street,address_city,address_postal_code',
            'current_value' => 'required|string',
            'new_value' => 'required|string',
            'reason' => 'nullable|string|max:500',
        ]);
        
        $user = $request->user();
        
        $contact = Contact::where('email', $user->email)->first();
        
        if (!$contact) {
            return response()->json([
                'message' => 'No patient data found for this user',
            ], 404);
        }
        
        // Log rectification request
        Log::info('GDPR Rectification Request', [
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'field' => $request->field,
            'current_value' => $request->current_value,
            'new_value' => $request->new_value,
            'reason' => $request->reason,
            'ip' => $request->ip(),
        ]);
        
        // Verify current value matches
        if ($contact->{$request->field} !== $request->current_value) {
            return response()->json([
                'message' => 'Current value does not match our records',
            ], 422);
        }
        
        // Update the field
        $contact->update([
            $request->field => $request->new_value,
        ]);
        
        return response()->json([
            'message' => 'Data rectified successfully',
            'field' => $request->field,
            'updated_at' => now()->toIso8601String(),
        ]);
    }
    
    /**
     * Right to Object (Art. 21 RGPD)
     * 
     * Allows users to object to certain data processing
     */
    public function oppose(Request $request): JsonResponse
    {
        $request->validate([
            'processing_type' => 'required|string|in:marketing,profiling,research',
            'reason' => 'nullable|string|max:500',
        ]);
        
        $user = $request->user();
        
        $contact = Contact::where('email', $user->email)->first();
        
        if (!$contact) {
            return response()->json([
                'message' => 'No patient data found for this user',
            ], 404);
        }
        
        // Log opposition
        Log::info('GDPR Opposition Request', [
            'user_id' => $user->id,
            'contact_id' => $contact->id,
            'processing_type' => $request->processing_type,
            'reason' => $request->reason,
            'ip' => $request->ip(),
        ]);
        
        // Update contact preferences
        $tags = $contact->tags ?? [];
        $tags[] = 'gdpr_opposition_' . $request->processing_type;
        
        $contact->update([
            'tags' => array_unique($tags),
            'notes' => ($contact->notes ?? '') . "\n\n[" . now()->toDateString() . "] GDPR Opposition: " . $request->processing_type . " - " . $request->reason,
        ]);
        
        return response()->json([
            'message' => 'Opposition registered successfully',
            'processing_type' => $request->processing_type,
            'registered_at' => now()->toIso8601String(),
        ]);
    }
}
