<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'Admin Mobile Portal - Retail POS')</title>

    <!-- Google Fonts: Inter & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563EB', // Royal Blue
                            700: '#1D4ED8', // Deep Navy
                            800: '#1e40af',
                            900: '#1E3A8A', // Navy
                        },
                        accent: {
                            500: '#0EA5E9', // Sky Blue Accent
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Hide scrollbars for slick mobile app feel */
        body {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="h-full font-sans text-slate-800 antialiased bg-slate-200 flex justify-center">

    <!-- Mobile Frame Container (max-w-md mx-auto) -->
    <div class="w-full max-w-md bg-slate-50 min-h-screen flex flex-col shadow-2xl border-x border-slate-200 relative pb-20">
        
        <!-- Mobile Top Bar (Royal Blue) -->
        <header class="sticky top-0 z-40 bg-brand-600 text-white px-4 py-3.5 shadow-md flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-lg bg-white/15 flex items-center justify-center border border-white/20">
                    <svg class="w-5 h-5 text-sky-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-extrabold tracking-tight flex items-center leading-none">
                        RETAIL ANALYTICS
                        <span class="ml-1.5 px-1.5 py-0.2 bg-white/20 text-sky-100 rounded text-[9px] font-bold">
                            HP / Mobile
                        </span>
                    </div>
                    <div class="text-[10px] text-blue-100 mt-0.5">Admin: {{ Auth::user()->name }}</div>
                </div>
            </div>

            <!-- Logout button -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" class="p-1.5 text-white/80 hover:text-white hover:bg-white/10 rounded-lg transition" title="Keluar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                </button>
            </form>
        </header>

        <!-- Main Body -->
        <main class="flex-1 p-4">
            @yield('admin-content')
        </main>

        <!-- Fixed Bottom Navigation Bar (Touch Friendly Mobile) -->
        <nav class="fixed bottom-0 max-w-md w-full bg-white border-t border-slate-200 z-50 flex justify-around py-2 shadow-2xl">
            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}" 
                class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('admin.dashboard') ? 'text-brand-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-[10px] mt-1">Ringkasan</span>
            </a>

            <!-- Penjualan -->
            <a href="{{ route('admin.sales') }}" 
                class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('admin.sales*') ? 'text-brand-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.sales*') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <span class="text-[10px] mt-1">Penjualan</span>
            </a>

            <!-- Stok & Mutasi -->
            <a href="{{ route('admin.stock') }}" 
                class="flex flex-col items-center py-1 px-3 {{ request()->routeIs('admin.stock') ? 'text-brand-600 font-bold' : 'text-slate-400 hover:text-slate-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.stock') ? 'text-brand-600' : 'text-slate-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                <span class="text-[10px] mt-1">Mutasi Stok</span>
            </a>
        </nav>

    </div>

    @stack('scripts')
</body>
</html>
