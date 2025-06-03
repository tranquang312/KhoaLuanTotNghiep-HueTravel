<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DestinationController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\TourController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TourController as ViewTourController;
use App\Http\Controllers\DestinationController as ViewDestinationController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\TourDepartureController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tour', [ViewTourController::class, 'index'])->name('tours.index');
Route::get('/tour/{tour}', [ViewTourController::class, 'show'])->name('tours.show');
Route::get('/destination', [ViewDestinationController::class, 'index'])->name('destinations.index');
Route::get('/destination/{destination}', [ViewDestinationController::class, 'show'])->name('destinations.show');
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::get('/payment/{booking}', [PaymentController::class, 'show'])->name('payment.show');
Route::post('/payment/{booking}/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
Route::get('/payment/{booking}/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/{booking}/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');


Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Route::middleware(['auth', 'verified', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
//     Route::get('/admin/destinations', [DestinationController::class, 'index'])->name('admin.destinations.index');
//     Route::get('/admin/destinations/create', [DestinationController::class, 'create'])->name('admin.destinations.create');
//     Route::post('/admin/destinations', [DestinationController::class, 'store'])->name('admin.destinations.store');
//     Route::get('/admin/destinations/{destination}/edit', [DestinationController::class, 'edit'])->name('admin.destinations.edit');
//     Route::put('/admin/destinations/{destination}', [DestinationController::class, 'update'])->name('admin.destinations.update');
//     Route::delete('/admin/destinations/{destination}', [DestinationController::class, 'destroy'])->name('admin.destinations.destroy');

//     Route::get('/admin/tours', [TourController::class, 'index'])->name('admin.tours.index');
//     Route::get('/admin/tours/create', [TourController::class, 'create'])->name('admin.tours.create');
//     Route::post('/admin/tours', [TourController::class, 'store'])->name('admin.tours.store');
//     Route::get('/admin/tours/{tour}/edit', [TourController::class, 'edit'])->name('admin.tours.edit');
//     Route::put('/admin/tours/{tour}', [TourController::class, 'update'])->name('admin.tours.update');
//     Route::delete('/admin/tours/{tour}', [TourController::class, 'destroy'])->name('admin.tours.destroy');

//     Route::get('/admin/roles', [RoleController::class, 'index'])->name('admin.roles.index');
//     Route::get('/admin/roles/create', [RoleController::class, 'create'])->name('admin.roles.create');
//     Route::post('/admin/roles', [RoleController::class, 'store'])->name('admin.roles.store');
//     Route::get('/admin/roles/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
//     Route::put('/admin/roles/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
//     Route::delete('/admin/roles/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');

//     Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
//     Route::get('/admin/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
//     Route::post('/admin/permissions', [PermissionController::class, 'store'])->name('admin.permissions.store');
//     Route::get('/admin/permissions/{permission}/edit', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
//     Route::put('/admin/permissions/{permission}', [PermissionController::class, 'update'])->name('admin.permissions.update');
//     Route::delete('/admin/permissions/{permission}', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');

//     Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
//     Route::get('/admin/users/create', [UserController::class, 'create'])->name('admin.users.create');
//     Route::post('/admin/users', [UserController::class, 'store'])->name('admin.users.store');
//     Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
//     Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
//     Route::delete('/admin/users/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
// });


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-tours', [ProfileController::class, 'myTours'])->name('profile.my-tours');
    Route::put('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');
});

// Admin routes
Route::middleware(['auth', 'check_permission:access-admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users management
    Route::middleware(['check_permission:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles management
    Route::middleware([])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Permissions management
    Route::middleware([])->group(function () {
        Route::resource('permissions', PermissionController::class);
    });

    // Tours management
    Route::middleware(['check_permission:manage-tours'])->group(function () {
        Route::resource('tours', TourController::class);
    });

    // Destinations management
    Route::middleware(['check_permission:manage-destinations'])->group(function () {
        Route::resource('destinations', DestinationController::class);
    });

    // Bookings management
    Route::middleware(['check_permission:manage-bookings'])->group(function () {
        // Quản lý chuyến đi sắp khởi hành - Đặt route này trước các route có tham số
        Route::get('bookings/upcoming', [AdminBookingController::class, 'upcoming'])->name('bookings.upcoming');
        Route::get('bookings/tour/{tour}/{date}', [AdminBookingController::class, 'tourDetails'])->name('bookings.tour-details');
        Route::get('bookings/active', [AdminBookingController::class, 'active'])->name('bookings.active');
        Route::patch('bookings/{booking}/mark-completed', [AdminBookingController::class, 'markCompleted'])->name('bookings.mark-departed');

        // Các route quản lý booking khác
        Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::post('bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::post('bookings/{booking}/payment', [AdminBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment');
        Route::get('bookings/{booking}/assign-guide', [AdminBookingController::class, 'assignGuide'])->name('bookings.assign-guide');
        Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('bookings/{tour}/{date}/assign-guide', [AdminBookingController::class, 'assignGuideByDate'])->name('bookings.assign-guide-by-date');
    });

    // Tour departures management
    Route::middleware(['check_permission:manage-tour-departures'])->group(function () {
        Route::resource('tour-departures', TourDepartureController::class);
    });
});
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');

require __DIR__ . '/auth.php';
