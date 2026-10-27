<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));
        $stockFilter = $request->query('stock_filter', 'all');

        $query = Product::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        if ($stockFilter === 'low') {
            $query->where('stock', '<', 10)->where('stock', '>', 0);
        } elseif ($stockFilter === 'empty') {
            $query->where('stock', '<=', 0);
        } elseif ($stockFilter === 'safe') {
            $query->where('stock', '>=', 10);
        }

        $products = $query->orderBy('name')->paginate(12)->withQueryString();

        // Summary statistics
        $totalProducts = Product::count();
        $totalStockUnits = Product::sum('stock');
        $lowStockCount = Product::where('stock', '<', 10)->where('stock', '>', 0)->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        // All products for quick selection in stock in / adjustment modals
        $allProductsList = Product::orderBy('name')->get(['id', 'barcode', 'name', 'stock', 'price_buy', 'price_sell']);

        return view('inventory.index', compact(
            'products',
            'totalProducts',
            'totalStockUnits',
            'lowStockCount',
            'outOfStockCount',
            'allProductsList',
            'search',
            'stockFilter'
        ));
    }

    public function storeProduct(Request $request)
    {
        $validated = $request->validate([
            'barcode' => ['required', 'string', 'max:50', 'unique:products,barcode'],
            'name' => ['required', 'string', 'max:150'],
            'price_buy' => ['required', 'numeric', 'min:0'],
            'price_sell' => ['required', 'numeric', 'gte:price_buy'],
            'stock' => ['required', 'integer', 'min:0'],
        ], [
            'barcode.unique' => 'Barcode ini sudah digunakan oleh produk lain.',
            'price_sell.gte' => 'Harga jual tidak boleh lebih rendah dari harga modal (beli).',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::create($validated);

            // Record initial stock mutation if > 0
            if ($product->stock > 0) {
                StockMutation::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'in',
                    'qty' => $product->stock,
                    'notes' => 'Stok awal penambahan produk baru.',
                ]);
            }
        });

        return redirect()->route('inventory.index')->with('success', 'Produk baru berhasil ditambahkan.');
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $validated = $request->validate([
            'barcode' => ['required', 'string', 'max:50', Rule::unique('products', 'barcode')->ignore($product->id)],
            'name' => ['required', 'string', 'max:150'],
            'price_buy' => ['required', 'numeric', 'min:0'],
            'price_sell' => ['required', 'numeric', 'gte:price_buy'],
        ], [
            'barcode.unique' => 'Barcode ini sudah digunakan oleh produk lain.',
            'price_sell.gte' => 'Harga jual tidak boleh lebih rendah dari harga modal (beli).',
        ]);

        $product->update($validated);

        return redirect()->route('inventory.index')->with('success', "Data produk \"{$product->name}\" berhasil diperbarui.");
    }

    public function destroyProduct($id)
    {
        $product = Product::findOrFail($id);

        // Check if product is in any transaction detail
        if ($product->transactionDetails()->exists()) {
            return redirect()->route('inventory.index')->with('error', "Produk \"{$product->name}\" tidak dapat dihapus karena sudah memiliki riwayat transaksi penjualan kasir.");
        }

        $productName = $product->name;
        $product->delete();

        return redirect()->route('inventory.index')->with('success', "Produk \"{$productName}\" berhasil dihapus.");
    }

    public function stockIn(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['required', 'integer', 'min:1'],
            'notes' => ['nullable', 'string', 'max:255'],
        ], [
            'qty.min' => 'Jumlah barang masuk minimal 1.',
        ]);

        DB::transaction(function () use ($request) {
            /** @var Product $product */
            $product = Product::where('id', $request->input('product_id'))->lockForUpdate()->firstOrFail();

            $qty = (int) $request->input('qty');
            $notes = $request->input('notes') ? trim($request->input('notes')) : 'Barang masuk (Penerimaan stok)';

            $product->increment('stock', $qty);

            StockMutation::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'type' => 'in',
                'qty' => $qty,
                'notes' => $notes,
            ]);
        });

        return redirect()->route('inventory.index')->with('success', 'Stok barang masuk berhasil dicatat dan ditambahkan.');
    }

    public function stockAdjustment(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'qty' => ['required', 'integer', 'not_in:0'],
            'notes' => ['required', 'string', 'min:3', 'max:255'],
        ], [
            'qty.not_in' => 'Nilai koreksi stok tidak boleh bernilai 0.',
            'notes.required' => 'Alasan penyesuaian/koreksi stok wajib diisi.',
            'notes.min' => 'Alasan koreksi minimal 3 karakter.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                /** @var Product $product */
                $product = Product::where('id', $request->input('product_id'))->lockForUpdate()->firstOrFail();

                $delta = (int) $request->input('qty');
                $newStock = $product->stock + $delta;

                if ($newStock < 0) {
                    throw new Exception("Stok akhir tidak boleh bernilai minus! Stok saat ini: {$product->stock}, koreksi yang diinput: {$delta}.");
                }

                $product->stock = $newStock;
                $product->save();

                StockMutation::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                    'type' => 'adjustment',
                    'qty' => $delta,
                    'notes' => trim($request->input('notes')),
                ]);
            });

            return redirect()->route('inventory.index')->with('success', 'Penyesuaian stok berhasil disimpan.');
        } catch (Exception $e) {
            return redirect()->route('inventory.index')->with('error', $e->getMessage());
        }
    }

    public function mutations(Request $request)
    {
        $type = $request->query('type');
        $search = trim($request->query('search', ''));

        $query = StockMutation::with(['product', 'user']);

        if ($type && in_array($type, ['in', 'adjustment'])) {
            $query->where('type', $type);
        }

        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }

        $mutations = $query->latest()->paginate(15)->withQueryString();

        return view('inventory.mutations', compact('mutations', 'type', 'search'));
    }
}
