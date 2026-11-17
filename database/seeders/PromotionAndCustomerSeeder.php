<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionAndCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample Promotions
        $promotions = [
            [
                'name' => 'Promo Weekend 10%',
                'code' => 'WEEKEND10',
                'type' => 'percentage',
                'value' => 10,
                'min_purchase' => 30000,
                'max_discount' => 20000,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
                'is_active' => true,
            ],
            [
                'name' => 'Potongan Belanja Rp 10.000',
                'code' => 'HEMAT10K',
                'type' => 'fixed',
                'value' => 10000,
                'min_purchase' => 50000,
                'max_discount' => 10000,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
                'is_active' => true,
            ],
            [
                'name' => 'Diskon Pelanggan Baru 15%',
                'code' => 'NEWUSER15',
                'type' => 'percentage',
                'value' => 15,
                'min_purchase' => 25000,
                'max_discount' => 30000,
                'start_date' => now()->startOfYear(),
                'end_date' => now()->endOfYear(),
                'is_active' => true,
            ],
        ];

        foreach ($promotions as $promo) {
            Promotion::updateOrCreate(['code' => $promo['code']], $promo);
        }

        // Sample Customers / Members
        $customers = [
            [
                'member_code' => 'MBR-0001',
                'name' => 'Budi Santoso',
                'phone' => '081234567890',
                'email' => 'budi.santoso@example.com',
                'address' => 'Jl. Mawar No. 12, Jakarta',
                'membership_tier' => 'Gold',
                'discount_percent' => 10.00,
                'points' => 180,
                'is_active' => true,
            ],
            [
                'member_code' => 'MBR-0002',
                'name' => 'Siti Aminah',
                'phone' => '082198765432',
                'email' => 'siti.aminah@example.com',
                'address' => 'Jl. Melati No. 45, Bandung',
                'membership_tier' => 'Silver',
                'discount_percent' => 5.00,
                'points' => 60,
                'is_active' => true,
            ],
            [
                'member_code' => 'MBR-0003',
                'name' => 'Ahmad Fauzi',
                'phone' => '085712345678',
                'email' => 'ahmad.fauzi@example.com',
                'address' => 'Jl. Kenanga No. 8, Surabaya',
                'membership_tier' => 'Platinum',
                'discount_percent' => 15.00,
                'points' => 450,
                'is_active' => true,
            ],
        ];

        foreach ($customers as $cust) {
            Customer::updateOrCreate(['member_code' => $cust['member_code']], $cust);
        }
    }
}

