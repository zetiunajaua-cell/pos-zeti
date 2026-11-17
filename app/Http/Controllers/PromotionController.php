<?php

namespace App\Http\Controllers;

use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    /**
     * Tampilkan daftar promo & diskon.
     */
    public function index(Request $request)
    {
        $query = Promotion::withCount('transactions')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $promotions = $query->paginate(10)->withQueryString();

        $totalPromos = Promotion::count();
        $activePromos = Promotion::where('is_active', true)->count();
        $totalUsage = \App\Models\Transaction::whereNotNull('promotion_id')->count();

        return view('promotions.index', compact('promotions', 'totalPromos', 'activePromos', 'totalUsage'));
    }

    /**
     * Form tambah promo baru.
     */
    public function create()
    {
        return view('promotions.create');
    }

    /**
     * Simpan promo baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:promotions,code',
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        Promotion::create($validated);

        return redirect()->route('promotions.index')
            ->with('success', "Promo '{$validated['code']}' berhasil ditambahkan.");
    }

    /**
     * Form edit promo.
     */
    public function edit(Promotion $promotion)
    {
        return view('promotions.edit', compact('promotion'));
    }

    /**
     * Perbarui data promo.
     */
    public function update(Request $request, Promotion $promotion)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'code' => 'required|string|max:50|unique:promotions,code,' . $promotion->id,
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|numeric|min:0',
            'min_purchase' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['code'] = strtoupper(trim($validated['code']));
        $validated['min_purchase'] = $validated['min_purchase'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active');

        $promotion->update($validated);

        return redirect()->route('promotions.index')
            ->with('success', "Promo '{$promotion->code}' berhasil diperbarui.");
    }

    /**
     * Hapus data promo.
     */
    public function destroy(Promotion $promotion)
    {
        $code = $promotion->code;
        $promotion->delete();

        return redirect()->route('promotions.index')
            ->with('success', "Promo '{$code}' berhasil dihapus.");
    }

    /**
     * Toggle status aktif / nonaktif promo.
     */
    public function toggleStatus(Promotion $promotion)
    {
        $promotion->update([
            'is_active' => !$promotion->is_active,
        ]);

        $statusText = $promotion->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Promo '{$promotion->code}' berhasil {$statusText}.");
    }
}

