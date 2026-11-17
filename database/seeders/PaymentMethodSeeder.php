<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Tunai (Cash)',
                'code' => 'cash',
                'type' => 'cash',
                'account_number' => null,
                'account_name' => null,
                'instructions' => 'Pembayaran langsung dengan uang tunai.',
                'is_active' => true,
            ],
            [
                'name' => 'QRIS',
                'code' => 'qris',
                'type' => 'qris',
                'account_number' => 'NMID-192837465',
                'account_name' => 'POS ZETI STORE',
                'instructions' => 'Pindai barcode QRIS melalui aplikasi e-wallet / m-banking pelanggan.',
                'is_active' => true,
            ],
            [
                'name' => 'Transfer Bank BCA',
                'code' => 'bca_transfer',
                'type' => 'bank_transfer',
                'account_number' => '8800123456',
                'account_name' => 'PT POS ZETI INDONESIA',
                'instructions' => 'Transfer antar bank ke rekening BCA perusahaan.',
                'is_active' => true,
            ],
            [
                'name' => 'E-Wallet GoPay / ShopeePay',
                'code' => 'ewallet',
                'type' => 'e_wallet',
                'account_number' => '081234567890',
                'account_name' => 'POS ZETI',
                'instructions' => 'Transfer ke nomor e-wallet toko.',
                'is_active' => true,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::updateOrCreate(
                ['code' => $method['code']],
                $method
            );
        }
    }
}

