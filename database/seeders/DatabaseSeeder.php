<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Pengguna (Kasir, Gudang, Admin)
        $kasir = User::create([
            'name' => 'Siti Kasir (POS)',
            'email' => 'kasir@toko.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
        ]);

        $gudang = User::create([
            'name' => 'Budi Gudang (Logistik)',
            'email' => 'gudang@toko.com',
            'password' => Hash::make('password'),
            'role' => 'gudang',
        ]);

        $admin = User::create([
            'name' => 'Hendro Administrator',
            'email' => 'admin@toko.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. 10 Data Produk Retail
        $productsData = [
            [
                'barcode' => '8998866200225',
                'name' => 'Indomie Goreng Spesial 85g',
                'price_buy' => 2800,
                'price_sell' => 3500,
                'stock' => 120,
            ],
            [
                'barcode' => '8886008101053',
                'name' => 'Aqua Air Mineral 600ml',
                'price_buy' => 2500,
                'price_sell' => 3500,
                'stock' => 96,
            ],
            [
                'barcode' => '8992753211119',
                'name' => 'Ultra Milk Cokelat 250ml',
                'price_buy' => 5500,
                'price_sell' => 7000,
                'stock' => 50,
            ],
            [
                'barcode' => '8993077110123',
                'name' => 'Teh Botol Sosro Kotak 250ml',
                'price_buy' => 3200,
                'price_sell' => 4500,
                'stock' => 75,
            ],
            [
                'barcode' => '8991001100234',
                'name' => 'SilverQueen Almond 58g',
                'price_buy' => 13500,
                'price_sell' => 17500,
                'stock' => 35,
            ],
            [
                'barcode' => '8992696404456',
                'name' => 'Chitato Sapi Panggang 68g',
                'price_buy' => 9000,
                'price_sell' => 12000,
                'stock' => 40,
            ],
            [
                'barcode' => '8992775210014',
                'name' => 'Pocari Sweat 500ml',
                'price_buy' => 6500,
                'price_sell' => 8500,
                'stock' => 60,
            ],
            [
                'barcode' => '8999999052028',
                'name' => 'Pepsodent Fresh Cool 120g',
                'price_buy' => 11000,
                'price_sell' => 14500,
                'stock' => 30,
            ],
            [
                'barcode' => '8999999512349',
                'name' => 'Lifebuoy Sabun Cair 450ml',
                'price_buy' => 21000,
                'price_sell' => 27000,
                'stock' => 25,
            ],
            [
                'barcode' => '8999999710081',
                'name' => 'Rinso Molto Anti Noda 770g',
                'price_buy' => 24000,
                'price_sell' => 31000,
                'stock' => 8, // Stok menipis (< 10) untuk pengujian alert
            ],
        ];

        $createdProducts = [];
        foreach ($productsData as $data) {
            $prod = Product::create($data);
            $createdProducts[$prod->barcode] = $prod;

            // Catat mutasi stok masuk awal oleh gudang
            StockMutation::create([
                'product_id' => $prod->id,
                'user_id' => $gudang->id,
                'type' => 'in',
                'qty' => $prod->stock,
                'notes' => 'Penerimaan stok awal dari Distributor Utama',
                'created_at' => Carbon::now()->subDays(2),
            ]);
        }

        // Catat satu koreksi stok contoh
        StockMutation::create([
            'product_id' => $createdProducts['8999999710081']->id,
            'user_id' => $gudang->id,
            'type' => 'adjustment',
            'qty' => -2,
            'notes' => 'Koreksi stok fisik: 2 bungkus kemasan bocor saat handling',
            'created_at' => Carbon::now()->subDay(),
        ]);

        // 3. Transaksi Contoh (Agar Admin Dashboard & Laporan langsung terisi data realistis)
        // Transaksi 1 (Hari ini)
        $tx1 = Transaction::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-001-A89K',
            'user_id' => $kasir->id,
            'total_amount' => 45000,
            'payment_amount' => 50000,
            'change_amount' => 5000,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx1->id,
            'product_id' => $createdProducts['8998866200225']->id, // Indomie
            'qty' => 5,
            'price' => 3500,
            'cost_price' => 2800,
            'subtotal' => 17500,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx1->id,
            'product_id' => $createdProducts['8991001100234']->id, // SilverQueen
            'qty' => 1,
            'price' => 17500,
            'cost_price' => 13500,
            'subtotal' => 17500,
            'created_at' => Carbon::now()->subHours(3),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx1->id,
            'product_id' => $createdProducts['8992696404456']->id, // Chitato
            'qty' => 1,
            'price' => 12000,
            'cost_price' => 9000,
            'subtotal' => 12000, // 17500 + 17500 + 12000 = 47000? Wait, let's make total exact:
            'created_at' => Carbon::now()->subHours(3),
        ]);
        // Update total to match subtotal: 17500 + 17500 + 12000 = 47000
        $tx1->update(['total_amount' => 47000, 'payment_amount' => 50000, 'change_amount' => 3000]);

        // Transaksi 2 (Hari ini)
        $tx2 = Transaction::create([
            'invoice_number' => 'INV-' . date('Ymd') . '-002-C73B',
            'user_id' => $kasir->id,
            'total_amount' => 24000,
            'payment_amount' => 50000,
            'change_amount' => 26000,
            'created_at' => Carbon::now()->subHours(1),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx2->id,
            'product_id' => $createdProducts['8886008101053']->id, // Aqua 2 botol
            'qty' => 2,
            'price' => 3500,
            'cost_price' => 2500,
            'subtotal' => 7000,
            'created_at' => Carbon::now()->subHours(1),
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx2->id,
            'product_id' => $createdProducts['8992775210014']->id, // Pocari 2 botol
            'qty' => 2,
            'price' => 8500,
            'cost_price' => 6500,
            'subtotal' => 17000,
            'created_at' => Carbon::now()->subHours(1),
        ]);

        // Transaksi 3 (Kemarin)
        $yesterday = Carbon::yesterday()->addHours(14);
        $tx3 = Transaction::create([
            'invoice_number' => 'INV-' . Carbon::yesterday()->format('Ymd') . '-001-K99X',
            'user_id' => $kasir->id,
            'total_amount' => 58000,
            'payment_amount' => 100000,
            'change_amount' => 42000,
            'created_at' => $yesterday,
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx3->id,
            'product_id' => $createdProducts['8999999512349']->id, // Lifebuoy
            'qty' => 1,
            'price' => 27000,
            'cost_price' => 21000,
            'subtotal' => 27000,
            'created_at' => $yesterday,
        ]);

        TransactionDetail::create([
            'transaction_id' => $tx3->id,
            'product_id' => $createdProducts['8999999710081']->id, // Rinso
            'qty' => 1,
            'price' => 31000,
            'cost_price' => 24000,
            'subtotal' => 31000,
            'created_at' => $yesterday,
        ]);
    }
}
