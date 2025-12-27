<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'roles' => $this->roles ? json_decode($this->roles, true) : [],
            'background_image' => $this->background_image ? asset('storage/' . $this->background_image) : null,
            'social_links' => $this->social_links ? json_decode($this->social_links, true) : [],
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}