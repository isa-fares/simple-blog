<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * تنسيق البيانات الراجعة من الـ API
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'full_name' => strtoupper($this->name),
            'created_at' => $this->created_at->format('Y-m-d'),
        ];
    }
}
