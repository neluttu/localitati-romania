<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth as Auth;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\Account as Account;


Route::get('/', [IndexController::class, 'index'])->name('index');

Route::get('/view/counties', fn() => view('api.counties'));
Route::get('/view/counties/{county}/localities', fn($county) => view('api.localities', ['county' => $county]));
// Route::get('/view/counties/{county}/localities-grouped', fn($county) => view('api.localities-grouped', ['county' => $county]));


Route::get('/exemple-api-judete-localitati', [IndexController::class, 'examples'])->name('examples.index');

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
            // USER ACCOUNT
            // ===============================
            Route::prefix('account')
                ->name('account.')
                ->group(function () {

                Route::get('/', [Account\ProfileController::class, 'edit'])->name('index');
                Route::get('/profile', [Account\ProfileController::class, 'edit'])->name('profile');
                Route::put('/profile', [Account\ProfileController::class, 'update'])->name('profile.update');
                Route::delete('/profil/avatar-delete', [Account\ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');
                Route::get('/billing', [Account\BillingController::class, 'edit'])->name('billing');
                Route::put('/billing', [Account\BillingController::class, 'update'])->name('billing.update');
            });
        });
});
