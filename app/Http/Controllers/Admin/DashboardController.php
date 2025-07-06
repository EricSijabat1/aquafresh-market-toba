<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman utama dashboard admin dengan statistik.
     */
    public function index()
    {
        // Mengambil statistik dasar
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
        ];

        // Mengambil 5 pesanan terbaru
        $recentOrders = Order::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        // Mengambil 5 produk terlaris
        $topProducts = Product::select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}
