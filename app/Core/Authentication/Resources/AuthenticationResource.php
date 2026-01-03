<?php

namespace App\Core\Authentication\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthenticationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'access_token' => $this->resource['token'],
            'token_type' => 'Bearer',
            'expires_in' => config('sanctum.expiration', null),
            'user' => new UserResource($this->resource['user']),
        ];
    }
}
