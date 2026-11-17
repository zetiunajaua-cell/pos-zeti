<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            PaymentMethodSeeder::class,
            SettingAndExpenseSeeder::class,
            PromotionAndCustomerSeeder::class,
        ]);

        if (Product::count() === 0) {
            Product::factory(20)->create();
        }
    }
}
