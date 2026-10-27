<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RetailPosTest extends TestCase
{
    public function test_login_page_loads_successfully()
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Retail POS');
    }

    public function test_kasir_login_redirects_to_pos()
    {
        $response = $this->post('/login', [
            'email' => 'kasir@toko.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/pos');
    }

    public function test_gudang_login_redirects_to_inventory()
    {
        $response = $this->post('/login', [
            'email' => 'gudang@toko.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/inventory');
    }

    public function test_admin_login_redirects_to_admin_dashboard()
    {
        $response = $this->post('/login', [
            'email' => 'admin@toko.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
    }

    public function test_role_middleware_blocks_unauthorized_access()
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        // Kasir cannot access inventory
        $response = $this->get('/inventory');
        $response->assertRedirect('/pos');

        // Kasir cannot access admin
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/pos');
    }

    public function test_pos_find_product_by_barcode()
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $response = $this->getJson('/pos/product/find?barcode=8998866200225');
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);
        $response->assertJsonPath('product.barcode', '8998866200225');
    }

    public function test_pos_checkout_creates_transaction_and_decrements_stock()
    {
        $kasir = User::where('role', 'kasir')->first();
        $this->actingAs($kasir);

        $product = Product::where('stock', '>', 5)->first();
        $initialStock = $product->stock;

        $payload = [
            'items' => [
                ['id' => $product->id, 'qty' => 2],
            ],
            'payment_amount' => 100000,
        ];

        $response = $this->postJson('/pos/checkout', $payload);
        $response->assertStatus(200);
        $response->assertJsonPath('success', true);

        // Verify stock decremented
        $product->refresh();
        $this->assertEquals($initialStock - 2, $product->stock);
    }

    public function test_inventory_stock_in_increases_stock_and_logs_mutation()
    {
        $gudang = User::where('role', 'gudang')->first();
        $this->actingAs($gudang);

        $product = Product::first();
        $initialStock = $product->stock;

        $response = $this->post('/inventory/stock-in', [
            'product_id' => $product->id,
            'qty' => 20,
            'notes' => 'Penerimaan tambahan dari pabrik',
        ]);

        $response->assertRedirect('/inventory');

        $product->refresh();
        $this->assertEquals($initialStock + 20, $product->stock);

        $this->assertDatabaseHas('stock_mutations', [
            'product_id' => $product->id,
            'type' => 'in',
            'qty' => 20,
        ]);
    }

    public function test_admin_dashboard_metrics_display()
    {
        $admin = User::where('role', 'admin')->first();
        $this->actingAs($admin);

        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Omzet Penjualan');
        $response->assertSee('Laba Bersih');
    }
}
