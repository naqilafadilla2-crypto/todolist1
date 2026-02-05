<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AppLinkController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RackController;

// Halaman Login Utama (landing page)
Route::get('/', [LoginController::class, 'loginPage'])->name('login'); 

// Halaman Form Sign In
Route::get('/signin', [LoginController::class, 'signinPage'])->name('signin.page');
Route::post('/signin', [LoginController::class, 'signinProcess'])->name('signin.process');

// Group route yang butuh login
Route::middleware(['auth'])->group(function () {

    // Menu utama
    Route::get('/menu', function () {
        return view('menu');
    })->name('menu');

    // Halaman Task

    // Monitoring routes untuk admin (CRUD lengkap)
    Route::get('/monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
    Route::get('/monitoring/create', [MonitoringController::class, 'create'])->name('monitoring.create');
    Route::post('/monitoring', [MonitoringController::class, 'store'])->name('monitoring.store');
    Route::get('/monitoring/{id}', [MonitoringController::class, 'show'])->name('monitoring.show');
    Route::get('/monitoring/{id}/edit', [MonitoringController::class, 'edit'])->name('monitoring.edit');
    Route::put('/monitoring/{id}', [MonitoringController::class, 'update'])->name('monitoring.update');
    Route::delete('/monitoring/{id}', [MonitoringController::class, 'destroy'])->name('monitoring.destroy');
    Route::get('/monitoring/{id}/download', [MonitoringController::class, 'download'])->name('monitoring.download');

    // Dashboard monitoring untuk user (hanya view dan download)
    Route::get('/dashboard', [MonitoringController::class, 'userDashboard'])->name('monitoring.user.dashboard');
    Route::get('/dashboard/{id}', [MonitoringController::class, 'userShow'])->name('monitoring.user.show');

    // Kelola aplikasi (card menu)
    Route::get('/applink', [AppLinkController::class, 'index'])->name('applink.index');
    Route::get('/applink/create', [AppLinkController::class, 'create'])->name('applink.create');
    Route::post('/applink', [AppLinkController::class, 'store'])->name('applink.store');
    Route::get('/applink/{id}/edit', [AppLinkController::class, 'edit'])->name('applink.edit');
    Route::put('/applink/{id}', [AppLinkController::class, 'update'])->name('applink.update');
    Route::delete('/applink/{id}', [AppLinkController::class, 'destroy'])->name('applink.destroy');

    // Laporan routes
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan');
    Route::get('/laporan/pdf', [LaporanController::class, 'pdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [LaporanController::class, 'excel'])->name('laporan.excel');

    // User management routes
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{id}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{id}', [UserController::class, 'update'])->name('user.update');
    Route::delete('/user/{id}', [UserController::class, 'destroy'])->name('user.destroy');

    // Rack management routes
    Route::get('/rack', [RackController::class, 'index'])->name('rack.index');
    Route::post('/rack', [RackController::class, 'store'])->name('rack.store');
    Route::delete('/rack/{id}', [RackController::class, 'destroy'])->name('rack.destroy');
    
    // Device status check routes
    Route::post('/rack/devices/check-all', [RackController::class, 'checkAllDevices'])->name('rack.devices.check-all');
    Route::post('/rack/devices/{deviceId}/check', [RackController::class, 'checkDevice'])->name('rack.devices.check');
    
    // Device log export routes
    Route::get('/rack/devices/{deviceId}/export-logs', [RackController::class, 'exportDeviceLogs'])->name('rack.devices.export-logs');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});