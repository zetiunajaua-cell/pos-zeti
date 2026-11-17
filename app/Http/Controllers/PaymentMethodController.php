<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paymentMethods = PaymentMethod::withCount('transactions')->latest()->get();

        return view('payment_methods.index', compact('paymentMethods'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:cash,bank_transfer,qris,e_wallet'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $code = Str::slug($request->name, '_');
        $originalCode = $code;
        $counter = 1;
        while (PaymentMethod::where('code', $code)->exists()) {
            $code = $originalCode . '_' . $counter++;
        }

        PaymentMethod::create([
            'name' => $validated['name'],
            'code' => $code,
            'type' => $validated['type'],
            'account_number' => $validated['account_number'] ?? null,
            'account_name' => $validated['account_name'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Metode pembayaran baru berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PaymentMethod $paymentMethod)
    {
        return view('payment_methods.edit', compact('paymentMethod'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'type' => ['required', 'string', 'in:cash,bank_transfer,qris,e_wallet'],
            'account_number' => ['nullable', 'string', 'max:100'],
            'account_name' => ['nullable', 'string', 'max:100'],
            'instructions' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $paymentMethod->update([
            'name' => $validated['name'],
            'type' => $validated['type'],
            'account_number' => $validated['account_number'] ?? null,
            'account_name' => $validated['account_name'] ?? null,
            'instructions' => $validated['instructions'] ?? null,
            'is_active' => $request->has('is_active') ? true : false,
        ]);

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PaymentMethod $paymentMethod)
    {
        if ($paymentMethod->transactions()->count() > 0) {
            return redirect()
                ->route('payment-methods.index')
                ->with('error', 'Metode pembayaran tidak dapat dihapus karena sudah memiliki riwayat transaksi. Anda dapat menonaktifkannya.');
        }

        $paymentMethod->delete();

        return redirect()
            ->route('payment-methods.index')
            ->with('success', 'Metode pembayaran berhasil dihapus.');
    }

    /**
     * Toggle status aktif/nonaktif
     */
    public function toggleStatus(PaymentMethod $paymentMethod)
    {
        $paymentMethod->update([
            'is_active' => !$paymentMethod->is_active,
        ]);

        $status = $paymentMethod->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Metode pembayaran {$paymentMethod->name} berhasil {$status}.");
    }
}

