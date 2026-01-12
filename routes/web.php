<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\DistributorController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ReturnController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        // Simple dashboard logic for now
        $stats = [
            'sales_today' => \App\Models\Sale::whereDate('sale_date', today())->sum('total_amount'),
            'total_products' => \App\Models\Product::count(),
            'low_stock' => \App\Models\Inventory::where('quantity', '<=', 10)->count(),
            'total_customers' => \App\Models\Sale::distinct('customer_name')->count(),
            'recent_sales' => \App\Models\Sale::latest()->take(5)->get()
        ];
        return view('dashboard', compact('stats'));
    })->name('dashboard');

    Route::resource('categories', CategoryController::class);
    Route::resource('sub-categories', SubCategoryController::class);
    Route::resource('brands', BrandController::class);
    Route::resource('distributors', DistributorController::class);
    Route::get('products/get-sub-categories', [ProductController::class, 'getSubCategories'])->name('products.get-sub-categories');
    Route::resource('products', ProductController::class);
    Route::resource('inventory', InventoryController::class);

    // POS Routes
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::get('/pos/search', [POSController::class, 'searchProducts'])->name('pos.search');
    Route::post('/pos/process', [POSController::class, 'processSale'])->name('pos.process');
    Route::get('/pos/invoice/{sale}', [POSController::class, 'showInvoice'])->name('pos.invoice');

    // Return Routes
    Route::get('/returns', [ReturnController::class, 'index'])->name('returns.index');
    Route::get('/returns/search', [ReturnController::class, 'searchInvoice'])->name('returns.search');
    Route::post('/returns/process', [ReturnController::class, 'processReturn'])->name('returns.process');
    Route::get('/returns/invoice/{return}', [ReturnController::class, 'showReturnInvoice'])->name('returns.invoice');

    // Report Routes
    Route::get('/reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/returns', [ReportController::class, 'returns'])->name('reports.returns');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
});
