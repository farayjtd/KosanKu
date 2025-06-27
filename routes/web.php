<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LandboardController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\TokenController;
use App\Http\Controllers\RentalHistoryController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TenantController;
Route::redirect('/', '/login')->name('auth');

// AUTH
Route::get('/login', [AuthController::class, 'showAuthForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');
Route::post('/signup', [AuthController::class, 'register'])->name('signup.process');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// LANDBOARD
Route::middleware(['auth', 'role:landboard'])->prefix('landboard')->group(function () {
    Route::get('/dashboard', [LandboardController::class, 'dashboard'])->name('landboard.dashboard.index');

    Route::get('/complete-profile', [LandboardController::class, 'createProfile'])->name('landboard.profile.complete-form');
    Route::post('/complete-profile', [LandboardController::class, 'storeProfile'])->name('landboard.complete-profile.store');
    Route::get('/update-profile', [LandboardController::class, 'editProfile'])->name('landboard.profile.update-form');
    Route::post('/update-profile', [LandboardController::class, 'updateProfile'])->name('landboard.profile.update');

    Route::get('/rooms', [RoomController::class, 'index'])->name('landboard.rooms.index');
    Route::get('/room/create', [RoomController::class, 'create'])->name('landboard.rooms.create-form');
    Route::post('/room/store', [RoomController::class, 'store'])->name('landboard.rooms.store');
    Route::get('/room/{id}', [RoomController::class, 'show'])->name('landboard.rooms.show');
    Route::get('/room/{id}/edit', [RoomController::class, 'edit'])->name('landboard.rooms.edit-form');
    Route::put('/room/{id}/update', [RoomController::class, 'update'])->name('landboard.rooms.update');
    Route::get('/room/{id}/duplicate', [RoomController::class, 'duplicateForm'])->name('landboard.rooms.duplicate-form');
    Route::post('/room/{id}/duplicate', [RoomController::class, 'duplicate'])->name('landboard.rooms.duplicate');
    Route::delete('/rooms/{room}', [RoomController::class, 'destroy'])->name('landboard.rooms.destroy');

    Route::post('/room/{room}/generate-token', [TokenController::class, 'generate'])->name('tokens.generate');

    Route::get('/rental-history', [RentalHistoryController::class, 'landboardHistory'])->name('landboard.rental-history.index');
    Route::get('/current-tenants', [RentalHistoryController::class, 'currentTenants'])->name('landboard.current-tenants');
    Route::get('/current-tenants/{id}', [RentalHistoryController::class, 'showCurrentTenant'])->name('landboard.current-tenants.show');

    Route::get('/penalty-settings', [PenaltyController::class, 'edit'])->name('penalty.edit');
    Route::patch('/penalty-settings', [PenaltyController::class, 'update'])->name('penalty.update');
});

// TENANT ROUTES
Route::middleware(['auth', 'role:tenant'])->prefix('tenant')->group(function () {
    Route::get('/dashboard', [TenantController::class, 'dashboard'])->name('tenant.dashboard.index');

    Route::get('/complete-profile', [TenantController::class, 'createProfile'])->name('tenant.profile.complete-form');
    Route::post('/complete-profile', [TenantController::class, 'storeProfile'])->name('tenant.profile.complete.store');
    Route::get('/update-profile', [TenantController::class, 'editProfile'])->name('tenant.profile.update-form');
    Route::post('/profile/update', [TenantController::class, 'updateProfile'])->name('tenant.profile.update');

    Route::post('/use-token', [TokenController::class, 'use'])->name('tokens.use');

    Route::get('/rental-history', [RentalHistoryController::class, 'tenantHistory'])->name('tenant.room-history.index');

    Route::post('/decide', [TenantController::class, 'decide'])->name('tenant.decide');

    Route::post('/leave-room', [TenantController::class, 'leaveRoom'])->name('tenant.leave-room');
    
    Route::post('/invoice/{rentalId}/pay', [PaymentController::class, 'createInvoice'])->name('tenant.invoice.pay');
});




