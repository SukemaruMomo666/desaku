<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitizenController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLetterTypeController;
use App\Http\Controllers\AdminLetterRequestController;
use App\Http\Controllers\AdminUserController;

Route::get('/', function () {
    return view('welcome');
});

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
        if(auth()->user()->role === 'admin') return redirect()->route('admin.dashboard');
        return redirect()->route('citizen.dashboard');
    });

    // Warga Routes
    Route::middleware('can:is-warga')->group(function() {
        Route::get('/dashboard/warga', [CitizenController::class, 'index'])->name('citizen.dashboard');
        
        // Pengajuan Surat
        Route::get('/dashboard/warga/request', [CitizenController::class, 'createRequest'])->name('citizen.request.create');
        Route::post('/dashboard/warga/request', [CitizenController::class, 'storeRequest'])->name('citizen.request.store');
    });

    // Admin Routes
    Route::middleware('can:is-admin')->group(function() {
        Route::get('/dashboard/admin', [AdminController::class, 'index'])->name('admin.dashboard');
        
        Route::resource('admin/letter-types', AdminLetterTypeController::class)->names('admin.letter-types')->except(['show']);
        Route::get('admin/letter-types/{id}/download-template', [AdminLetterTypeController::class, 'downloadTemplate'])->name('admin.letter-types.download-template');
        
        Route::get('admin/letter-requests', [AdminLetterRequestController::class, 'index'])->name('admin.letter-requests.index');
        Route::get('admin/letter-requests/{id}', [AdminLetterRequestController::class, 'show'])->name('admin.letter-requests.show');
        Route::post('admin/letter-requests/{id}/status', [AdminLetterRequestController::class, 'updateStatus'])->name('admin.letter-requests.update-status');
        Route::get('admin/letter-requests/{id}/print', [AdminLetterRequestController::class, 'downloadDocx'])->name('admin.letter-requests.print');

        Route::get('admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('admin/users/{id}', [AdminUserController::class, 'show'])->name('admin.users.show');
    });
});
