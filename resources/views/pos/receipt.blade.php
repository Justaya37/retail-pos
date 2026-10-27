<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pembayaran - {{ $transaction->invoice_number }}</title>

    <!-- JetBrains Mono / Courier for Thermal Receipt look -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Tailwind CDN for screen presentation -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'JetBrains Mono', monospace;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .receipt-container {
                box-shadow: none !important;
                border: none !important;
                width: 100% !important;
                max-width: 80mm !important;
                padding: 0 !important;
                margin: 0 auto !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100 min-h-screen py-8 flex flex-col items-center">

    <!-- Action Toolbar (Hidden during print) -->
    <div class="no-print mb-6 flex items-center space-x-3">
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-md flex items-center transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Struk (Ctrl + P)
        </button>
        <a href="{{ route('pos.index') }}" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold shadow-md flex items-center transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Kasir
        </a>
    </div>

    <!-- Thermal Paper Box (80mm width standard) -->
    <div class="receipt-container bg-white w-[80mm] p-4 text-xs text-slate-900 shadow-xl border border-slate-200 rounded-lg">
        
        <!-- Header -->
        <div class="text-center pb-3 border-b border-dashed border-slate-400">
            <div class="text-base font-bold tracking-tight uppercase">TOKO RETAIL MAJU JAYA</div>
            <div class="text-[10px] text-slate-600 mt-0.5">Jl. Raya Perintis Kemerdekaan No. 88</div>
            <div class="text-[10px] text-slate-600">Telp / WA: (021) 555-0199</div>
        </div>

        <!-- Transaction Meta -->
        <div class="py-2.5 border-b border-dashed border-slate-400 text-[11px] space-y-1">
            <div class="flex justify-between">
                <span>No. Faktur:</span>
                <span class="font-bold">{{ $transaction->invoice_number }}</span>
            </div>
            <div class="flex justify-between">
                <span>Waktu:</span>
                <span>{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kasir:</span>
                <span>{{ $transaction->user->name ?? 'Kasir' }}</span>
            </div>
        </div>

        <!-- Itemized List -->
        <div class="py-2.5 border-b border-dashed border-slate-400">
            <table class="w-full text-[11px]">
                <thead>
                    <tr class="text-slate-600 border-b border-slate-200">
                        <th class="text-left pb-1">Item</th>
                        <th class="text-center pb-1 w-12">Qty</th>
                        <th class="text-right pb-1">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($transaction->details as $item)
                        <tr class="align-top">
                            <td class="py-1 pr-1">
                                <div class="font-semibold">{{ $item->product->name ?? 'Produk' }}</div>
                                <div class="text-[9px] text-slate-500 font-mono">@ Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                            </td>
                            <td class="py-1 text-center font-bold">{{ $item->qty }}</td>
                            <td class="py-1 text-right font-semibold">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals & Payment -->
        <div class="py-2.5 border-b border-dashed border-slate-400 text-[11px] space-y-1.5">
            <div class="flex justify-between text-xs font-bold">
                <span>TOTAL BELANJA:</span>
                <span>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Tunai / Bayar:</span>
                <span>Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-slate-900">
                <span>KEMBALIAN:</span>
                <span>Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer Notice -->
        <div class="text-center pt-3 text-[10px] text-slate-600 space-y-1">
            <p class="font-bold">TERIMA KASIH ATAS KUNJUNGAN ANDA</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar atau dikembalikan kecuali ada perjanjian.</p>
            <div class="pt-2 font-mono text-[9px] text-slate-400">
                *** Struk Resmi Sistem POS ***
            </div>
        </div>

    </div>

    <!-- Auto Print Trigger -->
    <script>
        window.addEventListener('load', () => {
            // Delay slightly for font loading
            setTimeout(() => {
                window.print();
            }, 300);
        });
    </script>
</body>
</html>
