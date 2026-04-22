<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\HotelRequestController as AdminHotelRequestController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\HomeController;

// Base Route
Route::get('/', [HomeController::class, 'index'])->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        $roleMap = [
            'admin' => 'admin.dashboard',
            'owner' => 'owner.dashboard',
            'receptionist' => 'receptionist.dashboard',
            'staff' => 'staff.dashboard',
            'cleaner' => 'cleaner.dashboard',
            'inspector' => 'inspector.dashboard',
            'guest' => 'guest.dashboard',
        ];

        $userRole = auth()->user()->role->slug;
        $dashboardRoute = $roleMap[$userRole] ?? 'admin.dashboard';

        return redirect()->route($dashboardRoute);
    })->name('dashboard');

    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        // User Management
        Route::get('/admin/users', [AdminUserController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{user}', [AdminUserController::class, 'show'])->name('admin.users.show');
        Route::post('/admin/users/{user}/ban', [AdminUserController::class, 'ban'])->name('admin.users.ban');
        Route::post('/admin/users/{user}/unban', [AdminUserController::class, 'unban'])->name('admin.users.unban');

        // Hotel Requests
        Route::get('/admin/hotel-requests', [AdminHotelRequestController::class, 'index'])->name('admin.hotel-requests.index');
        Route::get('/admin/hotel-requests/{hotelRequest}', [AdminHotelRequestController::class, 'show'])->name('admin.hotel-requests.show');
        Route::post('/admin/hotel-requests/{hotelRequest}/approve', [AdminHotelRequestController::class, 'approve'])->name('admin.hotel-requests.approve');
        Route::post('/admin/hotel-requests/{hotelRequest}/reject', [AdminHotelRequestController::class, 'reject'])->name('admin.hotel-requests.reject');

        // Hotels
        Route::get('/admin/hotels/{hotel}', [AdminHotelController::class, 'show'])->name('admin.hotels.show');
    });
    // Fallback redirect (Foundation)
    Route::fallback(function () {
        return redirect()->route('dashboard');
    });
});
