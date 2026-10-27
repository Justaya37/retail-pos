@extends('layouts.app')

@section('title', 'Login - Retail POS & Inventory')

@section('content')
<div class="min-h-screen bg-slate-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <!-- Brand Header -->
        <div class="flex justify-center">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-brand-600 to-accent-500 flex items-center justify-center shadow-lg shadow-brand-500/30 text-white">
                <svg class="w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                </svg>
            </div>
        </div>
        <h2 class="mt-4 text-center text-3xl font-extrabold tracking-tight text-slate-900">
            Retail POS &amp; Inventory
        </h2>
        <p class="mt-2 text-center text-sm text-slate-600">
            Sistem Kasir, Manajemen Stok Gudang &amp; Laporan Admin
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md px-4 sm:px-0">
        <div class="bg-white py-8 px-6 shadow-xl shadow-slate-200/60 rounded-2xl border border-slate-100 sm:px-10">
            
            @if ($errors->any())
                <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-sm">
                    <div class="font-semibold flex items-center mb-1">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Gagal Masuk
                    </div>
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Email Pengguna</label>
                    <div class="mt-1 relative">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email', 'kasir@toko.com') }}"
                            placeholder="nama@toko.com"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-slate-800 placeholder-slate-400 text-sm transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">Password</label>
                    <div class="mt-1 relative">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            value="password"
                            placeholder="••••••••"
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:border-brand-500 text-slate-800 placeholder-slate-400 text-sm transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 text-brand-600 rounded border-slate-300 focus:ring-brand-500">
                        <span class="ml-2 text-xs font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-md text-sm font-semibold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-500 transition duration-150">
                        <span>Masuk ke Sistem</span>
                        <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                </div>
            </form>

            <!-- Quick Demo Role Switcher Buttons -->
            <div class="mt-6 pt-6 border-t border-slate-100">
                <p class="text-xs font-medium text-slate-500 text-center mb-3">
                    Pilih Cepat Akun Demo (1-Click Fill):
                </p>
                <div class="grid grid-cols-3 gap-2">
                    <button type="button" onclick="fillRole('kasir@toko.com', 'password')"
                        class="flex flex-col items-center p-2 rounded-xl border border-blue-200 bg-blue-50/70 hover:bg-blue-100 text-brand-700 transition">
                        <span class="text-xs font-bold">UKasir</span>
                        <span class="text-[10px] text-blue-600">POS Kiosk</span>
                    </button>
                    <button type="button" onclick="fillRole('gudang@toko.com', 'password')"
                        class="flex flex-col items-center p-2 rounded-xl border border-sky-200 bg-sky-50/70 hover:bg-sky-100 text-sky-700 transition">
                        <span class="text-xs font-bold">UGudang</span>
                        <span class="text-[10px] text-sky-600">Inventory</span>
                    </button>
                    <button type="button" onclick="fillRole('admin@toko.com', 'password')"
                        class="flex flex-col items-center p-2 rounded-xl border border-indigo-200 bg-indigo-50/70 hover:bg-indigo-100 text-indigo-700 transition">
                        <span class="text-xs font-bold">UAdmin</span>
                        <span class="text-[10px] text-indigo-600">Mobile Laporan</span>
                    </button>
                </div>
            </div>

        </div>

        <p class="mt-4 text-center text-xs text-slate-500">
            Environment: <span class="font-semibold text-slate-700">Laragon MySQL</span> &bull; db_retail_pos
        </p>
    </div>
</div>

<script>
    function fillRole(email, password) {
        document.getElementById('email').value = email;
        document.getElementById('password').value = password;
    }
</script>
@endsection
