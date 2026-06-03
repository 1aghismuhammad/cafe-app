<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\CafeProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\OutletController;

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

    Route::get('/admin/outlets', [OutletController::class, 'index'])
        ->middleware('role:admin')
        ->name('admin.outlets.index');

    Route::get('/admin/outlets/create', [OutletController::class, 'create'])
        ->middleware('role:admin')
        ->name('admin.outlets.create');

    Route::post('/admin/outlets', [OutletController::class, 'store'])
        ->middleware('role:admin')
        ->name('admin.outlets.store');

    Route::get('/admin/outlets/{outlet}/edit', [OutletController::class, 'edit'])
        ->middleware('role:admin')
        ->name('admin.outlets.edit');

    Route::put('/admin/outlets/{outlet}', [OutletController::class, 'update'])
        ->middleware('role:admin')
        ->name('admin.outlets.update');

    Route::delete('/admin/outlets/{outlet}', [OutletController::class, 'destroy'])
        ->middleware('role:admin')
        ->name('admin.outlets.destroy');
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