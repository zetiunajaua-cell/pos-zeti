<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\CashierShift;
use App\Models\Transaction;
use Illuminate\Http\Request;

class CashierShiftController extends Controller
{
    /**
     * Membuka Shift Kasir Baru (Input Modal Awal Kas)
     */
    public function open(Request $request)
    {
        $request->validate([
            'starting_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'starting_cash.required' => 'Modal awal kas wajib diisi.',
            'starting_cash.min' => 'Modal awal kas tidak boleh negatif.',
        ]);

        $userId = auth()->id();

        // Pastikan tidak ada shift aktif ganda untuk kasir ini
        $activeShift = CashierShift::where('user_id', $userId)
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return back()->with('error', 'Anda sudah memiliki shift yang sedang aktif.');
        }

        $shift = CashierShift::create([
            'user_id' => $userId,
            'branch' => auth()->user()->branch ?? setting('store_name', 'Pusat'),
            'starting_cash' => (float)$request->starting_cash,
            'expected_cash' => (float)$request->starting_cash,
            'status' => 'open',
            'opened_at' => now(),
            'notes' => $request->notes,
        ]);

        ActivityLog::record(
            'SHIFT_OPEN',
            auth()->user()->name . ' membuka shift kasir dengan modal awal kas Rp ' . number_format($request->starting_cash, 0, ',', '.'),
            $shift,
            ['starting_cash' => $request->starting_cash]
        );

        return back()->with('success', 'Shift kasir berhasil dibuka dengan modal awal Rp ' . number_format($request->starting_cash, 0, ',', '.'));
    }

    /**
     * Menutup Shift Kasir & Rekonsiliasi Uang Kas (Z-Report)
     */
    public function close(Request $request, CashierShift $shift)
    {
        if ($shift->status !== 'open') {
            return back()->with('error', 'Shift ini sudah ditutup sebelumnya.');
        }

        $request->validate([
            'actual_cash' => ['required', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:500'],
        ], [
            'actual_cash.required' => 'Jumlah uang kas fisik di laci wajib diisi.',
            'actual_cash.min' => 'Jumlah uang kas fisik tidak boleh kurang dari 0.',
        ]);

        $summary = $shift->calculateSalesSummary();
        $actualCash = (float)$request->actual_cash;
        $expectedCash = (float)$summary['expected_cash'];
        $difference = $actualCash - $expectedCash;

        $shift->update([
            'actual_cash' => $actualCash,
            'expected_cash' => $expectedCash,
            'difference' => $difference,
            'total_cash_sales' => $summary['cash_sales'],
            'total_non_cash_sales' => $summary['non_cash_sales'],
            'total_transactions' => $summary['transactions_count'],
            'notes' => $request->notes,
            'status' => 'closed',
            'closed_at' => now(),
        ]);

        $diffText = $difference == 0
            ? 'Uang kas pas (tanpa selisih).'
            : ($difference > 0
                ? 'Selisih lebih: +Rp ' . number_format($difference, 0, ',', '.')
                : 'Selisih kurang: -Rp ' . number_format(abs($difference), 0, ',', '.'));

        ActivityLog::record(
            'SHIFT_CLOSE',
            auth()->user()->name . ' menutup shift kasir. ' . $diffText,
            $shift,
            [
                'actual_cash' => $actualCash,
                'expected_cash' => $expectedCash,
                'difference' => $difference,
                'total_transactions' => $summary['transactions_count'],
            ]
        );

        return redirect()->route('cashier-shifts.print', $shift)
            ->with('success', 'Shift kasir berhasil ditutup dan direkonsiliasi.');
    }

    /**
     * Cetak Struk Rekapitulasi Shift (Thermal Z-Report)
     */
    public function printReport(CashierShift $shift)
    {
        $shift->load('user');

        return view('cashier_shifts.print', compact('shift'));
    }
}
