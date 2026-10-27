@extends('layouts.app')

@section('title', 'Manajemen Inventaris Gudang - Retail POS')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col">
    
    <!-- Top Navigation Bar (Gudang Desktop) -->
    <header class="bg-brand-600 text-white px-6 py-3.5 shadow-md flex items-center justify-between z-20">
        <div class="flex items-center space-x-4">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                <svg class="w-6 h-6 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight flex items-center">
                    MANAJEMEN GUDANG &amp; INVENTARIS
                    <span class="ml-2.5 px-2.5 py-0.5 text-[11px] font-semibold bg-sky-400/30 text-sky-100 rounded-md border border-sky-300/30 uppercase">
                        Role: Gudang
                    </span>
                </h1>
                <p class="text-xs text-blue-100">Petugas: <span class="font-bold text-white">{{ Auth::user()->name }}</span></p>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('inventory.index') }}" 
                class="px-3.5 py-2 bg-white/20 text-white rounded-xl text-xs font-bold border border-white/25 flex items-center shadow-xs">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                Master Produk
            </a>
            <a href="{{ route('inventory.mutations') }}" 
                class="px-3.5 py-2 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-semibold border border-white/15 flex items-center transition">
                <svg class="w-4 h-4 mr-1.5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
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

        <!-- Metrics Summary Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Produk -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Master Produk</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1 font-mono">{{ $totalProducts }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Item terdaftar di sistem</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-brand-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>

            <!-- Total Unit Stok -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Fisik Stok</p>
                    <p class="text-2xl font-extrabold text-slate-900 mt-1 font-mono">{{ number_format($totalStockUnits, 0, ',', '.') }}</p>
                    <p class="text-[11px] text-slate-400 mt-0.5">Unit barang di gudang</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-sky-50 text-accent-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>

            <!-- Stok Menipis (< 10) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-amber-600 uppercase tracking-wider">Stok Menipis (&lt; 10)</p>
                    <p class="text-2xl font-extrabold text-amber-600 mt-1 font-mono">{{ $lowStockCount }}</p>
                    <p class="text-[11px] text-amber-500 mt-0.5">Perlu segera restock</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>

            <!-- Stok Kosong (0) -->
            <div class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-rose-600 uppercase tracking-wider">Stok Habis (0)</p>
                    <p class="text-2xl font-extrabold text-rose-600 mt-1 font-mono">{{ $outOfStockCount }}</p>
                    <p class="text-[11px] text-rose-500 mt-0.5">Tidak dapat dijual di POS</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                </div>
            </div>
        </div>

        <!-- Action Toolbar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Search & Filter Form -->
            <form action="{{ route('inventory.index') }}" method="GET" class="flex-1 w-full flex flex-col sm:flex-row gap-2">
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Cari nama produk atau barcode..."
                        class="w-full pl-9 pr-4 py-2 bg-slate-50 border border-slate-200 focus:bg-white focus:border-brand-500 rounded-xl text-sm transition">
                </div>

                <select name="stock_filter" onchange="this.form.submit()"
                    class="py-2 px-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-medium text-slate-700 focus:bg-white focus:border-brand-500">
                    <option value="all" {{ $stockFilter === 'all' ? 'selected' : '' }}>Semua Status Stok</option>
                    <option value="safe" {{ $stockFilter === 'safe' ? 'selected' : '' }}>Stok Aman (≥ 10)</option>
                    <option value="low" {{ $stockFilter === 'low' ? 'selected' : '' }}>Stok Menipis (&lt; 10)</option>
                    <option value="empty" {{ $stockFilter === 'empty' ? 'selected' : '' }}>Stok Habis (0)</option>
                </select>

                <button type="submit" class="px-4 py-2 bg-slate-800 hover:bg-slate-900 text-white rounded-xl text-sm font-semibold transition">
                    Filter
                </button>
                @if($search || $stockFilter !== 'all')
                    <a href="{{ route('inventory.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-sm font-semibold transition flex items-center justify-center">
                        Reset
                    </a>
                @endif
            </form>

            <!-- 3 Main Action CTA Buttons -->
            <div class="flex items-center space-x-2 w-full md:w-auto shrink-0 justify-end">
                <!-- Tambah Produk Baru -->
                <button type="button" onclick="openCreateProductModal()"
                    class="px-3.5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs rounded-xl shadow-md shadow-brand-500/20 flex items-center transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    + Produk Baru
                </button>

                <!-- Barang Masuk (Stock In) -->
                <button type="button" onclick="openStockInModal()"
                    class="px-3.5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 flex items-center transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    + Barang Masuk
                </button>

                <!-- Koreksi Stok (Adjustment) -->
                <button type="button" onclick="openAdjustmentModal()"
                    class="px-3.5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-xs rounded-xl shadow-md shadow-amber-500/20 flex items-center transition">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    &plusmn; Koreksi Stok
                </button>
            </div>

        </div>

        <!-- Master Products Table (Header Navy) -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-brand-900 text-white text-xs uppercase font-semibold">
                        <tr>
                            <th class="py-3.5 px-4 w-12 text-center">#</th>
                            <th class="py-3.5 px-4">Kode Barcode</th>
                            <th class="py-3.5 px-4">Nama Produk</th>
                            <th class="py-3.5 px-4 text-right">Harga Modal (Beli)</th>
                            <th class="py-3.5 px-4 text-right">Harga Jual</th>
                            <th class="py-3.5 px-4 text-right">Margin / Laba</th>
                            <th class="py-3.5 px-4 text-center">Status Stok</th>
                            <th class="py-3.5 px-4 text-center w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @forelse($products as $index => $prod)
                            @php
                                $margin = $prod->price_sell - $prod->price_buy;
                                $marginPercent = $prod->price_buy > 0 ? ($margin / $prod->price_buy) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="py-3.5 px-4 text-center font-mono text-xs text-slate-400">
                                    {{ $products->firstItem() + $index }}
                                </td>
                                <td class="py-3.5 px-4 font-mono font-semibold text-xs text-brand-700">
                                    <span class="px-2 py-0.5 bg-slate-100 rounded border border-slate-200">
                                        {{ $prod->barcode }}
                                    </span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="font-bold text-slate-900">{{ $prod->name }}</div>
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono text-slate-600">
                                    Rp {{ number_format($prod->price_buy, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">
                                    Rp {{ number_format($prod->price_sell, 0, ',', '.') }}
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <span class="font-mono text-xs font-semibold text-emerald-600">
                                        +Rp {{ number_format($margin, 0, ',', '.') }}
                                    </span>
                                    <div class="text-[10px] text-slate-400 font-mono">({{ number_format($marginPercent, 1) }}%)</div>
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    @if($prod->stock <= 0)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-red-100 text-red-800 border border-red-200">
                                            Habis (0)
                                        </span>
                                    @elseif($prod->stock < 10)
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-red-100 text-red-800 border border-red-200">
                                            Tipis ({{ $prod->stock }})
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-sky-100 text-sky-800 border border-sky-200">
                                            Aman ({{ $prod->stock }})
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-center">
                                    <div class="inline-flex items-center space-x-1.5">
                                        <!-- Edit Button -->
                                        <button type="button" 
                                            onclick="openEditProductModal({{ $prod->id }}, '{{ addslashes($prod->barcode) }}', '{{ addslashes($prod->name) }}', {{ $prod->price_buy }}, {{ $prod->price_sell }})"
                                            class="p-1.5 text-slate-500 hover:text-brand-600 hover:bg-brand-50 rounded-lg transition" title="Edit Data Produk">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>

                                        <!-- Delete Button -->
                                        <form action="{{ route('inventory.products.destroy', $prod->id) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus Produk">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-12 text-slate-400">
                                    Tidak ada produk yang cocok dengan kriteria pencarian.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($products->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $products->links() }}
                </div>
            @endif
        </div>

    </main>
</div>

<!-- ============================================================== -->
<!-- MODAL 1: Tambah Master Produk Baru -->
<!-- ============================================================== -->
<div id="createProductModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="p-4 bg-brand-600 text-white flex items-center justify-between">
            <h3 class="font-bold text-base flex items-center">
                <svg class="w-5 h-5 mr-2 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Master Produk Baru
            </h3>
            <button type="button" onclick="closeCreateProductModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('inventory.products.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kode Barcode (Unik)</label>
                <input type="text" name="barcode" required placeholder="Contoh: 8998866200225"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Produk</label>
                <input type="text" name="name" required placeholder="Contoh: Teh Pucuk Harum 350ml"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500 focus:ring-2 focus:ring-brand-200">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Harga Beli / Modal (Rp)</label>
                    <input type="number" name="price_buy" id="create_price_buy" min="0" step="100" required placeholder="2500"
                        oninput="validatePrices('create')"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" name="price_sell" id="create_price_sell" min="0" step="100" required placeholder="3500"
                        oninput="validatePrices('create')"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500">
                </div>
            </div>
            <p id="create_price_warning" class="text-xs text-rose-600 hidden font-medium">
                Peringatan: Harga jual tidak boleh lebih rendah dari harga modal (beli)!
            </p>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Stok Awal (Unit)</label>
                <input type="number" name="stock" min="0" value="0" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500">
                <span class="text-[11px] text-slate-400">Mutasi stok masuk awal akan otomatis tercatat di log audit.</span>
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="closeCreateProductModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                <button type="submit" id="create_submit_btn"
                    class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md">Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 2: Edit Master Produk -->
<!-- ============================================================== -->
<div id="editProductModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="p-4 bg-brand-600 text-white flex items-center justify-between">
            <h3 class="font-bold text-base flex items-center">
                <svg class="w-5 h-5 mr-2 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Perbarui Data Produk
            </h3>
            <button type="button" onclick="closeEditProductModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form id="editProductForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Kode Barcode (Unik)</label>
                <input type="text" id="edit_barcode" name="barcode" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Nama Produk</label>
                <input type="text" id="edit_name" name="name" required
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-brand-500">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Harga Beli / Modal (Rp)</label>
                    <input type="number" id="edit_price_buy" name="price_buy" min="0" step="100" required
                        oninput="validatePrices('edit')"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Harga Jual (Rp)</label>
                    <input type="number" id="edit_price_sell" name="price_sell" min="0" step="100" required
                        oninput="validatePrices('edit')"
                        class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-brand-500">
                </div>
            </div>
            <p id="edit_price_warning" class="text-xs text-rose-600 hidden font-medium">
                Peringatan: Harga jual tidak boleh lebih rendah dari harga modal (beli)!
            </p>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="closeEditProductModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                <button type="submit" id="edit_submit_btn"
                    class="px-5 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold rounded-xl shadow-md">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 3: Form Barang Masuk (Stock In) -->
<!-- ============================================================== -->
<div id="stockInModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="p-4 bg-emerald-600 text-white flex items-center justify-between">
            <h3 class="font-bold text-base flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Penerimaan Barang Masuk (Stock In)
            </h3>
            <button type="button" onclick="closeStockInModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('inventory.stock-in') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pilih Produk</label>
                <select name="product_id" required class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-emerald-500">
                    <option value="">-- Pilih Produk yang Masuk --</option>
                    @foreach($allProductsList as $p)
                        <option value="{{ $p->id }}">{{ $p->name }} (Barcode: {{ $p->barcode }}) - Stok: {{ $p->stock }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Jumlah Unit Masuk (Qty)</label>
                <input type="number" name="qty" min="1" required placeholder="Contoh: 50"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-emerald-500">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Catatan Penerimaan / Supplier</label>
                <textarea name="notes" rows="2" placeholder="Contoh: Dari PT Mayora Indah Tbk, No. DO: DO-99214"
                    class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm focus:border-emerald-500"></textarea>
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="closeStockInModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                <button type="submit"
                    class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-md">Simpan Barang Masuk</button>
            </div>
        </form>
    </div>
</div>

<!-- ============================================================== -->
<!-- MODAL 4: Form Penyesuaian / Koreksi Stok (Adjustment) -->
<!-- ============================================================== -->
<div id="adjustmentModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden border border-slate-200">
        <div class="p-4 bg-amber-600 text-white flex items-center justify-between">
            <h3 class="font-bold text-base flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Penyesuaian / Koreksi Stok Fisik
            </h3>
            <button type="button" onclick="closeAdjustmentModal()" class="text-white/80 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <form action="{{ route('inventory.stock-adjustment') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Pilih Produk</label>
                <select name="product_id" id="adj_product_select" required onchange="handleAdjProductChange()"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-sm focus:border-amber-500">
                    <option value="" data-stock="0">-- Pilih Produk yang Dikoreksi --</option>
                    @foreach($allProductsList as $p)
                        <option value="{{ $p->id }}" data-stock="{{ $p->stock }}">
                            {{ $p->name }} (Barcode: {{ $p->barcode }}) - Stok Saat Ini: {{ $p->stock }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs text-amber-800">
                <span>Stok tercatat saat ini: <strong id="adj_current_stock" class="font-mono text-sm">0</strong> unit.</span>
                <span class="block text-[11px] text-amber-700 mt-0.5">Isi angka <strong>positif (+)</strong> untuk menambah atau <strong>negatif (-)</strong> untuk mengurangi stok. Stok akhir tidak boleh kurang dari 0.</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">Angka Koreksi (+ / -)</label>
                <input type="number" name="qty" id="adj_qty_input" required placeholder="Contoh: -2 atau +5"
                    oninput="calculateAdjFinalStock()"
                    class="w-full px-4 py-2.5 rounded-xl border border-slate-300 font-mono text-sm focus:border-amber-500">
                <div class="mt-1 text-xs">
                    Estimasi Stok Akhir: <strong id="adj_final_stock" class="font-mono text-slate-900">0</strong> unit
                </div>
                <p id="adj_error_msg" class="text-xs text-rose-600 hidden mt-1 font-semibold">
                    Stok akhir tidak boleh kurang dari 0!
                </p>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                    Alasan Koreksi Stok (Wajib Diisi)
                </label>
                <textarea name="notes" rows="2" required placeholder="Contoh: 2 botol rusak saat pembongkaran rak, atau selisih audit fisik opname bulanan..."
                    class="w-full px-4 py-2 rounded-xl border border-slate-300 text-sm focus:border-amber-500"></textarea>
            </div>

            <div class="pt-3 flex justify-end space-x-2">
                <button type="button" onclick="closeAdjustmentModal()"
                    class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-xl">Batal</button>
                <button type="submit" id="adj_submit_btn"
                    class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold rounded-xl shadow-md">Simpan Koreksi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Validation helper for Price Sell >= Price Buy
    function validatePrices(mode) {
        const buy = parseFloat(document.getElementById(`${mode}_price_buy`).value) || 0;
        const sell = parseFloat(document.getElementById(`${mode}_price_sell`).value) || 0;
        const warning = document.getElementById(`${mode}_price_warning`);
        const btn = document.getElementById(`${mode}_submit_btn`);

        if (sell < buy && sell > 0) {
            warning.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            warning.classList.add('hidden');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Modal 1: Create Product
    function openCreateProductModal() {
        document.getElementById('createProductModal').classList.remove('hidden');
    }
    function closeCreateProductModal() {
        document.getElementById('createProductModal').classList.add('hidden');
    }

    // Modal 2: Edit Product
    function openEditProductModal(id, barcode, name, buy, sell) {
        const form = document.getElementById('editProductForm');
        form.action = `{{ url('/inventory/products') }}/${id}`;
        document.getElementById('edit_barcode').value = barcode;
        document.getElementById('edit_name').value = name;
        document.getElementById('edit_price_buy').value = buy;
        document.getElementById('edit_price_sell').value = sell;
        validatePrices('edit');
        document.getElementById('editProductModal').classList.remove('hidden');
    }
    function closeEditProductModal() {
        document.getElementById('editProductModal').classList.add('hidden');
    }

    // Modal 3: Stock In
    function openStockInModal() {
        document.getElementById('stockInModal').classList.remove('hidden');
    }
    function closeStockInModal() {
        document.getElementById('stockInModal').classList.add('hidden');
    }

    // Modal 4: Adjustment
    function openAdjustmentModal() {
        document.getElementById('adjustmentModal').classList.remove('hidden');
        handleAdjProductChange();
    }
    function closeAdjustmentModal() {
        document.getElementById('adjustmentModal').classList.add('hidden');
    }

    function handleAdjProductChange() {
        const select = document.getElementById('adj_product_select');
        const opt = select.options[select.selectedIndex];
        const stock = parseInt(opt.getAttribute('data-stock')) || 0;
        document.getElementById('adj_current_stock').textContent = stock;
        calculateAdjFinalStock();
    }

    function calculateAdjFinalStock() {
        const select = document.getElementById('adj_product_select');
        const opt = select.options[select.selectedIndex];
        const currentStock = parseInt(opt.getAttribute('data-stock')) || 0;
        const delta = parseInt(document.getElementById('adj_qty_input').value) || 0;
        const finalStock = currentStock + delta;

        const finalDisplay = document.getElementById('adj_final_stock');
        const errorMsg = document.getElementById('adj_error_msg');
        const submitBtn = document.getElementById('adj_submit_btn');

        finalDisplay.textContent = finalStock;

        if (finalStock < 0) {
            errorMsg.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            errorMsg.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }
</script>
@endpush
