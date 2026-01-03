<?php

namespace App\Core\Authentication\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => trim("{$this->first_name} {$this->last_name}"),
            'phone' => $this->phone,
            'user_type' => $this->user_type,
            'language' => $this->language,
            'timezone' => $this->timezone,
            'is_active' => $this->is_active,
            'email_verified_at' => $this->email_verified_at?->toIso8601String(),
            'two_factor_enabled' => $this->two_factor_enabled,
            'last_login_at' => $this->last_login_at?->toIso8601String(),
            
            // Include professional data if exists
            'professional' => $this->whenLoaded('professional', function () {
                return [
                    'id' => $this->professional->id,
                    'name' => $this->professional->name,
                    'license_number' => $this->professional->license_number,
                    'profession' => $this->professional->profession,
                    'specialties' => $this->professional->specialties,
                    'subscription_plan' => $this->professional->subscription_plan,
                    'subscription_status' => $this->professional->subscription_status,
                ];
            }),
            
            // Include roles if loaded
            'roles' => $this->whenLoaded('roles', function () {
                return $this->roles->pluck('name');
            }),
            
            // Include permissions if loaded
            'permissions' => $this->whenLoaded('permissions', function () {
                return $this->getAllPermissions()->pluck('name');
            }),
            
            'created_at' => $this->created_at->toIso8601String(),
            'updated_at' => $this->updated_at->toIso8601String(),
        ];
    }
}
