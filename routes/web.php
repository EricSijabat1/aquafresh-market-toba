<?php
// routes/web.php
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;
use App\Livewire\CartPage;


// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/category/{category}', [ProductController::class, 'category'])->name('products.category');

// Route untuk Keranjang (Hapus `cart.add` karena sudah dihandle Livewire)
// Tambahkan route baru ini
Route::get('/cart', CartPage::class)->name('cart.index');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/{cartItemId}/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{cartItemId}/remove', [CartController::class, 'remove'])->name('cart.remove');
// Route untuk Keranjang
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add'); // <-- TAMBAHKAN ROUTE INI
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/order/{order}/success', [OrderController::class, 'success'])->name('orders.success');
Route::get('/customer/{whatsapp}/orders', [OrderController::class, 'history'])->name('orders.history');

// Checkout Routes - COMPLETE SOLUTION
Route::get('/checkout', function () {
    // Redirect to products if accessed directly without cart data
    return redirect()->route('products.index')->with('error', 'Silakan pilih produk terlebih dahulu.');
})->name('checkout.show');

// GET request akan menampilkan halaman checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
// POST request akan memproses order
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

// Midtrans Webhook
Route::post('/midtrans/webhook', [WebhookController::class, 'handle']);

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

Route::middleware('guest')->group(function () {
    // ... (route auth lainnya)
    
    // Rute baru untuk login admin
    Route::get('admin/login', [App\Http\Controllers\Auth\AuthenticatedSessionController::class, 'createAdmin'])
                ->name('admin.login');
});

require __DIR__ . '/auth.php';

// --- ADMIN ROUTES ---
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Resource Controllers for CRUD
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show']);
    Route::resource('customers', AdminCustomerController::class)->only(['index', 'show']);

    // Custom route for updating order status
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('orders.invoice'); // <-- Ditambahkan: Route untuk invoice
});