<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\API\HeroController;
use App\Http\Controllers\API\TestimonialController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public portfolio route (Main website)
Route::get('/', [PortfolioController::class, 'index'])->name('home');

// Admin routes
Route::prefix('admin')->name('admin.')->group(function () {
    // Login routes - accessible without authentication
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    // Protected admin routes - require authentication
    Route::middleware(['auth'])->group(function () {
        Route::post('logout', [LogoutController::class, 'logout'])->name('logout');
        
        // Dashboard
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Hero Section Management
        Route::get('hero', function () {
            return inertia('Admin/HeroSection');
        })->name('hero');
        
        // About Section Management
        Route::get('about', function () {
            return inertia('Admin/AboutSection');
        })->name('about');
        
        // Skills Management
        Route::get('skills', function () {
            return inertia('Admin/SkillsManagement');
        })->name('skills');
        
        // Statistics Management
        Route::get('statistics', function () {
            return inertia('Admin/StatisticsManagement');
        })->name('statistics');
        
        // Resume Management
        Route::get('resume', function () {
            return inertia('Admin/ResumeManagement');
        })->name('resume');
        
        // Portfolio Management
        Route::get('portfolio', function () {
            return inertia('Admin/PortfolioManagement');
        })->name('portfolio');
        
        // Services Management
        Route::get('services', function () {
            return inertia('Admin/ServicesManagement');
        })->name('services');
        
        // Testimonials Management
        Route::get('testimonials', function () {
            return inertia('Admin/TestimonialsManagement');
        })->name('testimonials');
        
        // Contact Information Management
        Route::get('contact', function () {
            return inertia('Admin/ContactManagement');
        })->name('contact');
        
        // Site Settings
        Route::get('settings', function () {
            return inertia('Admin/SiteSettings');
        })->name('settings');
    });
});

// API Routes - temporarily remove auth middleware for testing
// Route::prefix('api')->middleware(['auth'])->group(function () {
Route::prefix('api')->group(function () {
    // Hero Section API
    Route::get('/hero', [HeroController::class, 'index']);
    Route::post('/hero', [HeroController::class, 'store']);
    Route::put('/hero/{id}', [HeroController::class, 'update']);
    Route::delete('/hero/{id}', [HeroController::class, 'destroy']);
    
    // Testimonials API
    Route::get('/testimonials', [TestimonialController::class, 'index']);
    Route::post('/testimonials', [TestimonialController::class, 'store']);
    Route::put('/testimonials/{id}', [TestimonialController::class, 'update']);
    Route::delete('/testimonials/{id}', [TestimonialController::class, 'destroy']);
    
    // Add other API routes here as needed...
});

// Handle all other routes (404)
Route::fallback(function () {
    return inertia('NotFound');
});