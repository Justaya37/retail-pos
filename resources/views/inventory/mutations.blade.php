@extends('layouts.app')

@section('title', 'Riwayat Mutasi Stok - Retail POS')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col">
    
    <!-- Top Navigation Bar -->
    <header class="bg-brand-600 text-white px-6 py-3.5 shadow-md flex items-center justify-between z-20">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                <svg class="w-6 h-6 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight flex items-center">
                    LOG AUDIT MUTASI STOK
                    <span class="ml-2.5 px-2.5 py-0.5 text-[11px] font-semibold bg-sky-400/30 text-sky-100 rounded-md border border-sky-300/30 uppercase">
                        Gudang
                    </span>
                </h1>
                <p class="text-xs text-blue-100">Riwayat barang masuk dan penyesuaian stok fisik</p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('inventory.index') }}" 
                class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-semibold border border-white/15 flex items-center transition">
                <svg class="w-4 h-4 mr-1.5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Master Produk
            </a>
            <a href="{{ route('inventory.mutations') }}" 
                class="px-3.5 py-2 bg-white/20 text-white rounded-xl text-xs font-bold border border-white/25 flex items-center shadow-xs">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Riwayat Mutasi Stok
            </a>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="px-3.5 py-2 bg-rose-600/80 hover:bg-rose-600 text-white rounded-xl text-xs font-semibold flex items-center transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <!-- Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto p-6 space-y-6">

        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('inventory.mutations') }}" method="GET" class="flex-1 w-full flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari produk mutasi..."
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-500 rounded-xl text-sm transition">
                </div>

                <select name="type" onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:border-brand-500">
                    <option value="">Semua Jenis Mutasi</option>
                    <option value="in" {{ $type === 'in' ? 'selected' : '' }}>Barang Masuk (IN)</option>
                    <option value="adjustment" {{ $type === 'adjustment' ? 'selected' : '' }}>Penyesuaian (ADJUSTMENT)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold transition">
                    Terapkan
                </button>
                @if($search || $type)
                    <a href="{{ route('inventory.mutations') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>

            <a href="{{ route('inventory.index') }}" class="px-4 py-2 bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-bold rounded-xl border border-brand-200 transition shrink-0 flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali ke Master Produk
            </a>
        </div>

        <!-- Mutations Table -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-brand-900 text-white text-xs uppercase font-semibold">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">#</th>
                            <th class="py-3.5 px-4">Waktu Transaksi</th>
                            <th class="py-3.5 px-4">Petugas / Operator</th>
                            <th class="py-3.5 px-4">Nama Produk &amp; Barcode</th>
                            <th class="py-3.5 px-4 text-center">Jenis Mutasi</th>
                            <th class="py-3.5 px-4 text-right">Perubahan Qty</th>
                            <th class="py-3.5 px-4">Alasan / Catatan Supplier</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($mutations as $index => $mut)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-400">
                                    {{ $mutations->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 font-mono text-xs text-slate-600">
                                    {{ $mut->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-medium text-slate-800">{{ $mut->user->name ?? 'Sistem' }}</div>
                                    <div class="text-[10px] text-slate-400 capitalize">{{ $mut->user->role ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $mut->product->name ?? 'Produk Terhapus' }}</div>
                                    <div class="font-mono text-xs text-brand-600">{{ $mut->product->barcode ?? '-' }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($mut->type === 'in')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-800 border border-emerald-200">
                                            + BARANG MASUK
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-amber-100 text-amber-800 border border-amber-200">
                                            &plusmn; KOREKSI STOK
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold text-sm">
                                    @if($mut->qty > 0)
                                        <span class="text-emerald-600">+{{ $mut->qty }}</span>
                                    @else
                                        <span class="text-rose-600">{{ $mut->qty }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-slate-600 text-xs">
                                    {{ $mut->notes ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-12 text-slate-400">
                                    Belum ada log mutasi stok yang tercatat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($mutations->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $mutations->links() }}
                </div>
            @endif
        </div>

    </main>
</div>
@endsection
