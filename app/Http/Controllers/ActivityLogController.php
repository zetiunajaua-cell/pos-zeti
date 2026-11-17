<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Tampilkan riwayat aktivitas pengguna (Audit Trail).
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user.role'])->latest('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Endpoint JSON feed untuk pembaruan real-time di UI
        if ($request->wantsJson() || $request->query('feed') == '1') {
            $feedLogs = $query->take(30)->get()->map(fn($log) => $this->formatLogItem($log));

            return response()->json([
                'status' => 'success',
                'logs' => $feedLogs,
                'latest_id' => $feedLogs->first()['id'] ?? 0,
                'total_count' => ActivityLog::count(),
                'synced_at' => now()->format('H:i:s'),
            ]);
        }

        $logs = $query->paginate(15)->withQueryString();

        $initialItems = $logs->getCollection()->map(fn($log) => $this->formatLogItem($log))->values();

        $users = User::with('role')->orderBy('name')->get();

        $actionTypes = [
            'LOGIN' => 'Masuk Sistem (Login)',
            'LOGOUT' => 'Keluar Sistem (Logout)',
            'ACCESS_TERMINAL' => 'Akses Terminal Kasir (POS)',
            'CHECKOUT' => 'Transaksi Penjualan',
            'VOID_TRANSACTION' => 'Pembatalan (Void)',
            'REFUND_TRANSACTION' => 'Pengembalian Dana (Refund)',
            'CREATE_PRODUCT' => 'Tambah Produk',
            'UPDATE_PRODUCT' => 'Ubah Produk',
            'DELETE_PRODUCT' => 'Hapus Produk',
            'CREATE_EXPENSE' => 'Catat Pengeluaran',
            'UPDATE_EXPENSE' => 'Ubah Pengeluaran',
            'DELETE_EXPENSE' => 'Hapus Pengeluaran',
            'CREATE_CASHIER' => 'Tambah Akun Kasir',
            'UPDATE_CASHIER' => 'Ubah Akun Kasir',
            'DELETE_CASHIER' => 'Hapus Akun Kasir',
            'UPDATE_SETTING' => 'Pengaturan Toko & Pajak',
        ];

        return view('activity_logs.index', compact('logs', 'users', 'actionTypes', 'initialItems'));
    }

    /**
     * Format entri activity log untuk representasi seragam di UI & JSON.
     */
    private function formatLogItem(ActivityLog $log): array
    {
        $badgeClass = match ($log->action) {
            'CHECKOUT' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800/40',
            'ACCESS_TERMINAL' => 'bg-teal-100 text-teal-800 dark:bg-teal-900/50 dark:text-teal-300 border border-teal-200 dark:border-teal-800/40',
            'VOID_TRANSACTION', 'DELETE_PRODUCT', 'DELETE_EXPENSE', 'DELETE_CASHIER' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/50 dark:text-rose-300 border border-rose-200 dark:border-rose-800/40',
            'REFUND_TRANSACTION' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300 border border-amber-200 dark:border-amber-800/40',
            'CREATE_PRODUCT', 'CREATE_EXPENSE', 'CREATE_CASHIER' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800/40',
            'UPDATE_PRODUCT', 'UPDATE_EXPENSE', 'UPDATE_SETTING', 'UPDATE_CASHIER' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800/40',
            'LOGIN', 'LOGOUT' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800/40',
            default => 'bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600',
        };

        return [
            'id' => $log->id,
            'created_at_date' => $log->created_at ? $log->created_at->format('d M Y') : '-',
            'created_at_time' => $log->created_at ? $log->created_at->format('H:i:s') : '-',
            'diff_for_humans' => $log->created_at ? $log->created_at->diffForHumans() : '-',
            'user_id' => $log->user_id,
            'user_name' => $log->user?->name ?? 'Tamu / Sistem',
            'user_role' => $log->user?->role?->name ?? 'Kasir',
            'user_initials' => strtoupper(substr($log->user?->name ?? '?', 0, 2)),
            'action' => $log->action,
            'badge_class' => $badgeClass,
            'description' => $log->description,
            'properties' => $log->properties,
            'properties_json' => !empty($log->properties) ? json_encode($log->properties, JSON_UNESCAPED_UNICODE) : null,
            'ip_address' => $log->ip_address ?? '127.0.0.1',
            'user_agent' => $log->user_agent,
        ];
    }
}

