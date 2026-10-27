@extends('layouts.app')

@section('title', 'POS Kasir Desktop - Retail POS')

@section('content')
<div class="h-screen flex flex-col bg-slate-100 overflow-hidden select-none">
    
    <!-- Top Navigation Bar (Desktop Kiosk Mode) -->
    <header class="bg-brand-600 text-white px-6 py-3 flex items-center justify-between shadow-md z-30 shrink-0">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center backdrop-blur-sm border border-white/20">
                <svg class="w-6 h-6 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-lg font-extrabold tracking-tight flex items-center">
                    RETAIL POS KIOSK
                    <span class="ml-2 px-2 py-0.5 text-[11px] font-semibold bg-sky-400/30 text-sky-100 rounded-md border border-sky-300/30 uppercase">
                        Mode Kasir
                    </span>
                </h1>
                <p class="text-xs text-blue-100">Terminal #01 &bull; Kasir: <span class="font-bold text-white">{{ Auth::user()->name }}</span></p>
            </div>
        </div>

        <div class="flex items-center space-x-4">
            <!-- Digital Clock -->
            <div class="text-right hidden sm:block">
                <div id="liveClock" class="font-mono text-sm font-semibold tracking-wide">00:00:00</div>
                <div class="text-[11px] text-blue-200">{{ date('d M Y') }}</div>
            </div>

            <!-- Recent Receipts Button -->
            <button type="button" onclick="openRecentModal()" 
                class="px-3 py-1.5 bg-white/10 hover:bg-white/20 text-white rounded-lg text-xs font-semibold flex items-center border border-white/20 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Struk Hari Ini ({{ $recentTransactions->count() }})
            </button>

            <!-- Logout -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="px-3 py-1.5 bg-rose-600/80 hover:bg-rose-600 text-white rounded-lg text-xs font-semibold flex items-center transition">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar
                </button>
            </form>
        </div>
    </header>

    <!-- Main POS Body (Fullscreen Landscape Grid) -->
    <main class="flex-1 flex overflow-hidden p-3 gap-3">
        
        <!-- Left Side: Barcode Input, Cart Table, Quick Items -->
        <section class="flex-1 flex flex-col bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            
            <!-- Barcode Scanner Bar -->
            <div class="p-3 bg-slate-50/80 border-b border-slate-200">
                <form id="barcodeForm" onsubmit="handleBarcodeSubmit(event)" class="flex gap-2">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-accent-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <input id="barcodeInput" type="text" autofocus autocomplete="off"
                            placeholder="Scan Barcode / Tekan Enter untuk memasukkan produk..."
                            class="w-full pl-11 pr-24 py-3 bg-white border-2 border-sky-400 focus:border-brand-600 focus:ring-4 focus:ring-sky-100 rounded-xl text-base font-mono font-semibold text-slate-800 placeholder-slate-400 transition shadow-sm">
                        <div class="absolute inset-y-0 right-2 flex items-center">
                            <span class="text-[11px] font-mono font-medium px-2 py-1 bg-slate-100 text-slate-500 rounded border border-slate-200">
                                AUTO-SCAN
                            </span>
                        </div>
                    </div>

                    <button type="button" onclick="openSearchModal()"
                        class="px-4 py-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-xl text-sm border border-slate-200 flex items-center transition">
                        <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        Cari (F4)
                    </button>
                    <button type="button" onclick="clearCart()"
                        class="px-3 py-3 bg-rose-50 hover:bg-rose-100 text-rose-600 font-semibold rounded-xl text-sm border border-rose-200 transition" title="Kosongkan Keranjang">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </form>
            </div>

            <!-- Cart Table Area -->
            <div class="flex-1 overflow-y-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-brand-900 text-white text-xs uppercase font-semibold sticky top-0 z-10">
                        <tr>
                            <th class="py-3 px-4 w-12 text-center">#</th>
                            <th class="py-3 px-4">Nama Produk / Barcode</th>
                            <th class="py-3 px-4 text-right">Harga Satuan</th>
                            <th class="py-3 px-4 text-center w-36">Jumlah (Qty)</th>
                            <th class="py-3 px-4 text-right">Subtotal</th>
                            <th class="py-3 px-3 w-12 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="cartTableBody" class="divide-y divide-slate-100 text-sm">
                        <!-- Populated by JS -->
                    </tbody>
                </table>

                <!-- Empty State -->
                <div id="emptyCartState" class="h-64 flex flex-col items-center justify-center text-slate-400">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center mb-3 text-slate-300">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <p class="font-semibold text-slate-600">Keranjang Belanja Masih Kosong</p>
                    <p class="text-xs text-slate-400 mt-1">Arahkan scanner ke barcode produk atau pilih produk cepat di bawah</p>
                </div>
            </div>

            <!-- Quick Products Bar -->
            <div class="p-2.5 bg-slate-50 border-t border-slate-200">
                <div class="text-[11px] font-semibold uppercase text-slate-500 mb-1.5 flex items-center justify-between">
                    <span>Produk Cepat (Klik untuk tambah):</span>
                    <span class="text-slate-400 font-normal">Tersedia {{ $quickProducts->count() }} item</span>
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-2">
                    @foreach($quickProducts as $quick)
                        <button type="button" 
                            onclick="addToCart({{ $quick->id }}, '{{ addslashes($quick->barcode) }}', '{{ addslashes($quick->name) }}', {{ $quick->price_sell }}, {{ $quick->stock }})"
                            class="p-2 bg-white hover:bg-highlight-100 border border-slate-200 hover:border-sky-300 rounded-xl text-left transition shadow-xs group">
                            <div class="text-xs font-semibold text-slate-800 truncate group-hover:text-brand-700">{{ $quick->name }}</div>
                            <div class="flex items-center justify-between mt-1 text-[11px]">
                                <span class="font-mono text-brand-600 font-bold">Rp {{ number_format($quick->price_sell, 0, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">Stok {{ $quick->stock }}</span>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>

        </section>

        <!-- Right Side: Total Display, Payment, & Action CTA -->
        <aside class="w-96 flex flex-col bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden shrink-0">
            
            <!-- Large Display Total -->
            <div class="p-5 bg-gradient-to-br from-brand-900 to-brand-800 text-white text-right">
                <div class="text-xs uppercase tracking-wider text-sky-200 font-semibold mb-1">Total Tagihan (Rp)</div>
                <div id="displayTotal" class="font-mono text-4xl font-extrabold tracking-tight text-white">0</div>
                <div class="mt-2 text-xs text-sky-200/80 flex items-center justify-between">
                    <span>Item: <strong id="totalItemCount">0</strong></span>
                    <span>Total Qty: <strong id="totalQtyCount">0</strong></span>
                </div>
            </div>

            <!-- Payment Calculation Inputs -->
            <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1">
                        Uang Pembayaran Pelanggan (Rp)
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-slate-400 font-mono font-bold text-sm">Rp</span>
                        <input id="paymentInput" type="number" min="0" step="100"
                            placeholder="0"
                            oninput="calculateChange()"
                            class="w-full pl-11 pr-4 py-3 bg-slate-50 border-2 border-slate-300 focus:border-brand-600 focus:bg-white rounded-xl text-xl font-mono font-bold text-slate-900 transition">
                    </div>

                    <!-- Quick Cash Amounts -->
                    <div class="grid grid-cols-3 gap-1.5 mt-2">
                        <button type="button" onclick="setExactPayment()" class="py-1.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                            Uang Pas
                        </button>
                        <button type="button" onclick="addPayment(10000)" class="py-1.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                            +10.000
                        </button>
                        <button type="button" onclick="addPayment(20000)" class="py-1.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                            +20.000
                        </button>
                        <button type="button" onclick="addPayment(50000)" class="py-1.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                            +50.000
                        </button>
                        <button type="button" onclick="addPayment(100000)" class="py-1.5 px-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg border border-slate-200 transition">
                            +100.000
                        </button>
                        <button type="button" onclick="resetPayment()" class="py-1.5 px-2 bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-semibold rounded-lg border border-rose-200 transition">
                            Clear
                        </button>
                    </div>
                </div>

                <!-- Kembalian Panel -->
                <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                    <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-1">
                        Kembalian (Rp)
                    </div>
                    <div id="displayChange" class="font-mono text-2xl font-extrabold text-slate-800">
                        Rp 0
                    </div>
                    <div id="changeMessage" class="text-xs text-slate-500 mt-1">
                        Menunggu pembayaran...
                    </div>
                </div>

                <!-- Big CTA Pay Button -->
                <div class="space-y-2 pt-2">
                    <button id="payButton" type="button" onclick="processCheckout()" disabled
                        class="w-full py-4 px-6 bg-brand-600 hover:bg-brand-700 disabled:bg-slate-300 disabled:cursor-not-allowed text-white font-bold text-lg rounded-xl shadow-lg shadow-brand-500/25 transition duration-150 flex items-center justify-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <span>BAYAR &amp; CETAK STRUK</span>
                    </button>
                </div>

            </div>

        </aside>

    </main>
</div>

<!-- Modal: Cari Produk Manual (F4) -->
<div id="searchModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden border border-slate-200">
        <div class="p-4 bg-brand-900 text-white flex items-center justify-between">
            <h3 class="font-bold text-base flex items-center">
                <svg class="w-5 h-5 mr-2 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                Pencarian Produk Manual
            </h3>
            <button type="button" onclick="closeSearchModal()" class="text-slate-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="p-4 border-b border-slate-200">
            <input id="modalSearchQuery" type="text" placeholder="Ketik nama produk atau barcode..."
                oninput="performModalSearch()"
                class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 text-sm">
        </div>
        <div id="modalSearchResults" class="max-h-80 overflow-y-auto divide-y divide-slate-100 p-2">
            <div class="text-center py-8 text-slate-400 text-sm">Ketik untuk mencari produk...</div>
        </div>
    </div>
</div>

<!-- Modal: Riwayat Struk Hari Ini -->
<div id="recentModal" class="fixed inset-0 z-50 hidden bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden border border-slate-200">
        <div class="p-4 bg-brand-900 text-white flex items-center justify-between">
            <h3 class="font-bold text-base flex items-center">
                <svg class="w-5 h-5 mr-2 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Daftar Struk Kasir Hari Ini
            </h3>
            <button type="button" onclick="closeRecentModal()" class="text-slate-300 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="max-h-96 overflow-y-auto divide-y divide-slate-100 p-3">
            @forelse($recentTransactions as $tx)
                <div class="py-3 px-3 hover:bg-slate-50 rounded-xl flex items-center justify-between transition">
                    <div>
                        <div class="text-xs font-mono font-bold text-brand-700">{{ $tx->invoice_number }}</div>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            {{ $tx->created_at->format('H:i:s') }} &bull; {{ $tx->details->count() }} item &bull; Bayar: Rp {{ number_format($tx->payment_amount, 0, ',', '.') }}
                        </div>
                    </div>
                    <div class="text-right flex items-center space-x-2">
                        <span class="font-mono text-sm font-bold text-slate-800">Rp {{ number_format($tx->total_amount, 0, ',', '.') }}</span>
                        <a href="{{ route('pos.print', $tx->id) }}" target="_blank"
                            class="px-2.5 py-1 bg-brand-50 hover:bg-brand-100 text-brand-700 text-xs font-semibold rounded-lg border border-brand-200 transition">
                            Cetak
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-slate-400 text-sm">Belum ada transaksi hari ini.</div>
            @endforelse
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Live Digital Clock
    function updateClock() {
        const now = new Date();
        const timeStr = now.toLocaleTimeString('id-ID', { hour12: false });
        const el = document.getElementById('liveClock');
        if (el) el.textContent = timeStr;
    }
    setInterval(updateClock, 1000);
    updateClock();

    // In-Memory Cart State
    let cart = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Focus Barcode Scanner on load & keep focused
    const barcodeInput = document.getElementById('barcodeInput');
    window.addEventListener('load', () => {
        barcodeInput.focus();
    });

    document.addEventListener('click', (e) => {
        // If clicking outside inputs/buttons, re-focus barcode scanner
        if (!['INPUT', 'BUTTON', 'A', 'TEXTAREA', 'SELECT'].includes(e.target.tagName)) {
            barcodeInput.focus();
        }
    });

    // Keyboard Shortcuts
    document.addEventListener('keydown', (e) => {
        if (e.key === 'F4') {
            e.preventDefault();
            openSearchModal();
        } else if (e.key === 'F2') {
            e.preventDefault();
            document.getElementById('paymentInput').focus();
        } else if (e.key === 'Escape') {
            closeSearchModal();
            closeRecentModal();
            barcodeInput.focus();
        }
    });

    // Handle Barcode Submit
    async function handleBarcodeSubmit(e) {
        e.preventDefault();
        const barcode = barcodeInput.value.trim();
        if (!barcode) return;

        barcodeInput.value = '';
        barcodeInput.disabled = true;

        try {
            const res = await fetch(`{{ route('pos.product.find') }}?barcode=${encodeURIComponent(barcode)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const data = await res.json();

            if (data.success) {
                const prod = data.product;
                addToCart(prod.id, prod.barcode, prod.name, prod.price_sell, prod.stock);
            } else {
                showToast(data.message || 'Produk tidak ditemukan', 'error');
            }
        } catch (err) {
            showToast('Gagal memproses barcode: ' + err.message, 'error');
        } finally {
            barcodeInput.disabled = false;
            barcodeInput.focus();
        }
    }

    // Add Item to Cart
    function addToCart(id, barcode, name, price, stock) {
        const existing = cart.find(item => item.id === id);

        if (existing) {
            if (existing.qty + 1 > stock) {
                showToast(`Stok tidak mencukupi! Tersisa ${stock} unit untuk ${name}`, 'error');
                return;
            }
            existing.qty += 1;
            renderCart(id); // Pass newly highlighted id
        } else {
            if (stock <= 0) {
                showToast(`Stok habis untuk produk ${name}`, 'error');
                return;
            }
            cart.push({
                id: id,
                barcode: barcode,
                name: name,
                price: parseFloat(price),
                stock: parseInt(stock),
                qty: 1
            });
            renderCart(id); // Pass newly highlighted id
        }
    }

    // Update Qty
    function updateQty(id, newQty) {
        const item = cart.find(i => i.id === id);
        if (!item) return;

        newQty = parseInt(newQty);
        if (isNaN(newQty) || newQty <= 0) {
            removeFromCart(id);
            return;
        }

        if (newQty > item.stock) {
            showToast(`Maksimal stok tersedia hanya ${item.stock} unit.`, 'error');
            item.qty = item.stock;
        } else {
            item.qty = newQty;
        }
        renderCart(id);
    }

    // Remove Item
    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        renderCart();
    }

    // Clear Cart
    function clearCart() {
        if (cart.length === 0) return;
        if (confirm('Apakah Anda yakin ingin mengosongkan seluruh keranjang belanja?')) {
            cart = [];
            resetPayment();
            renderCart();
        }
    }

    // Render Cart HTML
    function renderCart(highlightId = null) {
        const tbody = document.getElementById('cartTableBody');
        const emptyState = document.getElementById('emptyCartState');

        if (cart.length === 0) {
            tbody.innerHTML = '';
            emptyState.classList.remove('hidden');
            updateTotals();
            return;
        }

        emptyState.classList.add('hidden');
        let html = '';

        cart.forEach((item, index) => {
            const subtotal = item.price * item.qty;
            const isHighlighted = item.id === highlightId;
            const highlightClass = isHighlighted ? 'bg-highlight-100 animate-scan-hit' : 'hover:bg-slate-50';

            html += `
                <tr class="${highlightClass} transition duration-150">
                    <td class="py-3 px-4 text-center font-mono text-xs text-slate-400">${index + 1}</td>
                    <td class="py-3 px-4">
                        <div class="font-bold text-slate-800">${item.name}</div>
                        <div class="text-[11px] font-mono text-slate-400">${item.barcode} &bull; Sisa stok: ${item.stock}</div>
                    </td>
                    <td class="py-3 px-4 text-right font-mono text-slate-700 font-semibold">
                        Rp ${item.price.toLocaleString('id-ID')}
                    </td>
                    <td class="py-3 px-4 text-center">
                        <div class="inline-flex items-center border border-slate-200 rounded-lg bg-white overflow-hidden shadow-xs">
                            <button type="button" onclick="updateQty(${item.id}, ${item.qty - 1})" class="px-2.5 py-1 text-slate-600 hover:bg-slate-100 font-bold text-sm">-</button>
                            <input type="number" min="1" max="${item.stock}" value="${item.qty}" 
                                onchange="updateQty(${item.id}, this.value)"
                                class="w-12 text-center border-x border-slate-200 py-1 font-mono font-bold text-xs text-slate-800 focus:outline-none">
                            <button type="button" onclick="updateQty(${item.id}, ${item.qty + 1})" class="px-2.5 py-1 text-slate-600 hover:bg-slate-100 font-bold text-sm">+</button>
                        </div>
                    </td>
                    <td class="py-3 px-4 text-right font-mono text-brand-700 font-bold">
                        Rp ${subtotal.toLocaleString('id-ID')}
                    </td>
                    <td class="py-3 px-3 text-center">
                        <button type="button" onclick="removeFromCart(${item.id})" class="text-slate-400 hover:text-rose-600 transition" title="Hapus Item">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
        updateTotals();
    }

    // Update Totals
    function getCartTotal() {
        return cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    }

    function updateTotals() {
        const total = getCartTotal();
        const totalQty = cart.reduce((sum, item) => sum + item.qty, 0);

        document.getElementById('displayTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
        document.getElementById('totalItemCount').textContent = cart.length;
        document.getElementById('totalQtyCount').textContent = totalQty;

        calculateChange();
    }

    // Payment Helpers
    function setExactPayment() {
        const total = getCartTotal();
        document.getElementById('paymentInput').value = total;
        calculateChange();
    }

    function addPayment(amount) {
        const current = parseFloat(document.getElementById('paymentInput').value) || 0;
        document.getElementById('paymentInput').value = current + amount;
        calculateChange();
    }

    function resetPayment() {
        document.getElementById('paymentInput').value = '';
        calculateChange();
    }

    function calculateChange() {
        const total = getCartTotal();
        const payment = parseFloat(document.getElementById('paymentInput').value) || 0;
        const changeDisplay = document.getElementById('displayChange');
        const changeMsg = document.getElementById('changeMessage');
        const payBtn = document.getElementById('payButton');

        if (total === 0 || cart.length === 0) {
            changeDisplay.textContent = 'Rp 0';
            changeDisplay.className = 'font-mono text-2xl font-extrabold text-slate-800';
            changeMsg.textContent = 'Keranjang kosong.';
            payBtn.disabled = true;
            return;
        }

        const change = payment - total;

        if (payment >= total) {
            changeDisplay.textContent = 'Rp ' + change.toLocaleString('id-ID');
            changeDisplay.className = 'font-mono text-2xl font-extrabold text-emerald-600';
            changeMsg.textContent = change === 0 ? 'Uang Pas' : 'Kembalian siap diserahkan ke pelanggan.';
            payBtn.disabled = false;
        } else {
            const short = total - payment;
            changeDisplay.textContent = '- Rp ' + short.toLocaleString('id-ID');
            changeDisplay.className = 'font-mono text-2xl font-extrabold text-rose-600';
            changeMsg.textContent = `Uang pembayaran kurang Rp ${short.toLocaleString('id-ID')}`;
            payBtn.disabled = true;
        }
    }

    // Process Checkout
    async function processCheckout() {
        const total = getCartTotal();
        const payment = parseFloat(document.getElementById('paymentInput').value) || 0;

        if (cart.length === 0) {
            showToast('Keranjang belanja kosong!', 'error');
            return;
        }

        if (payment < total) {
            showToast('Uang pembayaran masih kurang!', 'error');
            return;
        }

        const payBtn = document.getElementById('payButton');
        payBtn.disabled = true;
        payBtn.innerHTML = `
            <svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
            Memproses Transaksi...
        `;

        try {
            const payload = {
                items: cart.map(i => ({ id: i.id, qty: i.qty })),
                payment_amount: payment
            };

            const response = await fetch('{{ route('pos.checkout') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify(payload)
            });

            const resData = await response.json();

            if (resData.success) {
                // Clear cart & reset UI
                cart = [];
                resetPayment();
                renderCart();

                // Open Receipt in a popup or new tab for instant thermal printing
                const printWindow = window.open(resData.print_url, '_blank', 'width=450,height=650');
                if (printWindow) {
                    printWindow.focus();
                } else {
                    window.location.href = resData.print_url;
                }

                showToast(`Transaksi ${resData.invoice_number} Sukses!`, 'success');
            } else {
                showToast(resData.message || 'Gagal menyimpan transaksi', 'error');
            }
        } catch (err) {
            showToast('Terjadi kesalahan: ' + err.message, 'error');
        } finally {
            payBtn.disabled = false;
            payBtn.innerHTML = `
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                <span>BAYAR &amp; CETAK STRUK</span>
            `;
            barcodeInput.focus();
        }
    }

    // Search Modal Functions
    function openSearchModal() {
        document.getElementById('searchModal').classList.remove('hidden');
        const input = document.getElementById('modalSearchQuery');
        input.value = '';
        input.focus();
        performModalSearch();
    }

    function closeSearchModal() {
        document.getElementById('searchModal').classList.add('hidden');
        barcodeInput.focus();
    }

    async function performModalSearch() {
        const query = document.getElementById('modalSearchQuery').value.trim();
        const resultsEl = document.getElementById('modalSearchResults');

        try {
            const res = await fetch(`{{ route('pos.product.search') }}?q=${encodeURIComponent(query)}`, {
                headers: { 'Accept': 'application/json' }
            });
            const products = await res.json();

            if (products.length === 0) {
                resultsEl.innerHTML = '<div class="text-center py-8 text-slate-400 text-sm">Tidak ada produk ditemukan dengan stok tersedia.</div>';
                return;
            }

            let html = '';
            products.forEach(p => {
                html += `
                    <div onclick="selectProductFromModal(${p.id}, '${p.barcode}', '${escapeHtml(p.name)}', ${p.price_sell}, ${p.stock})"
                        class="p-3 hover:bg-highlight-100 rounded-xl cursor-pointer flex items-center justify-between transition group">
                        <div>
                            <div class="text-sm font-bold text-slate-800 group-hover:text-brand-700">${escapeHtml(p.name)}</div>
                            <div class="text-xs text-slate-400 font-mono">${p.barcode} &bull; Stok: ${p.stock} unit</div>
                        </div>
                        <div class="font-mono font-bold text-sm text-brand-700">
                            Rp ${parseFloat(p.price_sell).toLocaleString('id-ID')}
                        </div>
                    </div>
                `;
            });
            resultsEl.innerHTML = html;
        } catch (err) {
            resultsEl.innerHTML = '<div class="text-center py-4 text-rose-500 text-xs">Error memuat data</div>';
        }
    }

    function selectProductFromModal(id, barcode, name, price, stock) {
        addToCart(id, barcode, name, price, stock);
        closeSearchModal();
    }

    // Recent Modal Functions
    function openRecentModal() {
        document.getElementById('recentModal').classList.remove('hidden');
    }

    function closeRecentModal() {
        document.getElementById('recentModal').classList.add('hidden');
        barcodeInput.focus();
    }

    // Helper: Toast
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        const bg = type === 'success' ? 'bg-emerald-600' : 'bg-rose-600';
        toast.className = `fixed bottom-6 left-6 z-50 flex items-center p-4 ${bg} text-white rounded-xl shadow-2xl transition duration-300 text-sm font-semibold`;
        toast.innerHTML = message;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    function escapeHtml(text) {
        return text.replace(/'/g, "\\'");
    }
</script>
@endpush
