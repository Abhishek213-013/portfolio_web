<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\PortfolioController; // This controller needs to exist
use App\Http\Controllers\API\HeroController;
use App\Http\Controllers\API\TestimonialController;
use App\Http\Controllers\API\AboutController;
use App\Http\Controllers\API\SkillController;
use App\Http\Controllers\API\StatisticController;
use App\Http\Controllers\API\ResumeController;
use App\Http\Controllers\API\PortfolioController as ApiPortfolioController;
use App\Http\Controllers\API\ServiceController;
use App\Http\Controllers\API\ContactInfoController;
use App\Http\Controllers\API\SiteSettingController;
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

// Public portfolio route (Main website) - Using Inertia
Route::get('/', function () {
    return inertia('Portfolio'); // This points to resources/js/Pages/Portfolio.vue
})->name('home');

// OR if you have a PortfolioController with index method:
// Route::get('/', [PortfolioController::class, 'index'])->name('home');

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

// API Routes - For the portfolio frontend to fetch data
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
    
    // About Section API
    Route::get('/about', [AboutController::class, 'index']);
    Route::post('/about', [AboutController::class, 'store']);
    Route::put('/about/{id}', [AboutController::class, 'update']);
    Route::delete('/about/{id}', [AboutController::class, 'destroy']);
    
    // Skills API
    Route::get('/skills', [SkillController::class, 'index']);
    Route::post('/skills', [SkillController::class, 'store']);
    Route::get('/skills/{id}', [SkillController::class, 'show']);
    Route::put('/skills/{id}', [SkillController::class, 'update']);
    Route::delete('/skills/{id}', [SkillController::class, 'destroy']);
    Route::post('/skills/order', [SkillController::class, 'updateOrder']);

    // Statistics API
    Route::get('/statistics', [StatisticController::class, 'index']);
    Route::post('/statistics', [StatisticController::class, 'store']);
    Route::get('/statistics/{id}', [StatisticController::class, 'show']);
    Route::put('/statistics/{id}', [StatisticController::class, 'update']);
    Route::delete('/statistics/{id}', [StatisticController::class, 'destroy']);
    Route::post('/statistics/order', [StatisticController::class, 'updateOrder']);
    
    // Resume Sections API
    Route::get('/resume-sections', [ResumeController::class, 'index']);
    Route::post('/resume-sections', [ResumeController::class, 'store']);
    Route::get('/resume-sections/{id}', [ResumeController::class, 'show']);
    Route::put('/resume-sections/{id}', [ResumeController::class, 'update']);
    Route::delete('/resume-sections/{id}', [ResumeController::class, 'destroy']);
    Route::get('/resume-sections/type/{type}', [ResumeController::class, 'byType']);
    
    // Portfolio Items API
    Route::get('/portfolio-items', [ApiPortfolioController::class, 'index']);
    Route::post('/portfolio-items', [ApiPortfolioController::class, 'store']);
    Route::get('/portfolio-items/{id}', [ApiPortfolioController::class, 'show']);
    Route::put('/portfolio-items/{id}', [ApiPortfolioController::class, 'update']);
    Route::delete('/portfolio-items/{id}', [ApiPortfolioController::class, 'destroy']);
    Route::get('/portfolio-items/category/{category}', [ApiPortfolioController::class, 'byCategory']);
    
    // Services API
    Route::get('/services', [ServiceController::class, 'index']);
    Route::post('/services', [ServiceController::class, 'store']);
    Route::get('/services/{id}', [ServiceController::class, 'show']);
    Route::put('/services/{id}', [ServiceController::class, 'update']);
    Route::delete('/services/{id}', [ServiceController::class, 'destroy']);
    Route::post('/services/order', [ServiceController::class, 'updateOrder']);
    Route::post('/services/bulk-update', [ServiceController::class, 'bulkUpdate']);
    Route::get('/services/active', [ServiceController::class, 'active']);
    
    // Contact Info API
    Route::get('/contact-info', [ContactInfoController::class, 'index']);
    Route::post('/contact-info', [ContactInfoController::class, 'store']);
    Route::get('/contact-info/{id}', [ContactInfoController::class, 'show']);
    Route::put('/contact-info/{id}', [ContactInfoController::class, 'update']);
    Route::delete('/contact-info/{id}', [ContactInfoController::class, 'destroy']);
    
    // Site Settings API
    Route::get('/site-settings', [SiteSettingController::class, 'index']);
    Route::post('/site-settings', [SiteSettingController::class, 'store']);
    Route::put('/site-settings/{id}', [SiteSettingController::class, 'update']);
    Route::delete('/site-settings/{id}', [SiteSettingController::class, 'destroy']);
    Route::get('/site-settings/public', [SiteSettingController::class, 'publicSettings']);
    
    // Public APIs (no auth required for frontend)
    Route::prefix('public')->group(function () {
        Route::get('/hero', [HeroController::class, 'index']);
        Route::get('/about', [AboutController::class, 'index']);
        Route::get('/skills', [SkillController::class, 'index']);
        Route::get('/statistics', [StatisticController::class, 'index']);
        Route::get('/resume-sections', [ResumeController::class, 'index']);
        Route::get('/portfolio-items', [ApiPortfolioController::class, 'index']);
        Route::get('/services', [ServiceController::class, 'active']); // Only active services
        Route::get('/testimonials', [TestimonialController::class, 'index']);
        Route::get('/contact-info', [ContactInfoController::class, 'index']);
        Route::get('/site-settings', [SiteSettingController::class, 'publicSettings']);
    });
});

// Contact Form Submission Route
Route::post('/contact', function (\Illuminate\Http\Request $request) {
    // Validate the request
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'subject' => 'required|string|max:255',
        'message' => 'required|string'
    ]);
    
    // Here you can:
    // 1. Save to database
    // 2. Send email notification
    // 3. Integrate with a CRM
    
    // For now, just return success
    return response()->json([
        'success' => true,
        'message' => 'Thank you for your message! I will get back to you soon.'
    ]);
})->name('contact.submit');

// Handle all other routes (404)
Route::fallback(function () {
    return inertia('NotFound', [
        'status' => 404,
        'message' => 'Page Not Found'
    ]);
});