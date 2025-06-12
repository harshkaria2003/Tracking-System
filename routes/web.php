<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\TimeLogController;
use App\Http\Controllers\UserTrackingController; 

// ---------------- Guest Routes (Login) ----------------
Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// ---------------- Authenticated Routes ----------------
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // General Dashboard/Profile (all roles)
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    // Timer Control (all roles)
    Route::post('/timer/start', [TimeLogController::class, 'start'])->name('timer.start');
    Route::post('/timer/stop', [TimeLogController::class, 'stop'])->name('timer.stop');

    // Time Log Report (all roles)
    Route::get('/time-log/report', [TimeLogController::class, 'report'])->name('time_log.report');

    // AJAX: Fetch projects
    Route::post('/get-projects', [TimeLogController::class, 'getEmployeeProjects'])->name('get.projects');

    
    Route::post('/user-tracking/store', [UserTrackingController::class, 'store'])->name('user-tracking.store');
    Route::post('/user-tracking/update-count', [UserTrackingController::class, 'updateCount'])->name('user-tracking.update-count');

    // ---------------- Super Admin Routes ----------------
    Route::middleware('role:super_admin')->prefix('superadmin')->name('superadmin.')->group(function () {
        Route::get('/dashboard', [AuthController::class, 'superAdminDashboard'])->name('dashboard');

        // Admin management
        Route::get('/admin/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');

        // Time Log
        Route::get('/time-log/create', [TimeLogController::class, 'create'])->name('time_log.create');
        Route::post('/time-log/store', [TimeLogController::class, 'store'])->name('time_log.store');
    });

    // ---------------- Admin Routes ----------------
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'index'])->name('dashboard');

        // Employee management
        Route::post('/employees', [EmployeeController::class, 'store'])->name('employee.store');
        Route::get('/timelog/report', [AdminController::class, 'report'])->name('timelog.report');
        Route::get('/employee/hours-summary', [AdminController::class, 'employeeHoursReport'])->name('admin.employee.report');

        // Time Log
        Route::get('/time-log/create', [TimeLogController::class, 'create'])->name('time_log.create');
        Route::post('/time-log/store', [TimeLogController::class, 'store'])->name('time_log.store');
    });

    // ---------------- Employee Routes ----------------
    Route::middleware('role:employee')->prefix('employee')->name('employee.')->group(function () {
        Route::get('/dashboard', [TimeLogController::class, 'showTaskForm'])->name('dashboard');
        Route::post('/timer/start', [TimeLogController::class, 'start'])->name('timer.start');
        Route::post('/timer/stop', [TimeLogController::class, 'stop'])->name('timer.stop');
        Route::get('/timelog/report', [TimeLogController::class, 'report'])->name('timelog.report');
    });
});

// -------------- Fallback --------------
Route::fallback(function () {
    return redirect()->route('login')->with('error', 'Page not found or unauthorized access.');
});
