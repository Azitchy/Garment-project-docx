<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\GarmentController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminSectionController;
use App\Http\Controllers\Admin\InventoryRecordController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'create'])->name('login');
    Route::post('/login', [AuthController::class, 'store'])->name('login.store');
});

Route::post('/logout', [AuthController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin', fn () => redirect()->route('dashboard'));
    Route::get('/dashboard/{section}/create', [AdminSectionController::class, 'create'])->name('dashboard.section.create');
    Route::post('/dashboard/{section}', [AdminSectionController::class, 'store'])->name('dashboard.section.store');
    Route::get('/dashboard/{section}/{record}/edit', [AdminSectionController::class, 'edit'])->name('dashboard.section.edit');
    Route::put('/dashboard/{section}/{record}', [AdminSectionController::class, 'update'])->name('dashboard.section.update');
    Route::delete('/dashboard/{section}/{record}', [AdminSectionController::class, 'destroy'])->name('dashboard.section.destroy');
    Route::get('/dashboard/inventory/{page}', [AdminSectionController::class, 'inventoryPage'])
        ->whereIn('page', ['product-registration', 'stock-in', 'stock-out', 'real-time-tracking', 'barcode-qr-support', 'supplier-management', 'customer-management', 'purchase-management', 'sales-management', 'inventory-transfers', 'stock-adjustments', 'low-stock-alerts', 'reports-analytics'])
        ->name('dashboard.inventory.page');
    Route::get('/dashboard/hr-payroll/{page}', [AdminSectionController::class, 'hrPayrollPage'])
        ->whereIn('page', ['employee-management', 'attendance', 'payroll', 'leave-management', 'performance'])
        ->name('dashboard.hr-payroll.page');
    Route::get('/dashboard/finance/{page}', [AdminSectionController::class, 'financePage'])
        ->whereIn('page', ['income-management', 'expense-management', 'purchase-payments', 'payroll', 'cash-bank', 'receivables', 'payables', 'reconciliation', 'reports'])
        ->name('dashboard.finance.page');
    Route::get('/dashboard/master-data/{page}', [AdminSectionController::class, 'masterDataPage'])
        ->whereIn('page', ['product-master', 'customer-master', 'vendor-master'])
        ->name('dashboard.master-data.page');
    Route::get('/dashboard/orders/{page}', [AdminSectionController::class, 'ordersPage'])
        ->whereIn('page', ['sales-order-workflow', 'order-fulfillment', 'sample-workflow', 'reports'])
        ->name('dashboard.orders.page');
    Route::get('/dashboard/{section}', [AdminSectionController::class, 'show'])->name('dashboard.section');

    Route::prefix('admin')->name('admin.')->group(function (): void {
        Route::resource('inventory-records', InventoryRecordController::class)->except(['show']);
        Route::resource('garments', GarmentController::class)->except(['show']);
        Route::resource('users', AdminUserController::class)->except(['show']);
    });
});
