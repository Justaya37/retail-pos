@extends('admin.layout')

@section('title', 'Detail Transaksi - Retail POS')

@section('admin-content')
<div class="space-y-4">
    
    <!-- Top Back Navigation -->
    <div class="flex items-center space-x-2">
        <a href="{{ route('admin.sales') }}" class="p-2 bg-white hover:bg-slate-100 rounded-xl border border-slate-200 text-slate-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
        </a>
        <div>
            <h2 class="text-sm font-extrabold text-slate-900 leading-none">Rincian Transaksi</h2>
            <p class="text-[10px] text-slate-400 mt-0.5">Mode Pratinjau Read-Only</p>
        </div>
    </div>

    <!-- Invoice Header Card -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-2">
        <div class="flex items-center justify-between border-b border-slate-100 pb-2">
            <span class="text-xs font-mono font-bold text-brand-700">{{ $transaction->invoice_number }}</span>
            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-700 text-[10px] font-bold rounded border border-emerald-200">
                Lunas / Selesai
            </span>
        </div>
        <div class="grid grid-cols-2 gap-2 text-[11px] text-slate-600">
            <div>
                <span class="text-slate-400 block text-[10px]">Waktu:</span>
                <span class="font-medium">{{ $transaction->created_at->format('d/m/Y H:i:s') }}</span>
            </div>
            <div>
                <span class="text-slate-400 block text-[10px]">Kasir:</span>
                <span class="font-medium">{{ $transaction->user->name ?? 'Kasir' }}</span>
            </div>
        </div>
    </div>

    <!-- Line Items Table -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-3 bg-slate-50 border-b border-slate-200 text-xs font-bold uppercase tracking-wider text-slate-700">
            Daftar Produk Terjual ({{ $transaction->details->count() }} Item)
        </div>
        <div class="divide-y divide-slate-100 text-xs">
            @foreach($transaction->details as $item)
                @php
                    $itemProfit = ($item->price - $item->cost_price) * $item->qty;
                @endphp
                <div class="p-3 space-y-1">
                    <div class="flex justify-between font-bold text-slate-900">
                        <span>{{ $item->product->name ?? 'Produk Terhapus' }}</span>
                        <span class="font-mono">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] text-slate-500 font-mono">
                        <span>{{ $item->qty }} unit &times; Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <span class="text-slate-400">Modal: @ Rp {{ number_format($item->cost_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-[11px] text-emerald-600 font-mono font-medium pt-0.5">
                        <span>Laba Bersih Produk:</span>
                        <span>+Rp {{ number_format($itemProfit, 0, ',', '.') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Financial Summary -->
        <div class="p-4 bg-slate-50 border-t border-slate-200 space-y-1.5 text-xs">
            <div class="flex justify-between text-slate-600">
                <span>Total Omzet Penjualan:</span>
                <span class="font-mono font-bold text-slate-900">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Uang Pembayaran Tunai:</span>
                <span class="font-mono text-slate-800">Rp {{ number_format($transaction->payment_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-slate-600">
                <span>Kembalian Diserahkan:</span>
                <span class="font-mono text-slate-800">Rp {{ number_format($transaction->change_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-emerald-700 font-bold border-t border-slate-200 pt-2 text-sm">
                <span>Total Laba / Keuntungan:</span>
                <span class="font-mono">+Rp {{ number_format($totalProfit, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Print Thermal Shortcut for Admin -->
    <a href="{{ route('pos.print', $transaction->id) }}" target="_blank"
        class="w-full py-3 bg-brand-600 hover:bg-brand-700 text-white rounded-xl text-xs font-bold flex items-center justify-center shadow-md transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
        Pratinjau Struk Thermal Kasir
    </a>

</div>
@endsection
