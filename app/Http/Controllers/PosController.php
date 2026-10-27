<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PosController extends Controller
{
    public function index()
    {
        // Recent 10 transactions by today for quick receipt access
        $recentTransactions = Transaction::with(['user', 'details.product'])
            ->whereDate('created_at', today())
            ->latest()
            ->take(8)
            ->get();

        $quickProducts = Product::where('stock', '>', 0)
            ->orderBy('name')
            ->take(12)
            ->get();

        return view('pos.index', compact('recentTransactions', 'quickProducts'));
    }

    public function findProduct(Request $request)
    {
        $barcode = trim($request->query('barcode', ''));

        if (!$barcode) {
            return response()->json([
                'success' => false,
                'message' => 'Barcode tidak boleh kosong.',
            ], 400);
        }

        $product = Product::where('barcode', $barcode)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => "Produk dengan barcode \"{$barcode}\" tidak ditemukan.",
            ], 404);
        }

        if ($product->stock <= 0) {
            return response()->json([
                'success' => false,
                'message' => "Stok untuk produk \"{$product->name}\" saat ini habis (0).",
                'product' => $product,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'product' => [
                'id' => $product->id,
                'barcode' => $product->barcode,
                'name' => $product->name,
                'price_sell' => (float) $product->price_sell,
                'stock' => (int) $product->stock,
            ],
        ]);
    }

    public function searchProducts(Request $request)
    {
        $query = trim($request->query('q', ''));

        $products = Product::where('stock', '>', 0)
            ->where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('barcode', 'like', "%{$query}%");
            })
            ->take(20)
            ->get(['id', 'barcode', 'name', 'price_sell', 'stock']);

        return response()->json($products);
    }

    public function checkout(Request $request)
    {
        $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.id' => ['required', 'integer', 'exists:products,id'],
            'items.*.qty' => ['required', 'integer', 'min:1'],
            'payment_amount' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $transaction = DB::transaction(function () use ($request) {
                $items = $request->input('items');
                $paymentAmount = (float) $request->input('payment_amount');
                $calculatedTotal = 0.0;
                $lineItems = [];

                // Lock and validate each product
                foreach ($items as $item) {
                    $qty = (int) $item['qty'];
                    
                    /** @var Product $product */
                    $product = Product::where('id', $item['id'])->lockForUpdate()->first();

                    if (!$product) {
                        throw new Exception("Produk ID {$item['id']} tidak ditemukan.");
                    }

                    if ($product->stock < $qty) {
                        throw new Exception("Stok untuk \"{$product->name}\" tidak mencukupi! Tersisa {$product->stock}, diminta {$qty}.");
                    }

                    $priceSell = (float) $product->price_sell;
                    $costPrice = (float) $product->price_buy;
                    $subtotal = $priceSell * $qty;
                    $calculatedTotal += $subtotal;

                    $lineItems[] = [
                        'product' => $product,
                        'qty' => $qty,
                        'price' => $priceSell,
                        'cost_price' => $costPrice,
                        'subtotal' => $subtotal,
                    ];
                }

                if ($paymentAmount < $calculatedTotal) {
                    throw new Exception("Jumlah pembayaran (Rp " . number_format($paymentAmount, 0, ',', '.') . ") kurang dari total tagihan (Rp " . number_format($calculatedTotal, 0, ',', '.') . ").");
                }

                $changeAmount = $paymentAmount - $calculatedTotal;

                // Generate Invoice Number: INV-YYYYMMDD-XXXX
                $datePrefix = date('Ymd');
                $randomSuffix = strtoupper(Str::random(4));
                $countToday = Transaction::whereDate('created_at', today())->count() + 1;
                $invoiceNumber = sprintf('INV-%s-%03d-%s', $datePrefix, $countToday, $randomSuffix);

                $transaction = Transaction::create([
                    'invoice_number' => $invoiceNumber,
                    'user_id' => Auth::id(),
                    'total_amount' => $calculatedTotal,
                    'payment_amount' => $paymentAmount,
                    'change_amount' => $changeAmount,
                ]);

                foreach ($lineItems as $line) {
                    TransactionDetail::create([
                        'transaction_id' => $transaction->id,
                        'product_id' => $line['product']->id,
                        'qty' => $line['qty'],
                        'price' => $line['price'],
                        'cost_price' => $line['cost_price'],
                        'subtotal' => $line['subtotal'],
                    ]);

                    // Atomically decrease stock
                    $line['product']->decrement('stock', $line['qty']);
                }

                return $transaction;
            });

            return response()->json([
                'success' => true,
                'message' => 'Transaksi penjualan berhasil diproses!',
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'print_url' => route('pos.print', $transaction->id),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    public function printReceipt($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);

        return view('pos.receipt', compact('transaction'));
    }
}
