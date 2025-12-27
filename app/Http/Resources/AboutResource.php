<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AboutResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'profile_image' => $this->profile_image ? asset('storage/' . $this->profile_image) : null,
            'title' => $this->title,
            'description' => $this->description,
            'bio' => $this->bio,
            'personal_details' => $this->personal_details ? json_decode($this->personal_details, true) : [],
            'extended_bio' => $this->extended_bio,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}