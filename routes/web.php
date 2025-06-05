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
use App\Http\Controllers\PostController;
use App\Http\Controllers\TourReviewController;
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

    // Post management routes
    Route::get('/profile/posts', [ProfileController::class, 'posts'])->name('profile.posts');
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');
    Route::post('/posts/{post}/like', [PostController::class, 'like'])->name('posts.like');
    Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::get('/tour/review/{booking}', [TourReviewController::class, 'showReviewForm'])->name('tour.review');
    Route::post('/tour/review/{booking}', [TourReviewController::class, 'submitReview'])->name('tour.submit-review');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
});

// Admin routes
Route::middleware(['auth', 'role:admin|guide'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Posts management
    Route::middleware(['check_permission:manage-posts'])->group(function () {
        Route::get('/posts', [App\Http\Controllers\Admin\PostController::class, 'index'])->name('posts.index');
        Route::get('/posts/{post}', [App\Http\Controllers\Admin\PostController::class, 'show'])->name('posts.show');
        Route::post('/posts/{post}/approve', [App\Http\Controllers\Admin\PostController::class, 'approve'])->name('posts.approve');
        Route::post('/posts/{post}/reject', [App\Http\Controllers\Admin\PostController::class, 'reject'])->name('posts.reject');
        Route::put('/posts/{post}/status', [App\Http\Controllers\Admin\PostController::class, 'updateStatus'])->name('posts.update-status');
    });

    // Users management
    Route::middleware(['check_permission:manage-users'])->group(function () {
        Route::resource('users', UserController::class);
    });

    // Roles management
    Route::middleware(['check_permission:manage-roles'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Permissions management
    Route::middleware(['check_permission:manage-permissions'])->group(function () {
        Route::resource('permissions', PermissionController::class);
    });

    // Tours management
    Route::get('/tours', [TourController::class, 'index'])->name('tours.index')->middleware(['check_permission:view tours']);
    Route::get('/tours/create', [TourController::class, 'create'])->name('tours.create')->middleware(['check_permission:manage-tours']);
    Route::post('/tours', [TourController::class, 'store'])->name('tours.store')->middleware(['check_permission:manage-tours']);
    Route::delete('/tours/delete-image/{image}', [TourController::class, 'deleteImage'])->name('tours.images.destroy')->middleware(['check_permission:manage-tours']);
    Route::get('/tours/{tour}/edit', [TourController::class, 'edit'])->name('tours.edit')->middleware(['check_permission:manage-tours']);
    Route::put('/tours/{tour}', [TourController::class, 'update'])->name('tours.update')->middleware(['check_permission:manage-tours']);
    Route::delete('/tours/{tour}', [TourController::class, 'destroy'])->name('tours.destroy')->middleware(['check_permission:manage-tours']);

    // Destinations management
    Route::get('/destinations', [DestinationController::class, 'index'])->name('destinations.index')->middleware(['check_permission:view destinations']);
    Route::get('/destinations/create', [DestinationController::class, 'create'])->name('destinations.create')->middleware(['check_permission:manage-destinations']);
    Route::post('/destinations', [DestinationController::class, 'store'])->name('destinations.store')->middleware(['check_permission:manage-destinations']);
    Route::get('/destinations/{destination}/edit', [DestinationController::class, 'edit'])->name('destinations.edit')->middleware(['check_permission:manage-destinations']);
    Route::put('/destinations/{destination}', [DestinationController::class, 'update'])->name('destinations.update')->middleware(['check_permission:manage-destinations']);
    Route::delete('/destinations/{destination}', [DestinationController::class, 'destroy'])->name('destinations.destroy')->middleware(['check_permission:manage-destinations']);
    Route::delete('/destinations/delete-image/{image}', [DestinationController::class, 'deleteImage'])->name('destinations.images.destroy')->middleware(['check_permission:manage-destinations']);
    // Booking routes
    Route::middleware(['check_permission:manage-bookings'])->group(function () {
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::get('/bookings/{booking}/confirm', [AdminBookingController::class, 'showConfirmForm'])->name('bookings.show-confirm');
        Route::post('/bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
        Route::post('/bookings/{booking}/cancel', [AdminBookingController::class, 'cancel'])->name('bookings.cancel');
        Route::post('/bookings/{booking}/update-status', [AdminBookingController::class, 'updateStatus'])->name('bookings.update-status');
        Route::post('/bookings/{booking}/update-payment-status', [AdminBookingController::class, 'updatePaymentStatus'])->name('bookings.update-payment-status');
        Route::post('/bookings/{booking}/assign-guide', [AdminBookingController::class, 'assignGuide'])->name('bookings.assign-guide');
    });

    // Tour Review Routes
    Route::middleware(['check_permission:manage-reviews'])->group(function () {
        Route::get('/reviews', [TourReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/toggle-visibility', [TourReviewController::class, 'toggleVisibility'])->name('reviews.toggle-visibility');
    });

    // Tour Departure Routes
    Route::get('/tour-departures', [TourDepartureController::class, 'index'])->name('tour-departures.index');
    Route::get('/tour-departures/create', [TourDepartureController::class, 'create'])->name('tour-departures.create');
    Route::post('/tour-departures', [TourDepartureController::class, 'store'])->name('tour-departures.store');
    Route::get('/tour-departures/{departure}/edit', [TourDepartureController::class, 'edit'])->name('tour-departures.edit')->middleware(['check_permission:assign-guide']);
    Route::put('/tour-departures/{departure}', [TourDepartureController::class, 'update'])->name('tour-departures.update')->middleware(['check_permission:assign-guide']);
    Route::delete('/tour-departures/{departure}', [TourDepartureController::class, 'destroy'])->name('tour-departures.destroy')->middleware(['check_permission:assign-guide']);
    Route::post('/tour-departures/{departure}/assign-guide', [TourDepartureController::class, 'assignGuide'])->name('tour-departures.assign-guide');
    Route::post('/tour-departures/{departure}/guide-confirm', [TourDepartureController::class, 'guideConfirm'])->name('tour-departures.guide-confirm');
    Route::post('/tour-departures/{departure}/guide-reject', [TourDepartureController::class, 'guideReject'])->name('tour-departures.guide-reject');
    Route::get('/tour-departures/{departure}/bookings', [TourDepartureController::class, 'showBookings'])->name('tour-departures.show-bookings');
    Route::post('/tour-departures/{departure}/send-tour-info', [TourDepartureController::class, 'sendTourInfo'])->name('tour-departures.send-tour-info');
    Route::post('/tour-departures/{departure}/complete-tour', [TourDepartureController::class, 'completeTour'])->name('tour-departures.complete-tour');
    Route::post('/tour-departures/{departure}/start-tour', [TourDepartureController::class, 'startTour'])->name('tour-departures.start-tour');
});



require __DIR__ . '/auth.php';
