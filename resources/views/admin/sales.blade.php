@extends('admin.layout')

@section('title', 'Laporan Penjualan - Retail POS')

@section('admin-content')
<div class="space-y-4">
    
    <!-- Title & Badge -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Laporan Penjualan</h2>
            <p class="text-xs text-slate-500">Rekap transaksi kasir (Murni Read-Only)</p>
        </div>
        <span class="px-2 py-0.5 bg-blue-50 text-brand-700 text-[10px] font-bold rounded-md border border-blue-200">
            {{ $filteredCount }} Transaksi
        </span>
    </div>

    <!-- Date Range Filter Form -->
    <form action="{{ route('admin.sales') }}" method="GET" class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm space-y-2.5">
        <div class="grid grid-cols-2 gap-2 text-xs">
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                    class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono focus:bg-white focus:border-brand-500">
            </div>
            <div>
                <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                    class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-mono focus:bg-white focus:border-brand-500">
            </div>
        </div>
        <button type="submit" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-xs font-bold shadow-xs transition">
            Terapkan Filter Tanggal
        </button>
    </form>

    <!-- Period Aggregates Strip -->
    <div class="grid grid-cols-2 gap-2">
        <div class="bg-gradient-to-br from-brand-900 to-brand-800 text-white p-3 rounded-xl shadow-xs">
            <span class="text-[10px] text-sky-200 uppercase font-semibold">Total Omzet Periode</span>
            <div class="font-mono text-base font-extrabold mt-0.5">
                Rp {{ number_format($filteredOmzet, 0, ',', '.') }}
            </div>
        </div>
        <div class="bg-gradient-to-br from-emerald-800 to-emerald-700 text-white p-3 rounded-xl shadow-xs">
            <span class="text-[10px] text-emerald-200 uppercase font-semibold">Laba Kotor Periode</span>
            <div class="font-mono text-base font-extrabold mt-0.5">
                Rp {{ number_format($filteredProfit, 0, ',', '.') }}
            </div>
        </div>
    </div>

    <!-- Vertical Transaction Cards -->
    <div class="space-y-3">
        @forelse($transactions as $tx)
            @php
                $txProfit = $tx->details->sum(function($item) {
                    return ($item->price - $item->cost_price) * $item->qty;
                });
            @endphp
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm space-y-2">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <span class="font-mono text-xs font-bold text-brand-700">{{ $tx->invoice_number }}</span>
                        <div class="text-[10px] text-slate-400 mt-0.5">
                            {{ $tx->created_at->format('d M Y, H:i') }} &bull; Kasir: {{ $tx->user->name ?? '-' }}
                        </div>
                    </div>
                    <a href="{{ route('admin.sales.detail', $tx->id) }}" 
                        class="px-2.5 py-1 bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold rounded-lg border border-brand-200 transition shrink-0 flex items-center">
                        Rincian
                        <svg class="w-3.5 h-3.5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                <div class="text-xs text-slate-600 space-y-1">
                    @foreach($tx->details->take(2) as $det)
                        <div class="flex justify-between text-[11px]">
                            <span class="truncate pr-2">{{ $det->product->name ?? 'Produk' }} (x{{ $det->qty }})</span>
                            <span class="font-mono text-slate-800">Rp {{ number_format($det->subtotal, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                    @if($tx->details->count() > 2)
                        <div class="text-[10px] text-slate-400 italic">
                            +{{ $tx->details->count() - 2 }} item lainnya...
                        </div>
                    @endif
                </div>

                <div class="flex items-center justify-between pt-2 border-t border-slate-100 text-xs">
                    <div>
                        <span class="text-[10px] text-slate-400">Keuntungan/Laba:</span>
                        <span class="font-mono font-bold text-emerald-600 block text-xs">
                            +Rp {{ number_format($txProfit, 0, ',', '.') }}
                        </span>
                    </div>
                    <div class="text-right">
                        <span class="text-[10px] text-slate-400">Total Transaksi:</span>
                        <span class="font-mono font-extrabold text-slate-900 block text-sm">
                            Rp {{ number_format($tx->total_amount, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-xl border border-slate-200 text-center text-slate-400 text-xs">
                Tidak ada data transaksi pada rentang tanggal yang dipilih.
            </div>
        @endforelse

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="pt-2">
                {{ $transactions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
