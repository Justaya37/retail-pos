<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Retail POS & Inventory Management')</title>

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
                            600: '#2563EB', // Primary Royal Blue
                            700: '#1D4ED8', // Deep Navy Hover
                            800: '#1e40af',
                            900: '#1E3A8A', // Navy Table Header
                            950: '#172554',
                        },
                        accent: {
                            400: '#38bdf8',
                            500: '#0EA5E9', // Sky Blue Accent
                            600: '#0284c7',
                        },
                        highlight: {
                            100: '#E0F2FE', // Light Ice Blue
                        }
                    }
                }
            }
        }
    </script>

    <style>
        /* Custom scrollbars */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        @keyframes pulse-highlight {
            0%, 100% { background-color: #E0F2FE; }
            50% { background-color: #BAE6FD; }
        }
        .animate-scan-hit {
            animation: pulse-highlight 0.8s ease-in-out 2;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full font-sans text-slate-800 antialiased selection:bg-brand-600 selection:text-white flex flex-col">

    <!-- Notification Toast Alerts -->
    @if(session('success'))
        <div id="flash-success" class="fixed top-5 right-5 z-50 flex items-center p-4 bg-emerald-600 text-white rounded-xl shadow-2xl transition-all duration-300 transform translate-y-0">
            <svg class="w-6 h-6 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <div class="text-sm font-medium">{{ session('success') }}</div>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-4 text-emerald-200 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('flash-success');
                if (el) el.remove();
            }, 4500);
        </script>
    @endif

    @if(session('error'))
        <div id="flash-error" class="fixed top-5 right-5 z-50 flex items-center p-4 bg-rose-600 text-white rounded-xl shadow-2xl transition-all duration-300">
            <svg class="w-6 h-6 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>
            <div class="text-sm font-medium">{{ session('error') }}</div>
            <button onclick="document.getElementById('flash-error').remove()" class="ml-4 text-rose-200 hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <script>
            setTimeout(() => {
                const el = document.getElementById('flash-error');
                if (el) el.remove();
            }, 5500);
        </script>
    @endif

    @yield('content')

    @stack('scripts')
</body>
</html>
