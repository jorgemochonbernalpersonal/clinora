<?php

use App\Http\Controllers\LogViewerController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes specific to admin users. Admins have access to all system
| functionality plus administrative tools. All routes here are
| automatically protected with: auth, verified, role:admin middleware.
|
*/

// Admin Dashboard
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// System Logs (full access for admins)
Route::get('/logs', [LogViewerController::class, 'index'])->name('logs.index');

// User Management
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', function () {
        return view('admin.users.index');
    })->name('index');
    
    Route::get('/create', function () {
        return view('admin.users.create');
    })->name('create');
    
    Route::get('/{id}/edit', function ($id) {
        return view('admin.users.edit', ['id' => $id]);
    })->name('edit');
});

// System Settings
Route::prefix('settings')->name('settings.')->group(function () {
    Route::get('/', function () {
        return view('admin.settings.index');
    })->name('index');
    
    Route::get('/email', function () {
        return view('admin.settings.email');
    })->name('email');
    
    Route::get('/security', function () {
        return view('admin.settings.security');
    })->name('security');
});

// Analytics & Reports
Route::prefix('analytics')->name('analytics.')->group(function () {
    Route::get('/', function () {
        return view('admin.analytics.index');
    })->name('index');
    
    Route::get('/users', function () {
        return view('admin.analytics.users');
    })->name('users');
    
    Route::get('/revenue', function () {
        return view('admin.analytics.revenue');
    })->name('revenue');
});
