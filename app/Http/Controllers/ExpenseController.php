<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExpenseController extends Controller
{
    /**
     * Tampilkan daftar pengeluaran operasional.
     */
    public function index(Request $request)
    {
        $query = Expense::with('user')->latest('expense_date');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('expense_number', 'like', "%{$search}%")
                  ->orWhere('note', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('expense_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('start_date')) {
            $query->where('expense_date', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->where('expense_date', '<=', $request->end_date);
        }

        $expenses = $query->paginate(10)->withQueryString();

        $totalExpenses = Expense::sum('amount');
        $thisMonthExpenses = Expense::whereMonth('expense_date', now()->month)
            ->whereYear('expense_date', now()->year)
            ->sum('amount');
        $todayExpenses = Expense::whereDate('expense_date', today())->sum('amount');

        $categories = [
            'Pembelian Stok',
            'Gaji Karyawan',
            'Utilitas',
            'Sewa Tempat',
            'Operasional Toko',
            'Lainnya',
        ];

        return view('expenses.index', compact(
            'expenses',
            'totalExpenses',
            'thisMonthExpenses',
            'todayExpenses',
            'categories'
        ));
    }

    /**
     * Form catat pengeluaran baru.
     */
    public function create()
    {
        $categories = [
            'Pembelian Stok',
            'Gaji Karyawan',
            'Utilitas',
            'Sewa Tempat',
            'Operasional Toko',
            'Lainnya',
        ];

        return view('expenses.create', compact('categories'));
    }

    /**
     * Simpan pengeluaran baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Generate nomor pengeluaran
        $prefix = 'EXP-' . date('Ymd') . '-';
        $latest = Expense::where('expense_number', 'like', $prefix . '%')
            ->orderByDesc('expense_number')
            ->first();
        $nextSeq = 1;
        if ($latest && preg_match('/-(\d+)$/', $latest->expense_number, $matches)) {
            $nextSeq = ((int) $matches[1]) + 1;
        }
        $expenseNumber = $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        while (Expense::where('expense_number', $expenseNumber)->exists()) {
            $nextSeq++;
            $expenseNumber = $prefix . str_pad($nextSeq, 4, '0', STR_PAD_LEFT);
        }

        $receiptPath = null;
        if ($request->hasFile('receipt_image')) {
            $receiptPath = $request->file('receipt_image')->store('expenses', 'public');
        }

        $expense = Expense::create([
            'expense_number' => $expenseNumber,
            'user_id' => auth()->id(),
            'category' => $validated['category'],
            'title' => $validated['title'],
            'amount' => $validated['amount'],
            'expense_date' => $validated['expense_date'],
            'note' => $validated['note'] ?? null,
            'receipt_image' => $receiptPath,
        ]);

        ActivityLog::record(
            'CREATE_EXPENSE',
            "Mencatat pengeluaran baru '{$expense->title}' sebesar Rp " . number_format($expense->amount, 0, ',', '.'),
            $expense,
            ['amount' => $expense->amount, 'category' => $expense->category]
        );

        return redirect()->route('expenses.index')
            ->with('success', "Pengeluaran '{$expense->expense_number}' berhasil dicatat.");
    }

    /**
     * Form edit pengeluaran.
     */
    public function edit(Expense $expense)
    {
        $categories = [
            'Pembelian Stok',
            'Gaji Karyawan',
            'Utilitas',
            'Sewa Tempat',
            'Operasional Toko',
            'Lainnya',
        ];

        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Perbarui data pengeluaran.
     */
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category' => 'required|string|max:100',
            'title' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'note' => 'nullable|string|max:1000',
            'receipt_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('receipt_image')) {
            if ($expense->receipt_image && Storage::disk('public')->exists($expense->receipt_image)) {
                Storage::disk('public')->delete($expense->receipt_image);
            }
            $validated['receipt_image'] = $request->file('receipt_image')->store('expenses', 'public');
        }

        $expense->update($validated);

        ActivityLog::record(
            'UPDATE_EXPENSE',
            "Memperbarui pengeluaran '{$expense->expense_number}': {$expense->title}",
            $expense
        );

        return redirect()->route('expenses.index')
            ->with('success', "Data pengeluaran '{$expense->expense_number}' berhasil diperbarui.");
    }

    /**
     * Hapus pengeluaran.
     */
    public function destroy(Expense $expense)
    {
        $title = $expense->title;
        $num = $expense->expense_number;

        if ($expense->receipt_image && Storage::disk('public')->exists($expense->receipt_image)) {
            Storage::disk('public')->delete($expense->receipt_image);
        }

        $expense->delete();

        ActivityLog::record(
            'DELETE_EXPENSE',
            "Menghapus data pengeluaran '{$num}': {$title}"
        );

        return redirect()->route('expenses.index')
            ->with('success', "Pengeluaran '{$num}' berhasil dihapus.");
    }
}

