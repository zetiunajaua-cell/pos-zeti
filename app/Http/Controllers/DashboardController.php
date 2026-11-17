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
        $user = auth()->user();
        $isAdmin = optional($user->role)->name === 'Admin';

        // Query Penjualan Hari Ini (Admin lihat seluruh toko, Kasir lihat shift dirinya)
        $todayQuery = Transaction::whereDate('created_at', today())
            ->where('status', 'completed');

        if (!$isAdmin) {
            $todayQuery->where('user_id', $user->id);
        }

        $todaySales = (clone $todayQuery)->sum('total_amount');
        $todayTransactions = (clone $todayQuery)->count();

        // Data Khusus Admin (Akumulasi Bulan & Tahun Berjalan)
        $monthlySales = Transaction::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', 'completed')
            ->sum('total_amount');

        $totalProducts = Product::count();

        // 5 Produk Terlaris Bulan Ini (Otomatis dari Transaksi Selesai)
        $topProducts = TransactionItem::select('product_id')
            ->selectRaw('SUM(quantity) as total_qty')
            ->whereHas('transaction', fn($q) => $q->where('status', 'completed'))
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->groupBy('product_id')
            ->orderByDesc('total_qty')
            ->with('product')
            ->take(5)
            ->get();

        // Data Grafik Penjualan 7 Hari Terakhir (Kontinu & Otomatis)
        $sevenDaysSalesQuery = Transaction::where('status', 'completed')
            ->whereDate('created_at', '>=', now()->subDays(6)->toDateString());

        if (!$isAdmin) {
            $sevenDaysSalesQuery->where('user_id', $user->id);
        }

        $sevenDaysSales = $sevenDaysSalesQuery->get()
            ->groupBy(fn($item) => $item->created_at->format('d M'))
            ->map(fn($group) => $group->sum('total_amount'));

        $salesChartLabels = [];
        $salesChartValues = [];
        for ($i = 6; $i >= 0; $i--) {
            $dateLabel = now()->subDays($i)->format('d M');
            $salesChartLabels[] = $dateLabel;
            $salesChartValues[] = (float) ($sevenDaysSales[$dateLabel] ?? 0);
        }

        // Peringatan Stok Menipis (Task 24)
        $lowStockAlertProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->orderBy('stock', 'asc')
            ->get();

        return view('dashboard', compact(
            'todaySales', 
            'todayTransactions', 
            'monthlySales', 
            'totalProducts', 
            'topProducts',
            'salesChartLabels',
            'salesChartValues',
            'lowStockAlertProducts'
        ));
    }
}
