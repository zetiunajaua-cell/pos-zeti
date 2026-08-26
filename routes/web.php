<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

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
        return 'Halaman Admin';
    })->name('admin');

    Route::resource('products', ProductController::class);

    Route::resource('categories', CategoryController::class);

});

/*
|--------------------------------------------------------------------------
| Route khusus Kasir
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:Kasir'])->group(function () {

    Route::resource('transactions', TransactionController::class);

});

require __DIR__.'/auth.php';