<?php

namespace App\Http\Controllers;

use App\Models\HeroSection;
use App\Models\AboutSection;
use App\Models\Skill;
use App\Models\Statistic;
use App\Models\ResumeSection;
use App\Models\PortfolioItem;
use App\Models\Service;
use App\Models\Testimonial;
use App\Models\ContactInfo;
use App\Models\SiteSetting;
use Inertia\Inertia;

class PortfolioController extends Controller
{
    public function index()
    {
        // Get all active data for the portfolio
        $hero = HeroSection::where('is_active', true)->first();
        $about = AboutSection::where('is_active', true)->first();
        $skills = Skill::where('is_active', true)->orderBy('order')->get();
        $statistics = Statistic::where('is_active', true)->orderBy('order')->get();
        $resumeSections = ResumeSection::where('is_active', true)->orderBy('type')->orderBy('order')->get();
        $portfolioItems = PortfolioItem::where('is_active', true)->orderBy('order')->get();
        $services = Service::where('is_active', true)->orderBy('order')->get();
        $testimonials = Testimonial::where('is_active', true)->orderBy('order')->get();
        $contactInfo = ContactInfo::where('is_active', true)->orderBy('order')->get();
        $siteSetting = SiteSetting::first();

        // Organize data for Inertia
        $data = [
            'hero' => $hero ? [
                'id' => $hero->id,
                'name' => $hero->name,
                'roles' => $hero->roles ? json_decode($hero->roles, true) : [],
                'background_image' => $hero->background_image ? asset('storage/' . $hero->background_image) : null,
                'social_links' => $hero->social_links ? json_decode($hero->social_links, true) : [],
                'is_active' => $hero->is_active,
            ] : null,

            'about' => $about ? [
                'id' => $about->id,
                'profile_image' => $about->profile_image ? asset('storage/' . $about->profile_image) : null,
                'title' => $about->title,
                'description' => $about->description,
                'bio' => $about->bio,
                'personal_details' => $about->personal_details ? json_decode($about->personal_details, true) : [],
                'extended_bio' => $about->extended_bio,
                'is_active' => $about->is_active,
            ] : null,

            'skills' => $skills->map(function ($skill) {
                return [
                    'id' => $skill->id,
                    'name' => $skill->name,
                    'percentage' => $skill->percentage,
                    'column' => $skill->column,
                    'order' => $skill->order,
                    'is_active' => $skill->is_active,
                ];
            }),

            'statistics' => $statistics->map(function ($stat) {
                return [
                    'id' => $stat->id,
                    'icon' => $stat->icon,
                    'label' => $stat->label,
                    'value' => $stat->value,
                    'color' => $stat->color,
                    'order' => $stat->order,
                    'is_active' => $stat->is_active,
                ];
            }),

            'resumeSections' => $resumeSections->map(function ($section) {
                return [
                    'id' => $section->id,
                    'type' => $section->type,
                    'title' => $section->title,
                    'subtitle' => $section->subtitle,
                    'period' => $section->period,
                    'institution' => $section->institution,
                    'description' => $section->description,
                    'details' => $section->details ? json_decode($section->details, true) : [],
                    'order' => $section->order,
                    'is_active' => $section->is_active,
                ];
            }),

            'portfolioItems' => $portfolioItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'category' => $item->category,
                    'image' => $item->image ? asset('storage/' . $item->image) : null,
                    'description' => $item->description,
                    'details_url' => $item->details_url,
                    'order' => $item->order,
                    'is_active' => $item->is_active,
                ];
            }),

            'services' => $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'title' => $service->title,
                    'icon' => $service->icon,
                    'color' => $service->color,
                    'description' => $service->description,
                    'svg_path' => $service->svg_path,
                    'order' => $service->order,
                    'is_active' => $service->is_active,
                ];
            }),

            'testimonials' => $testimonials->map(function ($testimonial) {
                return [
                    'id' => $testimonial->id,
                    'name' => $testimonial->name,
                    'position' => $testimonial->position,
                    'image' => $testimonial->image ? asset('storage/' . $testimonial->image) : null,
                    'content' => $testimonial->content,
                    'rating' => $testimonial->rating,
                    'order' => $testimonial->order,
                    'is_active' => $testimonial->is_active,
                ];
            }),

            'contactInfo' => $contactInfo->map(function ($info) {
                return [
                    'id' => $info->id,
                    'type' => $info->type,
                    'icon' => $info->icon,
                    'title' => $info->title,
                    'value' => $info->value,
                    'order' => $info->order,
                    'is_active' => $info->is_active,
                ];
            }),

            'siteSetting' => $siteSetting ? [
                'id' => $siteSetting->id,
                'site_name' => $siteSetting->site_name,
                'site_description' => $siteSetting->site_description,
                'footer_social_links' => $siteSetting->footer_social_links ? json_decode($siteSetting->footer_social_links, true) : [],
                'copyright_name' => $siteSetting->copyright_name,
                'copyright_text' => $siteSetting->copyright_text,
                'designer_name' => $siteSetting->designer_name,
                'designer_url' => $siteSetting->designer_url,
                'distributor_name' => $siteSetting->distributor_name,
                'distributor_url' => $siteSetting->distributor_url,
                'distributed_by_text' => $siteSetting->distributed_by_text,
            ] : null,
        ];

        return Inertia::render('Portfolio', $data);
    }
}