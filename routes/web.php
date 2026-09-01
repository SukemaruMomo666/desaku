<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLetterTypeController;
use App\Http\Controllers\AdminLetterRequestController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminSettingController;

Route::get('/', function () {
    $letterTypes = \App\Models\LetterType::where('is_active', true)->get();
    $articles = \App\Models\Article::where('is_active', true)->latest()->take(6)->get();
    return view('welcome', compact('letterTypes', 'articles'));
});

// Public letter routes
Route::get('/letter-types/{id}/download-statement', [AdminLetterTypeController::class, 'downloadStatementLetter'])->name('letter-types.download-statement');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    
    Route::get('/register/otp', [AuthController::class, 'showOtp'])->name('otp.show');
    Route::post('/register/otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Fallback Dashboard Redirector
    Route::get('/dashboard', function() {
        if(in_array(auth()->user()->role, ['master_admin', 'super_admin', 'admin'])) return redirect()->route('admin.dashboard');
        return redirect()->route('citizen.dashboard');
    });

    // Warga Routes
    Route::middleware('can:is-warga')->group(function() {
        Route::get('/dashboard/warga', [CitizenController::class, 'index'])->name('citizen.dashboard');
        
        // Pengajuan Surat
        Route::get('/dashboard/warga/request', [CitizenController::class, 'createRequest'])->name('citizen.request.create');
        Route::post('/dashboard/warga/request', [CitizenController::class, 'storeRequest'])->name('citizen.request.store');

        // Profil Warga
        Route::get('/dashboard/warga/profile', [CitizenController::class, 'profile'])->name('citizen.profile');
        Route::put('/dashboard/warga/profile', [CitizenController::class, 'updateProfile'])->name('citizen.profile.update');
    });

    // Admin Routes
    Route::middleware('can:is-admin')->group(function() {
        Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::middleware('can:manage-requests')->group(function() {
            Route::get('admin/letter-requests', [AdminLetterRequestController::class, 'index'])->name('admin.letter-requests.index');
            Route::get('admin/letter-requests/{id}', [AdminLetterRequestController::class, 'show'])->name('admin.letter-requests.show');
            Route::post('admin/letter-requests/{id}/status', [AdminLetterRequestController::class, 'updateStatus'])->name('admin.letter-requests.update-status');
            Route::get('admin/letter-requests/{id}/print', [AdminLetterRequestController::class, 'downloadDocx'])->name('admin.letter-requests.print');
        });

        Route::middleware('can:manage-letter-types')->group(function() {
            Route::resource('admin/letter-types', AdminLetterTypeController::class)->names('admin.letter-types')->except(['show']);
            Route::get('admin/letter-types/{id}/download-template', [AdminLetterTypeController::class, 'downloadTemplate'])->name('admin.letter-types.download-template');
        });

        Route::middleware('can:manage-articles')->group(function() {
            Route::resource('admin/articles', \App\Http\Controllers\ArticleController::class)->names('admin.articles');
            Route::post('admin/articles/{id}/toggle', [\App\Http\Controllers\ArticleController::class, 'toggleActive'])->name('admin.articles.toggle');
        });
            
        Route::middleware('can:manage-users')->group(function() {
            Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
            Route::get('admin/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
        });
        
        // Admin Settings
        Route::middleware('can:is-master-admin')->group(function() {
            Route::get('/dashboard/admin/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
            Route::post('/dashboard/admin/settings/signatories', [AdminSettingController::class, 'storeSignatory'])->name('admin.settings.signatories.store');
            Route::delete('/dashboard/admin/settings/signatories/{id}', [AdminSettingController::class, 'destroySignatory'])->name('admin.settings.signatories.destroy');
            Route::get('/dashboard/admin/settings/backup', [AdminSettingController::class, 'backupArchive'])->name('admin.settings.backup');
            Route::post('/dashboard/admin/settings/clean', [AdminSettingController::class, 'cleanArchive'])->name('admin.settings.clean');
            
            Route::post('/dashboard/admin/settings/accounts', [AdminSettingController::class, 'storeAdmin'])->name('admin.settings.accounts.store');
            Route::delete('/dashboard/admin/settings/accounts/{id}', [AdminSettingController::class, 'destroyAdmin'])->name('admin.settings.accounts.destroy');
            Route::post('/dashboard/admin/settings/role-permissions', [AdminSettingController::class, 'updateRolePermissions'])->name('admin.settings.role-permissions.update');
        });
    });
});
