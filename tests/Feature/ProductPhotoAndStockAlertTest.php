<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductPhotoAndStockAlertTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();

        $roleAdmin = Role::create(['name' => 'Admin']);
        $this->admin = User::factory()->create([
            'role_id' => $roleAdmin->id,
            'is_active' => true,
        ]);

        $this->category = Category::create([
            'name' => 'Minuman',
            'slug' => 'minuman',
        ]);
    }

    /**
     * Task 23: Unggah foto produk saat pembuatan produk.
     */
    public function test_admin_dapat_mengunggah_foto_produk_dan_menentukan_min_stock(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('kopi-susu.jpg', 600, 600);

        $response = $this->actingAs($this->admin)->post(route('products.store'), [
            'name' => 'Kopi Susu Gula Aren',
            'sku' => 'KOP-001',
            'category_id' => $this->category->id,
            'purchase_price' => 8000,
            'sell_price' => 15000,
            'stock' => 10,
            'min_stock' => 5,
            'unit' => 'Cup',
            'is_active' => 1,
            'image' => $file,
        ]);

        $response->assertRedirect(route('products.index'));

        $product = Product::where('sku', 'KOP-001')->first();
        $this->assertNotNull($product);
        $this->assertNotNull($product->image);
        $this->assertEquals(5, $product->min_stock);

        Storage::disk('public')->assertExists($product->image);
    }

    /**
     * Task 24: Notifikasi stok menipis saat stok <= min_stock.
     */
    public function test_sistem_mendeteksi_stok_menipis_dan_menampilkan_peringatan(): void
    {
        $productLow = Product::create([
            'category_id' => $this->category->id,
            'name' => 'Sirup Vanila',
            'sku' => 'SRP-001',
            'purchase_price' => 20000,
            'sell_price' => 35000,
            'stock' => 3,
            'min_stock' => 5,
            'unit' => 'Botol',
            'is_active' => true,
        ]);

        $this->assertTrue($productLow->isLowStock());
        $this->assertFalse($productLow->isOutOfStock());

        // Halaman dashboard admin harus menampilkan peringatan stok menipis
        $response = $this->actingAs($this->admin)->get(route('dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Peringatan Stok Menipis');
        $response->assertSee('Sirup Vanila');
    }
}
