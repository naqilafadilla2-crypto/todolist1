<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\AppLinkController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\RackController;
use App\Http\Controllers\MaintenanceChecklistController;
use App\Http\Controllers\ChecklistExpiredDateController;
use App\Http\Controllers\ActivityLogController;



// Halaman Login Utama (landing page)
Route::get('/', [LoginController::class, 'loginPage'])->name('login'); 

// Halaman Form Sign In
Route::get('/signin', [LoginController::class, 'signinPage'])->name('signin.page');
Route::post('/signin', [LoginController::class, 'signinProcess'])->name('signin.process');

// Group route yang butuh login
Route::middleware(['auth'])->group(function () {

    // Menu utama
    Route::get('/menu', [MonitoringController::class, 'dashboard'])->name('menu');

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
    Route::get('/rack/report', [RackController::class, 'report'])->name('rack.report');
    Route::get('/rack/report/pdf', [RackController::class, 'pdf'])->name('rack.report.pdf');
    Route::get('/rack/report/excel', [RackController::class, 'excel'])->name('rack.report.excel');
    
    // Device status check routes
    Route::post('/rack/devices/check-all', [RackController::class, 'checkAllDevices'])->name('rack.devices.check-all');
    Route::post('/rack/devices/{deviceId}/check', [RackController::class, 'checkDevice'])->name('rack.devices.check');
    
    // Device log export routes
    Route::get('/rack/devices/{deviceId}/export-logs', [RackController::class, 'exportDeviceLogs'])->name('rack.devices.export-logs');
    
    // Maintenance checklist routes
    Route::get('/maintenance-checklist', [MaintenanceChecklistController::class, 'index'])->name('maintenance.checklist.index');
    Route::get('/maintenance-checklist/report', [MaintenanceChecklistController::class, 'report'])->name('maintenance.checklist.report');
    Route::get('/maintenance-checklist/report/pdf', [MaintenanceChecklistController::class, 'pdf'])->name('maintenance.checklist.report.pdf');
    Route::get('/maintenance-checklist/report/excel', [MaintenanceChecklistController::class, 'excel'])->name('maintenance.checklist.report.excel');
    Route::put('/maintenance-checklist/{id}', [MaintenanceChecklistController::class, 'update'])->name('maintenance.checklist.update');
    Route::put('/maintenance-checklist/{id}/keterangan', [MaintenanceChecklistController::class, 'updateKeterangan'])->name('maintenance.checklist.keterangan');
    Route::put('/maintenance-checklist/{id}/checkbox', [MaintenanceChecklistController::class, 'toggleCheckbox'])->name('maintenance.checklist.checkbox');
    Route::post('/maintenance-checklist', [MaintenanceChecklistController::class, 'storePerangkat'])->name('maintenance.checklist.store');
    Route::delete('/maintenance-checklist/{id}', [MaintenanceChecklistController::class, 'deletePerangkat'])->name('maintenance.checklist.delete');
    
    // Maintenance logs routes
    Route::post('/maintenance-checklist/{id}/logs', [MaintenanceChecklistController::class, 'storeLog'])->name('maintenance.log.store');
    Route::put('/maintenance-checklist/{id}/logs/{logId}', [MaintenanceChecklistController::class, 'updateLog'])->name('maintenance.log.update');
    Route::delete('/maintenance-checklist/{id}/logs/{logId}', [MaintenanceChecklistController::class, 'deleteLog'])->name('maintenance.log.delete');
    
    // Activity log routes
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::delete('/activity-log/{id}', [ActivityLogController::class, 'destroy'])->name('activity-log.destroy');
    Route::post('/activity-log/clear-all', [ActivityLogController::class, 'clearAll'])->name('activity-log.clear-all');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::get('/checklist', [ChecklistExpiredDateController::class, 'index'])
    ->name('checklist.index');

Route::get('/checklist/laporan', [ChecklistExpiredDateController::class, 'report'])
    ->name('checklist.report');

Route::get('/checklist/laporan/pdf', [ChecklistExpiredDateController::class, 'pdf'])
    ->name('checklist.report.pdf');

Route::get('/checklist/laporan/excel', [ChecklistExpiredDateController::class, 'excel'])
    ->name('checklist.report.excel');

Route::get('/checklist/create', [ChecklistExpiredDateController::class, 'create'])
    ->name('checklist.create');

Route::post('/checklist/store', [ChecklistExpiredDateController::class, 'store'])
    ->name('checklist.store');

Route::get('/checklist/{id}/edit', [ChecklistExpiredDateController::class, 'edit'])
    ->name('checklist.edit');

Route::put('/checklist/{id}', [ChecklistExpiredDateController::class, 'update'])
    ->name('checklist.update');

Route::delete('/checklist/{id}', [ChecklistExpiredDateController::class, 'destroy'])
    ->name('checklist.destroy');