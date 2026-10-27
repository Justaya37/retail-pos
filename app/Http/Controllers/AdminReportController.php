<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\StockMutation;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminReportController extends Controller
{
    public function dashboard(Request $request)
    {
        $today = Carbon::today();

        // Date filter
        $period = $request->query('period', 'today');
        $startDate = $today->copy()->startOfDay();
        $endDate = $today->copy()->endOfDay();

        if ($period === 'yesterday') {
            $startDate = Carbon::yesterday()->startOfDay();
            $endDate = Carbon::yesterday()->endOfDay();
        } elseif ($period === '7days') {
            $startDate = Carbon::today()->subDays(6)->startOfDay();
            $endDate = Carbon::today()->endOfDay();
        } elseif ($period === 'month') {
            $startDate = Carbon::today()->startOfMonth()->startOfDay();
            $endDate = Carbon::today()->endOfMonth()->endOfDay();
        } elseif ($period === 'custom') {
            if ($request->filled('start_date')) {
                $startDate = Carbon::parse($request->query('start_date'))->startOfDay();
            }
            if ($request->filled('end_date')) {
                $endDate = Carbon::parse($request->query('end_date'))->endOfDay();
            }
        }

        // Metrics for selected period
        $periodOmzet = Transaction::whereBetween('created_at', [$startDate, $endDate])->sum('total_amount');
        $periodTransactionsCount = Transaction::whereBetween('created_at', [$startDate, $endDate])->count();
        
        $periodProfit = TransactionDetail::whereHas('transaction', function ($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        })->selectRaw('SUM((price - cost_price) * qty) as profit')->value('profit') ?? 0;

        // Today metrics (for quick KPI snapshot)
        $todayOmzet = Transaction::whereDate('created_at', $today)->sum('total_amount');
        $todayTransactionsCount = Transaction::whereDate('created_at', $today)->count();
        $todayProfit = TransactionDetail::whereHas('transaction', function ($q) use ($today) {
            $q->whereDate('created_at', $today);
        })->selectRaw('SUM((price - cost_price) * qty) as profit')->value('profit') ?? 0;

        // Lifetime metrics
        $allTimeOmzet = Transaction::sum('total_amount');
        $allTimeProfit = TransactionDetail::selectRaw('SUM((price - cost_price) * qty) as profit')->value('profit') ?? 0;
        $totalProductsCount = Product::count();
        $lowStockCount = Product::where('stock', '<', 10)->count();

        // Recent 5 transactions
        $recentTransactions = Transaction::with(['user', 'details'])
            ->latest()
            ->take(5)
            ->get();

        // Top 5 best-selling products in selected period
        $topProducts = TransactionDetail::select(
                'product_id',
                DB::raw('SUM(qty) as total_sold'),
                DB::raw('SUM(subtotal) as total_sales'),
                DB::raw('SUM((price - cost_price) * qty) as total_profit')
            )
            ->whereHas('transaction', function ($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->with('product')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'period',
            'startDate',
            'endDate',
            'periodOmzet',
            'periodTransactionsCount',
            'periodProfit',
            'todayOmzet',
            'todayTransactionsCount',
            'todayProfit',
            'allTimeOmzet',
            'allTimeProfit',
            'totalProductsCount',
            'lowStockCount',
            'recentTransactions',
            'topProducts'
        ));
    }

    public function sales(Request $request)
    {
        $startDate = $request->query('start_date', Carbon::today()->subDays(6)->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());

        $parsedStart = Carbon::parse($startDate)->startOfDay();
        $parsedEnd = Carbon::parse($endDate)->endOfDay();

        $transactions = Transaction::with(['user', 'details.product'])
            ->whereBetween('created_at', [$parsedStart, $parsedEnd])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        // Summary for filtered period
        $filteredOmzet = Transaction::whereBetween('created_at', [$parsedStart, $parsedEnd])->sum('total_amount');
        $filteredProfit = TransactionDetail::whereHas('transaction', function ($q) use ($parsedStart, $parsedEnd) {
            $q->whereBetween('created_at', [$parsedStart, $parsedEnd]);
        })->selectRaw('SUM((price - cost_price) * qty) as profit')->value('profit') ?? 0;
        $filteredCount = Transaction::whereBetween('created_at', [$parsedStart, $parsedEnd])->count();

        return view('admin.sales', compact(
            'transactions',
            'startDate',
            'endDate',
            'filteredOmzet',
            'filteredProfit',
            'filteredCount'
        ));
    }

    public function salesDetail($id)
    {
        $transaction = Transaction::with(['user', 'details.product'])->findOrFail($id);

        $totalProfit = $transaction->details->sum(function ($item) {
            return ($item->price - $item->cost_price) * $item->qty;
        });

        return view('admin.sales-detail', compact('transaction', 'totalProfit'));
    }

    public function stock(Request $request)
    {
        $type = $request->query('type', 'all');
        $startDate = $request->query('start_date', Carbon::today()->subDays(14)->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());

        $parsedStart = Carbon::parse($startDate)->startOfDay();
        $parsedEnd = Carbon::parse($endDate)->endOfDay();

        $query = StockMutation::with(['product', 'user'])
            ->whereBetween('created_at', [$parsedStart, $parsedEnd]);

        if ($type !== 'all' && in_array($type, ['in', 'adjustment'])) {
            $query->where('type', $type);
        }

        $mutations = $query->latest()->paginate(15)->withQueryString();

        // Products with critical stock (< 10)
        $criticalProducts = Product::where('stock', '<', 10)->orderBy('stock', 'asc')->get();

        return view('admin.stock', compact(
            'mutations',
            'criticalProducts',
            'type',
            'startDate',
            'endDate'
        ));
    }
}
