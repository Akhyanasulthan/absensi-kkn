<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// User Routes (Requires login)
Route::middleware(['auth'])->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('user.index');
    Route::post('/attendance', [AttendanceController::class, 'store'])->name('user.attendance.store');
});

// Unified Authentication Routes
Route::get('/login', [AdminController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminController::class, 'login'])->name('login.submit');
Route::post('/logout', [AdminController::class, 'logout'])->name('logout');

// Backwards compatibility redirects for old URLs
Route::get('/admin/login', function () { return redirect()->route('login'); })->name('admin.login');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Admin Protected Dashboard & Settings
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/qr', [AdminController::class, 'showQr'])->name('admin.qr');
    Route::get('/admin/qr-token', [AdminController::class, 'getQrToken'])->name('admin.qr.token');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/settings', [AdminController::class, 'updateSettings'])->name('admin.settings.update');
    Route::get('/admin/logs', [AdminController::class, 'weeklyLogs'])->name('admin.logs');
    Route::post('/admin/logs/manual', [AdminController::class, 'storeManualAttendance'])->name('admin.logs.manual');
    Route::get('/admin/logs/export/excel', [AdminController::class, 'exportExcel'])->name('admin.logs.export.excel');
    Route::get('/admin/logs/export/pdf', [AdminController::class, 'exportPdf'])->name('admin.logs.export.pdf');

    // Admin Students CRUD
    Route::get('/admin/students', [AdminController::class, 'studentsIndex'])->name('admin.students');
    Route::post('/admin/students', [AdminController::class, 'storeStudent'])->name('admin.students.store');
    Route::put('/admin/students/{student}', [AdminController::class, 'updateStudent'])->name('admin.students.update');
    Route::delete('/admin/students/{student}', [AdminController::class, 'deleteStudent'])->name('admin.students.delete');
});
