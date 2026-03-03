<?php

use App\Http\Controllers\Account;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminSiteController;
use App\Http\Controllers\Admin\AdminStatsController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\Dashboard\SiteController;
use App\Http\Controllers\Dashboard\StatsController;
use App\Http\Controllers\IndexController;
use Illuminate\Support\Facades\Route;

Route::get('/', [IndexController::class, 'index'])->name('index');

Route::get('/view/counties', fn () => view('api.counties'));
Route::get('/view/counties/{county}/localities', fn ($county) => view('api.localities', ['county' => $county]));

Route::get('/exemple-api-judete-localitati', [IndexController::class, 'examples'])->name('examples.index');
Route::get('/docs', fn () => view('docs.index'))->name('docs');

Route::middleware('api')
    ->group(base_path('routes/api.php'));

// ===============================
// Guest Only
// ===============================
Route::middleware('guest')->group(function (): void {

    // Login / Register / Password Reset
    Route::get('/login', [Auth\LoginController::class, 'show'])->name('login');
    Route::post('/login', [Auth\LoginController::class, 'login']);

    Route::get('/register', [Auth\RegisterController::class, 'show'])->name('register');
    Route::post('/register', [Auth\RegisterController::class, 'register']);

    Route::get('/forgot-password', [Auth\ForgotPasswordController::class, 'show'])->name('password.request');
    Route::post('/forgot-password', [Auth\ForgotPasswordController::class, 'send'])->name('password.email');

    Route::get('/reset-password/{token}', [Auth\ResetPasswordController::class, 'show'])->name('password.reset');
    Route::post('/reset-password', [Auth\ResetPasswordController::class, 'reset'])->name('password.update');

    // ===============================
    // Google / Facebook Login
    // ===============================
    Route::get('/auth/{provider}/redirect', [SocialAuthController::class, 'redirect'])
        ->where('provider', 'google|facebook')
        ->name('social.redirect');

    Route::get('/auth/{provider}/callback', [Auth\SocialAuthController::class, 'callback'])
        ->where('provider', 'google|facebook')
        ->name('social.callback');
});

// ===============================
// Logged In Users
// ===============================
Route::middleware(['auth'])->group(function (): void {

    // Logged in users but unverified
    Route::post('/logout', Auth\LogoutController::class)->name('logout');
    Route::get('/email/verify', [Auth\EmailVerificationController::class, 'notice'])
        ->name('verification.notice');
    Route::post('/email/resend', [Auth\EmailVerificationController::class, 'send'])
        ->middleware('throttle:6,1')
        ->name('verification.send');
    Route::get('/email/verify/{id}/{hash}', [Auth\EmailVerificationController::class, 'verify'])
        ->middleware('signed')
        ->name('verification.verify');

    // ===============================
    // Verified Users
    // ===============================
    Route::middleware('verified')
        ->group(function (): void {

            // ===============================
            // DASHBOARD - Sites, Stats, Profile
            // ===============================
            Route::prefix('dashboard')
                ->name('dashboard.')
                ->group(function (): void {

                    // Sites (API Keys)
                    Route::get('/', [SiteController::class, 'index'])->name('index');
                    Route::get('/sites', [SiteController::class, 'index'])->name('sites.index');
                    Route::get('/sites/create', [SiteController::class, 'create'])->name('sites.create');
                    Route::post('/sites', [SiteController::class, 'store'])->name('sites.store');
                    Route::get('/sites/{site}', [SiteController::class, 'show'])->name('sites.show');
                    Route::post('/sites/{site}/regenerate', [SiteController::class, 'regenerateToken'])->name('sites.regenerate');
                    Route::delete('/sites/{site}', [SiteController::class, 'destroy'])->name('sites.destroy');

                    // Stats
                    Route::get('/stats', [StatsController::class, 'index'])->name('stats.index');

                    // Profile
                    Route::get('/profile', [Account\ProfileController::class, 'edit'])->name('profile.edit');
                    Route::put('/profile', [Account\ProfileController::class, 'update'])->name('profile.update');
                    Route::delete('/profile/avatar', [Account\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
                });

            // ===============================
            // ADMIN Panel
            // ===============================
            Route::prefix('admin')
                ->name('admin.')
                ->middleware('role:admin')
                ->group(function (): void {

                    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

                    Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
                    Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
                    Route::post('/users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');

                    Route::get('/sites', [AdminSiteController::class, 'index'])->name('sites.index');
                    Route::get('/sites/{site}', [AdminSiteController::class, 'show'])->name('sites.show');
                    Route::post('/sites/{site}/toggle', [AdminSiteController::class, 'toggleActive'])->name('sites.toggle');

                    Route::get('/stats', [AdminStatsController::class, 'index'])->name('stats.index');
                });
        });
});
