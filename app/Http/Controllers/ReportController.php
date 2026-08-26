<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Default rentang tanggal: awal bulan s/d hari ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->toDateString());

        // Ambil transaksi completed
        $transactions = Transaction::with(['user'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ])
            ->latest()
            ->get();

        // Hitung Agregasi Data
        $totalSales = $transactions->sum('total_amount');
        $totalTransactions = $transactions->count();
        
        // Hitung Rata-rata Transaksi (Average Basket Size)
        $averageTransaction = $totalTransactions > 0 ? $totalSales / $totalTransactions : 0;

        // Data Grafik Penjualan Harian
        $chartData = $transactions->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('d M Y');
        })->map(function ($dayTransactions) {
            return $dayTransactions->sum('total_amount');
        });

        $chartLabels = $chartData->keys();
        $chartValues = $chartData->values();

        // Pastikan $averageTransaction terkirim ke view melalui compact()
        return view('reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'totalSales',
            'totalTransactions',
            'averageTransaction',
            'chartLabels',
            'chartValues'
        ));
    }
}