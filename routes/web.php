<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CafeProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'redirect'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
        ->middleware('role:admin')
        ->name('admin.dashboard');

    Route::get('/kasir/dashboard', [DashboardController::class, 'kasir'])
        ->middleware('role:kasir')
        ->name('kasir.dashboard');

    Route::get('/owner/dashboard', [DashboardController::class, 'owner'])
        ->middleware('role:owner')
        ->name('owner.dashboard');

    Route::get('/admin/cafe-profile', [CafeProfileController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.cafe-profile.edit');

    Route::put('/admin/cafe-profile', [CafeProfileController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.cafe-profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__.'/auth.php';