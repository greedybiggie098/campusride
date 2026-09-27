<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\RideController as AdminRideController;
use App\Http\Controllers\Admin\DriverController as AdminDriverController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ContactMessageController;
use App\Http\Controllers\Admin\DriverApplicationController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Passenger\DashboardController as PassengerDashboardController;
use App\Http\Controllers\Passenger\RideController as PassengerRideController;
use App\Http\Controllers\Driver\DashboardController as DriverDashboardController;
use App\Http\Controllers\Driver\RideController as DriverRideController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PublicController;

// ─── Public Landing Pages ───────────────────────────────────────────────────
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/services', [PublicController::class, 'services'])->name('services');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('contact.submit');
Route::get('/apply-to-drive', [PublicController::class, 'applyToDrive'])->name('driver.apply');
Route::post('/apply-to-drive', [PublicController::class, 'submitDriverApplication'])->name('driver.apply.submit');

// ─── Role-based redirect after login ─────────────────────────────────────────
Route::get('/dashboard', function () {
    return match (Auth::user()->role) {
        'admin'  => redirect()->route('admin.dashboard'),
        'driver' => redirect()->route('driver.dashboard'),
        default  => redirect()->route('passenger.dashboard'),
    };
})->middleware(['auth', 'verified'])->name('dashboard');

// ─── Profile (Breeze) ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ─── Admin ────────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])
        ->name('admin.dashboard');
    Route::get('/admin/rides', [AdminRideController::class, 'index'])->name('admin.rides.index');
    Route::get('/admin/rides/{ride}', [AdminRideController::class, 'show'])->name('admin.rides.show');
    Route::patch('/admin/rides/{ride}/assign', [AdminRideController::class, 'assign'])->name('admin.rides.assign');
    Route::patch('/admin/rides/{ride}/cancel', [AdminRideController::class, 'cancel'])->name('admin.rides.cancel');
    Route::patch('/admin/rides/{ride}/status', [AdminRideController::class, 'updateStatus'])->name('admin.rides.status');
    Route::patch('/admin/rides/{ride}/payment', [AdminRideController::class, 'updatePayment'])->name('admin.rides.payment');
    Route::resource('/admin/drivers', AdminDriverController::class)->names('admin.drivers');
    Route::resource('/admin/users', AdminUserController::class)->names('admin.users');
    Route::get('/admin/messages', [ContactMessageController::class, 'index'])->name('admin.messages.index');
    Route::patch('/admin/messages/{message}/status', [ContactMessageController::class, 'updateStatus'])->name('admin.messages.status');
    Route::delete('/admin/messages/{message}', [ContactMessageController::class, 'destroy'])->name('admin.messages.destroy');
    Route::get('/admin/driver-applications', [DriverApplicationController::class, 'index'])->name('admin.driver-applications.index');
    Route::get('/admin/driver-applications/{application}', [DriverApplicationController::class, 'show'])->name('admin.driver-applications.show');
    Route::post('/admin/driver-applications/{application}/approve', [DriverApplicationController::class, 'approve'])->name('admin.driver-applications.approve');
    Route::post('/admin/driver-applications/{application}/reject', [DriverApplicationController::class, 'reject'])->name('admin.driver-applications.reject');
    Route::get('/admin/settings', [SettingsController::class, 'edit'])->name('admin.settings.edit');
    Route::patch('/admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
});

// ─── Passenger ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'passenger'])->group(function () {
    Route::get('/passenger/dashboard', [PassengerDashboardController::class, 'index'])
        ->name('passenger.dashboard');
    Route::get('/passenger/rides', [PassengerRideController::class, 'index'])->name('passenger.rides.index');
    Route::get('/passenger/rides/create', [PassengerRideController::class, 'create'])->name('passenger.rides.create');
    Route::post('/passenger/rides', [PassengerRideController::class, 'store'])->name('passenger.rides.store');
    Route::get('/passenger/rides/{ride}', [PassengerRideController::class, 'show'])->name('passenger.rides.show');
    Route::patch('/passenger/rides/{ride}/cancel', [PassengerRideController::class, 'cancel'])->name('passenger.rides.cancel');
});

// ─── Driver ───────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'driver'])->group(function () {
    Route::get('/driver/dashboard', [DriverDashboardController::class, 'index'])
        ->name('driver.dashboard');
    Route::patch('/driver/availability', [DriverDashboardController::class, 'toggleAvailability'])
        ->name('driver.availability.toggle');
    Route::get('/driver/rides', [DriverRideController::class, 'index'])->name('driver.rides.index');
    Route::get('/driver/rides/{ride}', [DriverRideController::class, 'show'])->name('driver.rides.show');
    Route::patch('/driver/rides/{ride}/accept', [DriverRideController::class, 'accept'])->name('driver.rides.accept');
    Route::patch('/driver/rides/{ride}/status', [DriverRideController::class, 'updateStatus'])->name('driver.rides.status');
});

// ─── Breeze auth routes ───────────────────────────────────────────────────────
require __DIR__.'/auth.php';
