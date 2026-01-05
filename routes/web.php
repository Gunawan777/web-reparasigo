<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TeknisiController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ServiceCategoryController;
use App\Http\Controllers\TeknisiListController;
use App\Http\Controllers\TechnicianServiceWebController; // Import new controller
use App\Http\Controllers\BookingController; // Import BookingController
use App\Http\Controllers\ReviewController; // Import ReviewController
use App\Http\Controllers\Admin\PaymentVerificationController;
use App\Http\Controllers\Admin\PayoutController;
use App\Http\Controllers\Admin\ReportController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');

// Technician Routes
Route::middleware(['auth', 'role:teknisi'])->prefix('teknisi')->group(function () {
    Route::get('/dashboard', [TeknisiController::class, 'index'])->name('teknisi.dashboard'); // Main technician dashboard
    Route::get('/services', [TechnicianServiceWebController::class, 'index'])->name('technician.services.index'); // Service management list
    Route::get('/services/create', [TechnicianServiceWebController::class, 'create'])->name('technician.services.create'); // Service creation form
    Route::get('/bookings/active', [TeknisiController::class, 'activeBookings'])->name('technician.bookings.active'); // Active bookings
    Route::get('/bookings/history', [TeknisiController::class, 'historyBookings'])->name('technician.bookings.history'); // Booking history
});


// Customer Routes
Route::middleware(['auth', 'role:pelanggan'])->prefix('customer')->group(function () {
    Route::get('/dashboard', [PelangganController::class, 'index'])->name('pelanggan.dashboard'); // Customer dashboard
    Route::get('/bookings', [PelangganController::class, 'bookings'])->name('customer.bookings.index'); // Customer booking list
});

Route::get('/layanan', [ServiceCategoryController::class, 'index'])->name('layanan.index');

Route::get('/teknisi', [TeknisiListController::class, 'index'])->name('teknisi.index');
Route::get('/teknisi/{id}', [TeknisiListController::class, 'show'])->name('teknisi.show');

Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store')->middleware(['auth', 'role:pelanggan']);
Route::post('/bookings/{booking}/accept', [BookingController::class, 'accept'])->name('bookings.accept')->middleware(['auth', 'role:teknisi']);
Route::post('/bookings/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject')->middleware(['auth', 'role:teknisi']);
Route::put('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus')->middleware(['auth', 'role:teknisi']);
Route::put('/bookings/{booking}/price-revision', [BookingController::class, 'requestPriceRevision'])->name('bookings.requestPriceRevision')->middleware(['auth', 'role:teknisi']);

// Routes for payment
Route::get('/bookings/{booking}/pay', [BookingController::class, 'showPaymentForm'])->name('bookings.pay.form')->middleware(['auth', 'role:pelanggan']);
Route::post('/bookings/{booking}/pay', [BookingController::class, 'processPayment'])->name('bookings.pay.process')->middleware(['auth', 'role:pelanggan']);

Route::post('/bookings/{booking}/confirm-completion', [BookingController::class, 'confirmCompletion'])->name('bookings.confirmCompletion')->middleware(['auth', 'role:pelanggan']);

Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware(['auth', 'role:pelanggan']);
// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Payment Verification
    Route::get('/payments', [PaymentVerificationController::class, 'index'])->name('payments.index');
    Route::post('/payments/{booking}/approve', [PaymentVerificationController::class, 'approve'])->name('payments.approve');
    Route::post('/payments/{booking}/reject', [PaymentVerificationController::class, 'reject'])->name('payments.reject');

    // Payouts
    Route::get('/payouts', [PayoutController::class, 'index'])->name('payouts.index');
    Route::post('/payouts/{technician}', [PayoutController::class, 'store'])->name('payouts.store');

    // Reports
    Route::get('/reports/commission', [ReportController::class, 'commissionReport'])->name('reports.commission');
});
