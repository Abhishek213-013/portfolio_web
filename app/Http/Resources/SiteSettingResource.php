<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SiteSettingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'site_name' => $this->site_name,
            'site_description' => $this->site_description,
            'footer_social_links' => $this->footer_social_links ? json_decode($this->footer_social_links, true) : [],
            'copyright_name' => $this->copyright_name,
            'copyright_text' => $this->copyright_text,
            'designer_name' => $this->designer_name,
            'designer_url' => $this->designer_url,
            'distributor_name' => $this->distributor_name,
            'distributor_url' => $this->distributor_url,
            'distributed_by_text' => $this->distributed_by_text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}