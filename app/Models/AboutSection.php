<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'profile_image',
        'title',
        'description',
        'bio',
        'personal_details',
        'extended_bio',
        'is_active',
    ];

    protected $casts = [
        'personal_details' => 'array',
        'is_active' => 'boolean',
    ];
}