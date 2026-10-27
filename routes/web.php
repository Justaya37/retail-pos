<?php

use App\Http\Controllers\AdminReportController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PosController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Root redirect
Route::get('/', function () {
    if (Auth::check()) {
        $user = Auth::user();
        return match ($user->role) {
            'kasir' => redirect()->route('pos.index'),
            'gudang' => redirect()->route('inventory.index'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Kasir (POS) Routes - Desktop Kiosk Mode
Route::middleware(['auth', 'role:kasir'])->prefix('pos')->name('pos.')->group(function () {
    Route::get('/', [PosController::class, 'index'])->name('index');
    Route::get('/product/find', [PosController::class, 'findProduct'])->name('product.find');
    Route::get('/product/search', [PosController::class, 'searchProducts'])->name('product.search');
    Route::post('/checkout', [PosController::class, 'checkout'])->name('checkout');
    Route::get('/receipt/{id}', [PosController::class, 'printReceipt'])->name('print');
});

// Gudang (Inventory) Routes - Desktop Dashboard
Route::middleware(['auth', 'role:gudang'])->prefix('inventory')->name('inventory.')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('index');
    Route::post('/products', [InventoryController::class, 'storeProduct'])->name('products.store');
    Route::put('/products/{id}', [InventoryController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [InventoryController::class, 'destroyProduct'])->name('products.destroy');
    Route::post('/stock-in', [InventoryController::class, 'stockIn'])->name('stock-in');
    Route::post('/stock-adjustment', [InventoryController::class, 'stockAdjustment'])->name('stock-adjustment');
    Route::get('/mutations', [InventoryController::class, 'mutations'])->name('mutations');
});

// Admin Routes - Mobile Web Responsive (Read-Only)
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminReportController::class, 'dashboard'])->name('dashboard');
    Route::get('/sales', [AdminReportController::class, 'sales'])->name('sales');
    Route::get('/sales/{id}', [AdminReportController::class, 'salesDetail'])->name('sales.detail');
    Route::get('/stock', [AdminReportController::class, 'stock'])->name('stock');
});
