<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HeroResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Handle null resource
        if (!$this->resource) {
            return [];
        }

        // Handle background image URL
        $backgroundImage = null;
        if ($this->background_image) {
            // Check if it's already a full URL
            if (filter_var($this->background_image, FILTER_VALIDATE_URL)) {
                $backgroundImage = $this->background_image;
            } 
            // Check if it already has storage/ prefix
            else if (str_starts_with($this->background_image, 'storage/')) {
                // It already has storage/ prefix, use asset() to get full URL
                $backgroundImage = asset($this->background_image);
            }
            // It's just a relative path (e.g., 'hero/filename.jpg')
            else {
                // Add storage/ prefix to get the full URL
                $backgroundImage = asset('storage/' . $this->background_image);
            }
        }

        return [
            'id' => $this->id ?? null,
            'name' => $this->name ?? '',
            'roles' => $this->roles ? (json_decode($this->roles, true) ?: []) : [],
            'background_image' => $backgroundImage,
            'social_links' => $this->social_links ? (json_decode($this->social_links, true) ?: []) : [],
            'is_active' => $this->is_active ?? true,
            'created_at' => $this->created_at ?? null,
            'updated_at' => $this->updated_at ?? null,
        ];
    }
}