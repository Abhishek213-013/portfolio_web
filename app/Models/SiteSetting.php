<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_name',
        'site_description',
        'footer_social_links',
        'copyright_name',
        'copyright_text',
        'designer_name',
        'designer_url',
        'distributor_name',
        'distributor_url',
        'distributed_by_text',
    ];

    protected $casts = [
        'footer_social_links' => 'array',
    ];
}