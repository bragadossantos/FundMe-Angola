<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DocumentController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/como-funciona', [HomeController::class, 'howItWorks'])->name('how_it_works');
Route::get('/campanhas', [CampaignController::class, 'index'])->name('campaigns.index');
Route::get('/campanhas/{slug}', [CampaignController::class, 'show'])->name('campaigns.show');

Route::post('/campanhas/{campaign}/doar', [DonationController::class, 'store'])->name('donations.store')->middleware('throttle:10,1');
Route::get('/doacoes/{donation}/checkout', [DonationController::class, 'checkout'])->name('donations.checkout');
Route::post('/doacoes/{donation}/confirmar', [DonationController::class, 'confirm'])->name('donations.confirm')->middleware('throttle:10,1');

Route::post('/campanhas/{campaign}/denunciar', [ReportController::class, 'store'])->name('reports.store')->middleware('throttle:5,10');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/registo', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/registo', [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::get('/esqueci-palavra-passe', [AuthController::class, 'showForgotPassword'])->name('forgot_password');
    Route::post('/esqueci-palavra-passe', [AuthController::class, 'sendResetLink'])->middleware('throttle:3,1');
    Route::get('/redefinir-palavra-passe/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
    Route::post('/redefinir-palavra-passe', [AuthController::class, 'resetPassword'])->name('reset_password')->middleware('throttle:5,1');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

/*
|--------------------------------------------------------------------------
| User & Applicant Dashboard Routes (Authenticated)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/pedir-ajuda', [CampaignController::class, 'create'])->name('campaigns.create');
    Route::post('/pedir-ajuda', [CampaignController::class, 'store'])->name('campaigns.store');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/doacoes', [DashboardController::class, 'donations'])->name('dashboard.donations');
    Route::get('/dashboard/campanhas', [DashboardController::class, 'campaigns'])->name('dashboard.campaigns');
    Route::get('/dashboard/perfil', [DashboardController::class, 'profile'])->name('dashboard.profile');
    Route::post('/dashboard/perfil', [DashboardController::class, 'updateProfile'])->name('dashboard.profile.update');

    Route::get('/documentos/{document}/download', [DocumentController::class, 'download'])->name('documents.download');
});

/*
|--------------------------------------------------------------------------
| Verifier & Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verifier'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/campanhas', [AdminController::class, 'campaigns'])->name('campaigns');
    Route::get('/campanhas/{campaign}', [AdminController::class, 'showCampaign'])->name('campaigns.show');
    Route::post('/campanhas/{campaign}/status', [AdminController::class, 'updateCampaignStatus'])->name('campaigns.update_status');

    Route::get('/doacoes', [AdminController::class, 'donations'])->name('donations');
    Route::post('/doacoes/{donation}/confirmar-manual', [AdminController::class, 'confirmDonation'])->name('donations.confirm_manual');

    Route::get('/denuncias', [AdminController::class, 'reports'])->name('reports');
    Route::get('/denuncias/{report}/evidencia', [AdminController::class, 'downloadReportEvidence'])->name('reports.evidence');
    Route::post('/denuncias/{report}/status', [AdminController::class, 'updateReport'])->name('reports.update_status');

    Route::get('/documentos', [AdminController::class, 'documents'])->name('documents');
    Route::get('/logs', [AdminController::class, 'logs'])->name('logs');

    // Admin-only: user role management and financial fund disbursement. A
    // Verifier must NOT be able to promote accounts (including their own) to
    // Admin, nor authorize the release of funds — both require the stricter
    // 'admin' middleware on top of 'verifier'.
    Route::middleware('admin')->group(function () {
        Route::get('/utilizadores', [AdminController::class, 'users'])->name('users');
        Route::post('/utilizadores/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.update_role');

        Route::get('/pagamentos', [AdminController::class, 'payments'])->name('payments');
        Route::post('/pagamentos/{campaign}/desembolsar', [AdminController::class, 'disburse'])->name('payments.disburse');
    });
});
