<?php
// routes/web.php
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('orders.success');
Route::get('/customer/{whatsapp}/orders', [OrderController::class, 'history'])->name('orders.history');

// Route untuk MENAMPILKAN halaman checkout (yang kita panggil dari JavaScript)
Route::post('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Route untuk MEMPROSES pesanan dari form di halaman checkout
Route::post('/checkout/store', [CheckoutController::class, 'store'])->name('checkout.store');

// Admin Routes
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Categories
    Route::resource('categories', AdminCategoryController::class);

    // Products
    Route::resource('products', AdminProductController::class);

    // Orders
    Route::resource('orders', AdminOrderController::class);
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');

    // Customers
    Route::resource('customers', AdminCustomerController::class);
});

// Auth Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
