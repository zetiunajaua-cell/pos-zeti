<?php

namespace Database\Seeders;

use App\Models\Expense;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;

class SettingAndExpenseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultSettings = [
            'store_name' => 'POS ZETI',
            'store_slogan' => 'Sistem Point of Sale & Retail Modern',
            'store_address' => 'Jl. Raya Utama No. 88, Kota Solusi',
            'store_phone' => '0812-3456-7890',
            'store_email' => 'cs@poszeti.com',
            'store_website' => 'poszeti.com',
            'receipt_header_msg' => 'Selamat Datang di POS ZETI! Selamat Berbelanja.',
            'receipt_footer_msg' => 'Semoga hari Anda menyenangkan. Silakan berkunjung kembali!',
            'tax_enabled' => '0',
            'tax_name' => 'PPN 10%',
            'tax_type' => 'percentage',
            'tax_rate' => '10',
            'service_charge_enabled' => '0',
            'service_charge_name' => 'Biaya Layanan',
            'service_charge_type' => 'percentage',
            'service_charge_rate' => '5',
        ];

        foreach ($defaultSettings as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Contoh sample pengeluaran jika ada user admin
        $admin = User::first();
        if ($admin && Expense::count() === 0) {
            Expense::create([
                'expense_number' => 'EXP-' . date('Ymd') . '-0001',
                'user_id' => $admin->id,
                'category' => 'Pembelian Stok',
                'title' => 'Kulakan Biji Kopi dan Susu Segar',
                'amount' => 350000,
                'expense_date' => today(),
                'note' => 'Pembelian bahan baku mingguan dari supplier utama.',
            ]);

            Expense::create([
                'expense_number' => 'EXP-' . date('Ymd') . '-0002',
                'user_id' => $admin->id,
                'category' => 'Utilitas',
                'title' => 'Tagihan Listrik & Internet Toko',
                'amount' => 450000,
                'expense_date' => today(),
                'note' => 'Pembayaran listrik PLN dan WiFi Indihome bulan berjalan.',
            ]);
        }
    }
}
