<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'start_date' => ['nullable', 'date_format:Y-m-d'],
            'end_date'   => ['nullable', 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'user_id'    => ['nullable', 'exists:users,id'],
            'status'     => ['nullable', 'string', 'in:all,completed,refunded,voided'],
            'search'     => ['nullable', 'string', 'max:100'],
        ]);

        // Default rentang tanggal: awal bulan s/d hari ini
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());
        $userId    = $request->input('user_id');
        $status    = $request->input('status', 'all');
        $search    = $request->input('search');

        // Query dasar transaksi dengan rentang tanggal
        $query = Transaction::with(['user', 'paymentMethod'])
            ->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);

        // Filter status transaksi
        if (!empty($status) && $status !== 'all') {
            $query->where('status', $status);
        }

        // Filter berdasarkan Kasir / Operator (Task 7 & 16)
        if (!empty($userId)) {
            $query->where('user_id', $userId);
        }

        // Filter berdasarkan Pencarian No Invoice atau Nama Kasir (Task 16)
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Hitung Agregasi Data berdasarkan seluruh data hasil filter
        $allFiltered = (clone $query)->get();

        $grossSales = $allFiltered->where('status', 'completed')->sum('total_amount');
        $totalRefund = $allFiltered->where('status', 'refunded')->sum('refund_amount');
        $netSales = max(0, $grossSales - $totalRefund);

        $totalCompleted = $allFiltered->where('status', 'completed')->count();
        $totalRefundCount = $allFiltered->where('status', 'refunded')->count();
        $totalVoidCount = $allFiltered->where('status', 'voided')->count();
        $totalTransactions = $allFiltered->count();

        // Rata-rata transaksi sukses
        $averageTransaction = $totalCompleted > 0 ? $grossSales / $totalCompleted : 0;

        // Data Grafik Penjualan Harian (Hanya transaksi valid / completed)
        $chartData = $allFiltered->where('status', 'completed')->groupBy(function ($item) {
            return Carbon::parse($item->created_at)->format('d M Y');
        })->map(function ($dayTransactions) {
            return $dayTransactions->sum('total_amount');
        });

        $chartLabels = $chartData->keys();
        $chartValues = $chartData->values();

        // Paginate hasil untuk tabel (Task 3)
        $transactions = $query->latest()->paginate(10)->withQueryString();

        // List kasir / user untuk dropdown filter (Task 7 & 16)
        $cashiers = User::orderBy('name')->get();

        // Variabel kompatibilitas $totalSales untuk view
        $totalSales = $netSales > 0 ? $netSales : $grossSales;

        return view('reports.index', compact(
            'transactions',
            'startDate',
            'endDate',
            'userId',
            'status',
            'search',
            'cashiers',
            'grossSales',
            'totalRefund',
            'netSales',
            'totalSales',
            'totalTransactions',
            'totalCompleted',
            'totalRefundCount',
            'totalVoidCount',
            'averageTransaction',
            'chartLabels',
            'chartValues'
        ));
    }

    /**
     * Laporan Laba dan Rugi (Profit & Loss Statement - Task 28)
     */
    public function profitLoss(Request $request)
    {
        $period = $request->input('period', 'this_month');

        if ($period === 'today') {
            $startDate = today()->toDateString();
            $endDate = today()->toDateString();
        } elseif ($period === 'this_week') {
            $startDate = now()->startOfWeek()->toDateString();
            $endDate = now()->endOfWeek()->toDateString();
        } elseif ($period === 'this_month') {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        } elseif ($period === 'custom') {
            $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
            $endDate = $request->input('end_date', now()->toDateString());
        } else {
            $startDate = now()->startOfMonth()->toDateString();
            $endDate = now()->endOfMonth()->toDateString();
        }

        $startDateTime = Carbon::parse($startDate)->startOfDay();
        $endDateTime = Carbon::parse($endDate)->endOfDay();

        // 1. Transaksi Penjualan Sukses
        $completedTransactions = Transaction::with(['items.product'])
            ->where('status', 'completed')
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();

        // 2. Transaksi Refund
        $refundedTransactions = Transaction::where('status', 'refunded')
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->get();

        // Komponen Pendapatan
        $grossProductSales = 0;
        $totalCogs = 0; // HPP (Harga Pokok Penjualan)
        foreach ($completedTransactions as $tx) {
            foreach ($tx->items as $item) {
                $grossProductSales += (float)$item->subtotal;
                $purchasePrice = $item->product ? (float)$item->product->purchase_price : 0;
                $totalCogs += $purchasePrice * $item->quantity;
            }
        }

        $totalDiscounts = $completedTransactions->sum('discount');
        $totalRefunds = $refundedTransactions->sum('refund_amount');
        $netSales = max(0, $grossProductSales - $totalDiscounts - $totalRefunds);

        // Laba Kotor (Gross Profit)
        $grossProfit = $netSales - $totalCogs;

        // Beban Operasional (Expenses - Task 27)
        $expenses = \App\Models\Expense::whereDate('expense_date', '>=', $startDate)
            ->whereDate('expense_date', '<=', $endDate)
            ->get();
        $totalExpenses = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('category')->map->sum('amount');

        // Pajak & Biaya Tambahan
        $totalTax = $completedTransactions->sum('tax');
        $totalServiceCharge = $completedTransactions->sum('service_charge');

        // Laba Bersih Usaha (Net Profit)
        $netProfit = $grossProfit - $totalExpenses;

        $totalCompletedCount = $completedTransactions->count();

        return view('reports.profit_loss', compact(
            'period',
            'startDate',
            'endDate',
            'grossProductSales',
            'totalDiscounts',
            'totalRefunds',
            'netSales',
            'totalCogs',
            'grossProfit',
            'expenses',
            'totalExpenses',
            'expensesByCategory',
            'totalTax',
            'totalServiceCharge',
            'netProfit',
            'totalCompletedCount'
        ));
    }
}
