<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View Composer untuk Notifikasi Stok Menipis pada Navbar & Topbar Admin (Task 24 & 43)
        View::composer(['layouts.navigation', 'layouts.topbar'], function ($view) {
            if (auth()->check() && optional(auth()->user()->role)->name === 'Admin') {
                try {
                    if (Schema::hasTable('products')) {
                        $navLowStockProducts = Product::where('is_active', true)
                            ->whereColumn('stock', '<=', 'min_stock')
                            ->orderBy('stock', 'asc')
                            ->take(5)
                            ->get();
                        $navLowStockCount = Product::where('is_active', true)
                            ->whereColumn('stock', '<=', 'min_stock')
                            ->count();
                        $view->with([
                            'navLowStockProducts' => $navLowStockProducts,
                            'navLowStockCount' => $navLowStockCount,
                        ]);
                    }
                } catch (\Throwable $e) {
                    // Silently fail during migrations/console
                }
            }
        });
    }
}
