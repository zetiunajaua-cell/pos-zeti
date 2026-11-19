<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CashierShiftController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

// Halaman Utama / Landing Page (Welcome)
Route::get('/', function () {
    $totalProducts = 0;
    $activeProducts = 0;
    $totalCategories = 0;
    $totalStock = 0;

    if (\Illuminate\Support\Facades\Schema::hasTable('products')) {
        $totalProducts = \App\Models\Product::count();
        $activeProducts = \App\Models\Product::where('is_active', true)->count();
        $totalStock = (int) \App\Models\Product::sum('stock');
    }

    if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
        $totalCategories = \App\Models\Category::count();
    }

    return view('welcome', compact('totalProducts', 'activeProducts', 'totalCategories', 'totalStock'));
})->name('welcome');

/*
|--------------------------------------------------------------------------
| Route yang bisa diakses SEMUA User Terautentikasi (Admin & Kasir)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Route Laporan disini agar Kasir dan Admin bisa akses
    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');

    // Task 28: Laporan Laba dan Rugi (Admin & Kasir)
    Route::get('/reports/profit-loss', [ReportController::class, 'profitLoss'])
        ->name('reports.profit_loss');

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');

});

/*
|--------------------------------------------------------------------------
| Route khusus Admin
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Admin'])->group(function () {

    Route::get('/admin', function () {
        return redirect()->route('dashboard');
    })->name('admin');

    Route::resource('products', ProductController::class);

    Route::resource('categories', CategoryController::class);

    Route::patch('payment-methods/{payment_method}/toggle', [PaymentMethodController::class, 'toggleStatus'])
        ->name('payment-methods.toggle');
    Route::resource('payment-methods', PaymentMethodController::class);

    // Task 25: Fitur Diskon & Promo
    Route::patch('promotions/{promotion}/toggle', [PromotionController::class, 'toggleStatus'])
        ->name('promotions.toggle');
    Route::resource('promotions', PromotionController::class);

    // Task 26: Fitur Membership Pelanggan
    Route::resource('customers', CustomerController::class);

    // Task 27: Pengeluaran Operasional (Expenses)
    Route::resource('expenses', ExpenseController::class);

    // Task 29 & 31: Pengaturan Toko, Pajak & Biaya Tambahan
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Pengelolaan Akun Kasir & Multi-Toko
    Route::patch('cashiers/{cashier}/toggle', [\App\Http\Controllers\CashierController::class, 'toggleStatus'])
        ->name('cashiers.toggle');
    Route::resource('cashiers', \App\Http\Controllers\CashierController::class);

    // Task 32: Riwayat Aktivitas Pengguna (Activity Log / Audit Trail)
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

});

/*
|--------------------------------------------------------------------------
| Route Khusus Kasir (Terminal Kasir POS & Checkout)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Kasir'])->group(function () {
    Route::resource('transactions', TransactionController::class)->only(['index', 'create', 'store']);
});

/*
|--------------------------------------------------------------------------
| Route Bersama Kasir & Admin (Lihat Struk, Cetak PDF, Void, Refund)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Kasir,Admin'])->group(function () {
    Route::get('/transactions/{transaction}', [TransactionController::class, 'show'])
        ->name('transactions.show');
    Route::get('/transactions/{transaction}/pdf', [TransactionController::class, 'pdf'])
        ->name('transactions.pdf');
    Route::get('/transactions/{transaction}/kitchen', [TransactionController::class, 'kitchenTicket'])
        ->name('transactions.kitchen');
    Route::post('/transactions/{transaction}/void', [TransactionController::class, 'void'])
        ->name('transactions.void');
    Route::post('/transactions/{transaction}/refund', [TransactionController::class, 'refund'])
        ->name('transactions.refund');

    // Manajemen Shift Kasir (Buka & Tutup Kasir / Kontrol Kas Laci)
    Route::post('/cashier-shifts/open', [CashierShiftController::class, 'open'])
        ->name('cashier-shifts.open');
    Route::post('/cashier-shifts/{shift}/close', [CashierShiftController::class, 'close'])
        ->name('cashier-shifts.close');
    Route::get('/cashier-shifts/{shift}/print', [CashierShiftController::class, 'printReport'])
        ->name('cashier-shifts.print');
});

require __DIR__.'/auth.php';