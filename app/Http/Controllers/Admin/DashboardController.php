<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'hero_sections' => HeroSection::count(),
            'about_sections' => AboutSection::count(),
            'skills' => Skill::count(),
            'statistics' => Statistic::count(),
            'resume_sections' => ResumeSection::count(),
            'portfolio_items' => PortfolioItem::count(),
            'services' => Service::count(),
            'testimonials' => Testimonial::count(),
            'contact_info' => ContactInfo::count(),
        ];

        $recentUpdates = [
            'hero' => HeroSection::latest()->first(),
            'about' => AboutSection::latest()->first(),
            'portfolio' => PortfolioItem::latest()->take(3)->get(),
            'testimonials' => Testimonial::latest()->take(3)->get(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentUpdates' => $recentUpdates,
        ]);
    }
}