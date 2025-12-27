<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'roles',
        'background_image',
        'social_links',
        'is_active',
    ];

    protected $casts = [
        'roles' => 'array',
        'social_links' => 'array',
        'is_active' => 'boolean',
    ];
}