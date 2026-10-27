@extends('admin.layout')

@section('title', 'Laporan Mutasi Stok - Retail POS')

@section('admin-content')
<div class="space-y-4">
    
    <!-- Title & Badge -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-base font-extrabold text-slate-900 tracking-tight">Laporan Mutasi Stok</h2>
            <p class="text-xs text-slate-500">Log pergudangan &amp; status stok fisik</p>
        </div>
        <span class="px-2 py-0.5 bg-blue-50 text-brand-700 text-[10px] font-bold rounded-md border border-blue-200">
            Read-Only
        </span>
    </div>

    <!-- Critical Stock Alert Card (if any stock < 10) -->
    @if($criticalProducts->count() > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-3.5 space-y-2">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-amber-800 flex items-center">
                    <svg class="w-4 h-4 mr-1 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Peringatan Stok Menipis (&lt; 10 Unit)
                </span>
                <span class="text-[10px] bg-amber-200 text-amber-900 font-bold px-1.5 py-0.5 rounded">
                    {{ $criticalProducts->count() }} Produk
                </span>
            </div>
            <div class="divide-y divide-amber-200/60 text-xs">
                @foreach($criticalProducts as $cp)
                    <div class="py-1.5 flex items-center justify-between">
                        <div>
                            <span class="font-bold text-amber-950">{{ $cp->name }}</span>
                            <span class="text-[10px] text-amber-700 font-mono block">{{ $cp->barcode }}</span>
                        </div>
                        <span class="font-mono font-bold text-rose-700 px-2 py-0.5 bg-white rounded border border-amber-300">
                            Sisa {{ $cp->stock }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Filter Form -->
    <form action="{{ route('admin.stock') }}" method="GET" class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm space-y-2.5">
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

        <div>
            <label class="block text-[10px] font-bold uppercase text-slate-500 mb-1">Jenis Mutasi</label>
            <select name="type" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-medium text-slate-700 focus:bg-white focus:border-brand-500">
                <option value="all" {{ $type === 'all' ? 'selected' : '' }}>Semua Jenis Mutasi</option>
                <option value="in" {{ $type === 'in' ? 'selected' : '' }}>Barang Masuk (IN)</option>
                <option value="adjustment" {{ $type === 'adjustment' ? 'selected' : '' }}>Penyesuaian (ADJUSTMENT)</option>
            </select>
        </div>

        <button type="submit" class="w-full py-2 bg-brand-600 hover:bg-brand-700 text-white rounded-lg text-xs font-bold shadow-xs transition">
            Terapkan Filter Mutasi
        </button>
    </form>

    <!-- Vertical Mutation Cards -->
    <div class="space-y-3">
        @forelse($mutations as $m)
            <div class="bg-white p-3.5 rounded-xl border border-slate-200 shadow-sm space-y-1.5">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="text-xs font-bold text-slate-900">{{ $m->product->name ?? 'Produk Terhapus' }}</span>
                        <div class="text-[10px] font-mono text-slate-400">{{ $m->product->barcode ?? '-' }}</div>
                    </div>
                    @if($m->type === 'in')
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            + BARANG MASUK
                        </span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                            &plusmn; KOREKSI STOK
                        </span>
                    @endif
                </div>

                <div class="flex items-center justify-between text-xs pt-1 border-t border-slate-100">
                    <span class="text-slate-500 text-[11px]">
                        Petugas: <strong class="text-slate-700">{{ $m->user->name ?? '-' }}</strong>
                    </span>
                    <span class="font-mono font-bold {{ $m->qty > 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        {{ $m->qty > 0 ? '+'.$m->qty : $m->qty }} Unit
                    </span>
                </div>

                <div class="text-[11px] text-slate-500 bg-slate-50 p-2 rounded-lg border border-slate-100">
                    <span class="font-semibold text-slate-600">Alasan:</span> {{ $m->notes ?? '-' }}
                </div>

                <div class="text-[10px] text-slate-400 text-right">
                    {{ $m->created_at->format('d/m/Y H:i') }}
                </div>
            </div>
        @empty
            <div class="bg-white p-8 rounded-xl border border-slate-200 text-center text-slate-400 text-xs">
                Tidak ada riwayat mutasi stok pada periode ini.
            </div>
        @endforelse

        <!-- Pagination -->
        @if($mutations->hasPages())
            <div class="pt-2">
                {{ $mutations->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
