@extends('admin.layout')

@section('title', 'Admin Dashboard - Retail POS')

@section('admin-content')
<div class="space-y-4">
    
    <!-- Period Filter Pills -->
    <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 text-xs">
        <a href="{{ route('admin.dashboard', ['period' => 'today']) }}" 
            class="px-3 py-1.5 rounded-full font-semibold shrink-0 transition {{ $period === 'today' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200' }}">
            Hari Ini
        </a>
        <a href="{{ route('admin.dashboard', ['period' => 'yesterday']) }}" 
            class="px-3 py-1.5 rounded-full font-semibold shrink-0 transition {{ $period === 'yesterday' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200' }}">
            Kemarin
        </a>
        <a href="{{ route('admin.dashboard', ['period' => '7days']) }}" 
            class="px-3 py-1.5 rounded-full font-semibold shrink-0 transition {{ $period === '7days' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200' }}">
            7 Hari
        </a>
        <a href="{{ route('admin.dashboard', ['period' => 'month']) }}" 
            class="px-3 py-1.5 rounded-full font-semibold shrink-0 transition {{ $period === 'month' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white text-slate-600 border border-slate-200' }}">
            Bulan Ini
        </a>
    </div>

    <!-- Period Date Indicator -->
    <div class="flex items-center justify-between text-[11px] text-slate-500 bg-white px-3 py-2 rounded-xl border border-slate-200/80">
        <span class="flex items-center">
            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            Rentang: {{ $startDate->format('d M') }} &ndash; {{ $endDate->format('d M Y') }}
        </span>
        <span class="px-2 py-0.5 bg-blue-50 text-brand-600 font-bold rounded">Read-Only</span>
    </div>

    <!-- KPI Metric Cards (Blue Theme: border-l-4 border-blue-600 & icon text-sky-500) -->
    <div class="space-y-3">
        
        <!-- Card 1: Omzet / Pendapatan Kotor -->
        <div class="bg-white p-4 rounded-xl border-l-4 border-blue-600 border-y border-r border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Omzet Penjualan</p>
                <p class="text-xl font-extrabold text-slate-900 font-mono mt-0.5">
                    Rp {{ number_format($periodOmzet, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-0.5">
                    Hari ini: Rp {{ number_format($todayOmzet, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-sky-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <!-- Card 2: Keuntungan / Laba Bersih -->
        <div class="bg-white p-4 rounded-xl border-l-4 border-blue-600 border-y border-r border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Laba Bersih (Keuntungan)</p>
                <p class="text-xl font-extrabold text-emerald-600 font-mono mt-0.5">
                    Rp {{ number_format($periodProfit, 0, ',', '.') }}
                </p>
                <p class="text-[10px] text-slate-400 mt-0.5">
                    Total Akumulasi: Rp {{ number_format($allTimeProfit, 0, ',', '.') }}
                </p>
            </div>
            <div class="w-11 h-11 rounded-xl bg-emerald-50 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
            </div>
        </div>

        <!-- Card 3 & 4 (Grid 2-col) -->
        <div class="grid grid-cols-2 gap-3">
            <!-- Total Transaksi -->
            <div class="bg-white p-3.5 rounded-xl border-l-4 border-blue-600 border-y border-r border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Transaksi</p>
                    <svg class="w-4 h-4 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
                <p class="text-lg font-extrabold text-slate-900 font-mono mt-1">{{ $periodTransactionsCount }}</p>
                <p class="text-[9px] text-slate-400 mt-0.5">Struk kasir tercatat</p>
            </div>

            <!-- Stok Menipis Alert -->
            <div class="bg-white p-3.5 rounded-xl border-l-4 border-blue-600 border-y border-r border-slate-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Stok Tipis (&lt;10)</p>
                    <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
                <p class="text-lg font-extrabold {{ $lowStockCount > 0 ? 'text-amber-600' : 'text-slate-900' }} font-mono mt-1">
                    {{ $lowStockCount }} Item
                </p>
                <p class="text-[9px] text-slate-400 mt-0.5">Dari {{ $totalProductsCount }} master produk</p>
            </div>
        </div>

    </div>

    <!-- Top 5 Produk Terlaris (Period) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700 flex items-center">
                <svg class="w-4 h-4 mr-1 text-sky-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                Produk Terlaris Periode Ini
            </h3>
        </div>

        <div class="space-y-2.5">
            @forelse($topProducts as $idx => $item)
                <div class="flex items-center justify-between py-1.5 border-b border-slate-100 last:border-none">
                    <div class="flex items-center space-x-2">
                        <span class="w-5 h-5 rounded-full bg-slate-100 text-[10px] font-bold text-slate-600 flex items-center justify-center shrink-0">
                            {{ $idx + 1 }}
                        </span>
                        <div>
                            <div class="text-xs font-bold text-slate-800 line-clamp-1">{{ $item->product->name ?? 'Produk' }}</div>
                            <div class="text-[10px] text-slate-400 font-mono">{{ $item->total_sold }} unit terjual</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs font-mono font-bold text-slate-900">Rp {{ number_format($item->total_sales, 0, ',', '.') }}</div>
                        <div class="text-[10px] text-emerald-600 font-mono">+Rp {{ number_format($item->total_profit, 0, ',', '.') }}</div>
                    </div>
                </div>
            @empty
                <div class="text-center py-4 text-xs text-slate-400">Belum ada penjualan pada rentang periode ini.</div>
            @endforelse
        </div>
    </div>

    <!-- Transaksi Terkini Preview -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">
                Transaksi Kasir Terkini
            </h3>
            <a href="{{ route('admin.sales') }}" class="text-[11px] font-semibold text-brand-600 hover:text-brand-700">
                Semua &rarr;
            </a>
        </div>

        <div class="space-y-2">
            @forelse($recentTransactions as $tx)
                <a href="{{ route('admin.sales.detail', $tx->id) }}" class="block p-2.5 hover:bg-slate-50 rounded-lg border border-slate-100 transition">
                    <div class="flex items-center justify-between">
                        <span class="font-mono text-xs font-bold text-brand-700">{{ $tx->invoice_number }}</span>
                        <span class="font-mono text-xs font-bold text-slate-900">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[10px] text-slate-400 mt-1">
                        <span>{{ $tx->created_at->format('d M, H:i') }} &bull; Kasir: {{ $tx->user->name ?? '-' }}</span>
                        <span class="text-brand-600 font-medium">Rincian &rarr;</span>
                    </div>
                </a>
            @empty
                <div class="text-center py-4 text-xs text-slate-400">Belum ada riwayat transaksi.</div>
            @endforelse
        </div>
    </div>

</div>
@endsection
