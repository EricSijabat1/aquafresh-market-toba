<?php

// app/Http/Controllers/Admin/DashboardController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'monthly_revenue' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
        ];

        $recentOrders = Order::with('customer')
            ->latest()
            ->limit(5)
            ->get();

        $topProducts = Product::select('products.*', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->join('order_items', 'products.id', '=', 'order_items.product_id')
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}
