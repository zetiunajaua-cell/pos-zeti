<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Tampilkan daftar pelanggan & member.
     */
    public function index(Request $request)
    {
        $query = Customer::withCount('transactions')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('tier')) {
            $query->where('membership_tier', $request->tier);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $customers = $query->paginate(10)->withQueryString();

        $totalCustomers = Customer::count();
        $silverCount = Customer::where('membership_tier', 'Silver')->count();
        $goldCount = Customer::where('membership_tier', 'Gold')->count();
        $platinumCount = Customer::where('membership_tier', 'Platinum')->count();
        $totalPoints = Customer::sum('points');

        return view('customers.index', compact(
            'customers',
            'totalCustomers',
            'silverCount',
            'goldCount',
            'platinumCount',
            'totalPoints'
        ));
    }

    /**
     * Form pendaftaran pelanggan/member baru.
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Simpan data pelanggan/member baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'member_code' => 'nullable|string|max:50|unique:customers,member_code',
            'membership_tier' => 'required|in:Silver,Gold,Platinum',
            'discount_percent' => 'nullable|numeric|min:0|max:100',
            'points' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        // Auto-generate member code jika dikosongkan
        if (empty($validated['member_code'])) {
            $count = Customer::count() + 1;
            $validated['member_code'] = 'MBR-' . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
            // Pastikan unik
            while (Customer::where('member_code', $validated['member_code'])->exists()) {
                $count++;
                $validated['member_code'] = 'MBR-' . str_pad((string)$count, 4, '0', STR_PAD_LEFT);
            }
        } else {
            $validated['member_code'] = strtoupper(trim($validated['member_code']));
        }

        // Set default diskon per tier jika kosong
        if (!isset($validated['discount_percent']) || $validated['discount_percent'] === '') {
            $tierDiscounts = [
                'Silver' => 5.00,
                'Gold' => 10.00,
                'Platinum' => 15.00,
            ];
            $validated['discount_percent'] = $tierDiscounts[$validated['membership_tier']] ?? 5.00;
        }

        $validated['points'] = $validated['points'] ?? 0;
        $validated['is_active'] = $request->boolean('is_active', true);

        $customer = Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', "Member '{$customer->name}' ({$customer->member_code}) berhasil didaftarkan.");
    }

    /**
     * Form edit pelanggan/member.
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Perbarui data pelanggan/member.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'phone' => 'nullable|string|max:25',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:500',
            'member_code' => 'required|string|max:50|unique:customers,member_code,' . $customer->id,
            'membership_tier' => 'required|in:Silver,Gold,Platinum',
            'discount_percent' => 'required|numeric|min:0|max:100',
            'points' => 'required|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['member_code'] = strtoupper(trim($validated['member_code']));
        $validated['is_active'] = $request->boolean('is_active');

        $customer->update($validated);

        return redirect()->route('customers.index')
            ->with('success', "Data member '{$customer->name}' berhasil diperbarui.");
    }

    /**
     * Hapus data pelanggan.
     */
    public function destroy(Customer $customer)
    {
        $name = $customer->name;
        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', "Pelanggan '{$name}' berhasil dihapus.");
    }
}

