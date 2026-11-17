<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan toko, pajak, dan tampilan.
     */
    public function index()
    {
        $settings = [
            'store_name' => setting('store_name', 'POS ZETI'),
            'store_slogan' => setting('store_slogan', 'Sistem Point of Sale & Retail Modern'),
            'store_address' => setting('store_address', 'Jl. Raya Utama No. 88, Kota Solusi'),
            'store_phone' => setting('store_phone', '0812-3456-7890'),
            'store_email' => setting('store_email', 'cs@poszeti.com'),
            'store_website' => setting('store_website', 'poszeti.com'),
            'store_logo' => setting('store_logo'),
            'receipt_header_msg' => setting('receipt_header_msg', 'Selamat Datang di POS ZETI! Selamat Berbelanja.'),
            'receipt_footer_msg' => setting('receipt_footer_msg', 'Semoga hari Anda menyenangkan. Silakan berkunjung kembali!'),
            
            // Pajak & Service Charge
            'tax_enabled' => setting('tax_enabled', '0'),
            'tax_name' => setting('tax_name', 'PPN 10%'),
            'tax_type' => setting('tax_type', 'percentage'),
            'tax_rate' => setting('tax_rate', '10'),

            'service_charge_enabled' => setting('service_charge_enabled', '0'),
            'service_charge_name' => setting('service_charge_name', 'Biaya Layanan'),
            'service_charge_type' => setting('service_charge_type', 'percentage'),
            'service_charge_rate' => setting('service_charge_rate', '5'),
        ];

        $cashiers = User::whereHas('role', function ($query) {
            $query->where('name', 'Kasir');
        })->withCount('transactions')->latest('id')->get();

        return view('settings.index', compact('settings', 'cashiers'));
    }

    /**
     * Simpan perubahan pengaturan toko.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            // Identitas Toko (Task 31)
            'store_name' => 'required|string|max:150',
            'store_slogan' => 'nullable|string|max:255',
            'store_address' => 'nullable|string|max:500',
            'store_phone' => 'nullable|string|max:30',
            'store_email' => 'nullable|email|max:100',
            'store_website' => 'nullable|string|max:100',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:10240',
            'remove_logo' => 'nullable|boolean',
            'receipt_header_msg' => 'nullable|string|max:255',
            'receipt_footer_msg' => 'nullable|string|max:500',

            // Pajak & Biaya Tambahan (Task 29)
            'tax_enabled' => 'nullable|boolean',
            'tax_name' => 'required|string|max:50',
            'tax_type' => 'required|in:percentage,fixed',
            'tax_rate' => 'required|numeric|min:0',

            'service_charge_enabled' => 'nullable|boolean',
            'service_charge_name' => 'required|string|max:50',
            'service_charge_type' => 'required|in:percentage,fixed',
            'service_charge_rate' => 'required|numeric|min:0',
        ], [
            'store_name.required' => 'Nama toko / bisnis wajib diisi.',
            'store_logo.image' => 'File logo toko harus berupa file gambar valid.',
            'store_logo.mimes' => 'Format file logo yang didukung: JPG, JPEG, PNG, WEBP, atau SVG.',
            'store_logo.max' => 'Ukuran file logo toko maksimal 10 MB (10.240 KB). Silakan pilih gambar berukuran lebih kecil.',
            'tax_name.required' => 'Nama pajak wajib diisi.',
            'tax_rate.required' => 'Tarif pajak wajib diisi.',
            'service_charge_name.required' => 'Nama biaya layanan wajib diisi.',
            'service_charge_rate.required' => 'Tarif biaya layanan wajib diisi.',
        ]);

        // Upload Logo baru jika ada
        if ($request->hasFile('store_logo')) {
            $oldLogo = setting('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('store_logo')->store('store', 'public');
            Setting::set('store_logo', $logoPath);
        } elseif ($request->boolean('remove_logo')) {
            $oldLogo = setting('store_logo');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }
            Setting::set('store_logo', null);
        }

        // Simpan setiap field pengaturan
        Setting::set('store_name', $validated['store_name']);
        Setting::set('store_slogan', $validated['store_slogan'] ?? '');
        Setting::set('store_address', $validated['store_address'] ?? '');
        Setting::set('store_phone', $validated['store_phone'] ?? '');
        Setting::set('store_email', $validated['store_email'] ?? '');
        Setting::set('store_website', $validated['store_website'] ?? '');
        Setting::set('receipt_header_msg', $validated['receipt_header_msg'] ?? '');
        Setting::set('receipt_footer_msg', $validated['receipt_footer_msg'] ?? '');

        Setting::set('tax_enabled', $request->boolean('tax_enabled') ? '1' : '0');
        Setting::set('tax_name', $validated['tax_name']);
        Setting::set('tax_type', $validated['tax_type']);
        Setting::set('tax_rate', (string) $validated['tax_rate']);

        Setting::set('service_charge_enabled', $request->boolean('service_charge_enabled') ? '1' : '0');
        Setting::set('service_charge_name', $validated['service_charge_name']);
        Setting::set('service_charge_type', $validated['service_charge_type']);
        Setting::set('service_charge_rate', (string) $validated['service_charge_rate']);

        ActivityLog::record(
            'UPDATE_SETTING',
            'Memperbarui konfigurasi identitas toko, pajak, dan biaya layanan.',
            null,
            ['store_name' => $validated['store_name']]
        );

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan toko dan biaya berhasil diperbarui.');
    }
}
