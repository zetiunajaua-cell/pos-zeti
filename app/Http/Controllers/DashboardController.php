<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data Harian (Kasir & Admin)
        $todaySales = Transaction::whereDate('created_at', today())
            ->where('status', 'completed')
            ->sum('total_amount');

        $todayTransactions = Transaction::whereDate('created_at', today())
            ->where('status', 'completed')
            ->count();

        // Data Khusus Admin (Bulanan & Stok)
        $monthlySales = Transaction::whereMonth('created_at', now()->month)
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalProducts = Product::count();

        // 5 Produk Terlaris
        $topProducts = TransactionItem::select('product_id')
            ->selectRaw('SUM(quantity) as total_qty')
            ->whereMonth('created_at', now()->month)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'todaySales', 
            'todayTransactions', 
            'monthlySales', 
            'totalProducts', 
            'topProducts'
        ));
    }
}